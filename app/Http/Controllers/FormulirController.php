<?php

namespace App\Http\Controllers;

use App\Models\DokumenPendaftar;
use App\Models\Fakultas;
use App\Models\Gelombang;
use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use App\Models\ProgramStudi;
use App\Models\Province;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; 

class FormulirController extends Controller
{
    public function create () {
        $user = Auth::user();

        if($user->pendaftar) {
            return redirect()->route('dashboard')
                ->with('status', 'Anda Sudah Terdaftar. Silahkan cek status kelulusan');
        }

        $gelombangAktif = Gelombang::where('is_active', true)->first();


        if(!$gelombangAktif) {
            return redirect()->route('dashboard')
                ->with('error', 'Saat ini tidak ada gelombang pendaftaran yang dibuka' );
        }

        $jalurPendaftaran = JalurPendaftaran::where('is_active', true)->get();

        $fakultas = Fakultas::with('programStudis')->get(); 
        $provinces = Province::all();

        return view('formulir.create', compact(
            'gelombangAktif',
            'jalurPendaftaran',
            'fakultas',
            'provinces'
        ));

    }

    public function store (Request $request) {
        

        $validate = $request->validate([
            'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftarans,id',
            'program_studi_id_1' => 'required|exists:program_studis,kode_prodi',
            'program_studi_id_2' => 'nullable|exists:program_studis,kode_prodi|different:program_studi_id_1',
            
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16', 
            'nisn' => 'required|numeric',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'no_hp' => 'required|numeric',
            
            'asal_sekolah' => 'required|string',
            'jurusan_asal_sekolah' => 'nullable|string',
            'nama_ibu_kandung' => 'required|string',
            'nama_ayah_kandung' => 'nullable|string',
            
            'alamat_lengkap' => 'required|string',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',

            'nilai_rapor_x_1' => 'nullable|numeric|between:0,100',
            'nilai_rapor_x_2' => 'nullable|numeric|between:0,100',
            'nilai_rapor_xi_1' => 'nullable|numeric|between:0,100',
            'nilai_rapor_xi_2' => 'nullable|numeric|between:0,100',
            'token_bk' => 'nullable|string',
        ]);

        $tokenData = null;
        if ($request->filled('token_bk')) {
            $kode = $request->token_bk;

            $tokenData = Token::where('kode', $kode)->first();

            if(!$tokenData) {
                return back()->withInput()->withErrors(['token_bk' => 'Token tidak ditemukan / salah']);
            }

            if($tokenData->is_used) {
                return back()->withInput()->withErrors(['token_bk' => 'Token sudah terpaki']);
            }

            if($tokenData->expired_at < now()) {
                return back()-> withInput()->withErrors(['token_data' => 'Token ini sudah kadaluarsa']);
            }
        }

        $gelombangAktif = Gelombang::where('is_active', true)->firstOrFail();

        $pendaftar = DB::transaction(function() use ($validate, $gelombangAktif, $tokenData){
            $user = Auth::user();
            $prodi = ProgramStudi::where('kode_prodi', $validate['program_studi_id_1'])->firstOrFail();
            $kodeFakultas = $prodi->fakultas->kode_fakultas;
            $kodeProdi = $prodi->kode_prodi;
            $tahun = now()->format('Y');

            $dataBersih = Arr::except($validate, ['token_bk']);

            $pendaftar = Pendaftar::create([
                'user_id' => Auth::id(),
                'gelombang_id' => $gelombangAktif->id,
                'email' => $user->email,
                'no_pendaftaran'=> 'MABA-' . $user->id . $tahun . '-' . $kodeFakultas . $kodeProdi,
                'status' => 'draft',

                ...$dataBersih
            ]);

            if($tokenData) {
                $tokenData->update([
                    'is_used' => true,
                    'used_at' => now(),
                    'pendaftar_id' => $pendaftar->id, 
                ]);
            }
            return $pendaftar;
        });

        return redirect()->route('formulir.upload', $pendaftar->id)
            ->with('success', 'Biodata tersimpan. Silakan upload berkas.');
    }

