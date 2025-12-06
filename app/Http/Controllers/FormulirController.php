<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Gelombang;
use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use App\Models\ProgramStudi;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        ]);

        $gelombangAktif = Gelombang::where('is_active', true)->firstOrFail();

        DB::transaction(function() use ($validate, $gelombangAktif){
            $prodi = ProgramStudi::with('fakultas')->find($validate['program_studi_id_1']);
            $kodeFakultas = $prodi->fakultas->kode_fakultas;
            $kodeProdi = $prodi->kode_prodi;
            $tahun = now()->format('Y');

            Pendaftar::create([
                'user_id' => Auth::id(),
                'gelombang_id' => $gelombangAktif->id,
                'email' => Auth::user()->email,
                'no_pendaftaran'=> 'MABA-' . Auth::user()->id . $tahun . '-' . $kodeFakultas . $kodeProdi,
                'status' => 'baru',

                ...$validate
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil! Silakan upload dokumen pendukung.');
    }

    public function edit () {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('formulir.create');
        }

        $gelombangAktif = Gelombang::where('is_active', true)->first();
        $jalurPendaftaran = JalurPendaftaran::where('is_active', true)->get();
        $fakultas = Fakultas::with('programStudis')->get();
        $provinces = Province::all();

        return view(
            'formulir.create', compact(
                'gelombangAktif',
                'jalurPendaftaran',
                'fakultas',
                'provinces',
                'pendaftar',
            )
        );
    }

    public function update (Request $request) {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if ($pendaftar->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!Gelombang::where('is_active', true)->exists()) {
            return redirect()->route('dashboard')->with('error', 'Pendaftaran sudah ditutup.');
        }
        
        $validate = $request->validate([
            'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftarans,id',
            'program_studi_id_1' => 'required|exists:program_studis,kode_prodi',
            'program_studi_id_2' => 'nullable|exists:program_studis,kode_prodi|different:program_studi_id_1',
            
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:pendaftars,nik,' .$pendaftar->id, 
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
        ]);

        try {

            $pendaftar->update($validate);
            $pendaftar->refresh();
    
            return redirect()->route('dashboard')->with('success', 'Data pendaftaran berhasil diupdate!');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['msg' => 'Gagal Menyimpan: ', $e->getMessage()]);
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
}