    public function showUploadPage($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        
        // Keamanan: Pastikan yang akses adalah pemilik data
        if ($pendaftar->user_id !== Auth::id()) abort(403);

        // Jika status sudah bukan draft (sudah selesai), lempar ke dashboard
        if ($pendaftar->status != 'draft') return redirect()->route('dashboard');

        return view('formulir.upload', compact('pendaftar'));
    }

    public function storeUpload(Request $request, $id)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);
        if ($pendaftar->user_id !== Auth::id()) abort(403);

        $path = $request->file('file')->store('dokumen-pendaftar', 'public');

        DokumenPendaftar::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id, 'jenis_dokumen' => $request->jenis_dokumen],
            [
                'path_file' => $path,
                'nama_file' => $request->file('file')->getClientOriginalName(),
                'status_verifikasi' => 'menunggu'
            ]
        );

        return response()->json(['success' => true]);
    }

    public function finalize($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        
        // 1. Keamanan: Pastikan yang akses adalah pemilik data
        if ($pendaftar->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // 2. Tentukan Dokumen Wajib (Sama seperti logika di View)
        // Dokumen dasar
        $wajib = ['Ijazah', 'Foto Terbaru', 'Transkrip Nilai', 'KTP', 'KK', 'Bukti Transfer'];

        // Cek Dokumen Tambahan berdasarkan Jalur
        $jalur = $pendaftar->jalurPendaftaran;
        if ($jalur) {
            $namaJalur = strtolower($jalur->nama_jalur);
            $kategori = $jalur->kategori;

            if ($kategori == 'Hafidz' || str_contains($namaJalur, 'hafidz')) {
                $wajib[] = 'Sertifikat Hafalan';
            }
            if ($kategori == 'Prestasi' || str_contains($namaJalur, 'prestasi')) {
                $wajib[] = 'Sertifikat Prestasi';
            }
            if (str_contains($namaJalur, 'utbk')) {
                $wajib[] = 'Sertifikat Nilai UTBK';
            }
        }

        // 3. Cek apa saja yang sudah diupload
        $uploaded = $pendaftar->dokumenPendaftars()->pluck('jenis_dokumen')->toArray();

        // 4. Bandingkan: Apakah ada dokumen wajib yang BELUM ada di uploaded?
        // array_diff = mencari item di $wajib yang tidak ada di $uploaded
        $kurang = array_diff($wajib, $uploaded);

        if (!empty($kurang)) {
            // Jika ada yang kurang, kembalikan dengan error
            return back()->with('error', 'Mohon lengkapi semua dokumen wajib sebelum menyelesaikan pendaftaran.');
        }

        // 5. Jika Lengkap, Ubah status jadi 'baru'
        $pendaftar->update([
            'status' => 'baru'
        ]);

        // 6. Kembali ke Dashboard
        return redirect()->route('dashboard')->with('success', 'Selamat! Pendaftaran Anda telah selesai dikirim. Tunggu verifikasi admin.');
    }

    public function edit()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) return redirect()->route('formulir.create');

        $gelombangAktif = Gelombang::where('is_active', true)->first();
        $jalurPendaftaran = JalurPendaftaran::where('is_active', true)->get();
        $fakultas = Fakultas::with('programStudis')->get();
        $provinces = Province::all();

        return view('formulir.create', compact('gelombangAktif', 'jalurPendaftaran', 'fakultas', 'provinces', 'pendaftar'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar || $pendaftar->user_id !== Auth::id()) abort(403, 'Unauthorized');

        // Validasi - LENGKAPI SEMUA FIELD
        $validate = $request->validate([
            'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftarans,id',
            'program_studi_id_1' => 'required|exists:program_studis,kode_prodi',
            'program_studi_id_2' => 'nullable|exists:program_studis,kode_prodi|different:program_studi_id_1',
            'nama_lengkap' => 'required|string|max:255',

            'nik' => [
                'required', 
                'numeric', 
                'digits:16', 
                Rule::unique('pendaftars', 'nik')->ignore($pendaftar->id, 'id') // Eksplisit sebutkan kolom ID
            ],

            'nisn' => 'required|numeric',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'no_hp' => 'required|numeric',
            'asal_sekolah' => 'required|string',
            'jurusan_asal_sekolah' => 'nullable|string',
            'nama_ibu_kandung' => 'required|string',
            'nama_ayah_kandung' => 'nullable|string',
            'alamat_lengkap' => 'required|string',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'province_id' => 'required|exists:provinces,id',
            'regency_id' => 'required|exists:regencies,id',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',

            // TAMBAHKAN YANG HILANG
            'nilai_rapor_x_1' => 'nullable|numeric|between:0,100',
            'nilai_rapor_x_2' => 'nullable|numeric|between:0,100',
            'nilai_rapor_xi_1' => 'nullable|numeric|between:0,100',
            'nilai_rapor_xi_2' => 'nullable|numeric|between:0,100',

            'token_bk' => 'nullable|string',
        ]);

        try {
            // Buang token_bk dari data yang akan di-update
            $dataToUpdate = Arr::except($validate, ['token_bk']);

            // DEBUG: Tambahkan ini untuk melihat data yang akan di-update
            Log::info('Data yang akan di-update:', $dataToUpdate);

            $updated = $pendaftar->update($dataToUpdate);

            // DEBUG: Cek apakah update berhasil
            Log::info('Status update:', ['success' => $updated]);

            if ($pendaftar->status == 'draft') {
                return redirect()->route('formulir.upload', $pendaftar->id)
                    ->with('success', 'Perubahan disimpan. Silakan lanjutkan upload berkas.');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Data pendaftaran berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error saat update pendaftar:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->withErrors(['msg' => 'Gagal Menyimpan: ' . $e->getMessage()]);
        }
    }

    public function uploadDokumen(Request $request)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $path = $request->file('file')->store('dokumen-pendaftar', 'public');

        
        \App\Models\DokumenPendaftar::updateOrCreate(
            [
                'pendaftar_id' => $pendaftar->id,
                'jenis_dokumen' => $request->jenis_dokumen,
            ],
            [
                'path_file' => $path,
                'nama_file' => $request->file('file')->getClientOriginalName(),
                'status_verifikasi' => 'menunggu',
                'catatan_verifikasi' => null,
            ]
        );

        return back()->with('success', 'Dokumen ' . $request->jenis_dokumen . ' berhasil diupload!');
    }

    public function cetakKartu() {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('dashboard');
            
        }

        $data = [
            'pendaftar' => $pendaftar,
            'user' => $user,
            'tanggal_cetak' => now()->format('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.kartu-peserta', $data);

        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Kartu-Peserta-' . $pendaftar->no_pendaftaran . '.pdf');
    }

    public function checkToken (Request $request) {
        $kode = $request->query('kode');

        if (!$kode) {
            return response()->json([
                'valid' => false,
                'message' => 'Token Kosong'
            ]);
        }

        $token = Token::where('kode', $kode)->first();

        if(!$token) {
            return response()->json([
                'valid' => false,
                'message' => 'Token tidak valid / tidak ditemukan'
            ]);
        }

        if($token->is_used) {
            return response()->json([
                'valid' => false,
                'message' => 'Token ini sudah terpakai'
            ]);
        }

        if($token->expired_at < now()) {
            return response()->json([
                'valid' => false,
                'message' => 'Token Kadaluarsa'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Token Valid! (Guru BK: ' . ($token->nama_guru_bk ?? 'Tidak Diketahui') . ' )'
        ]);
    }
}
