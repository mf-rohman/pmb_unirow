<x-student-layout>

    @php 

        $isEdit = isset($pendaftar);

        $formAction = $isEdit ? route('formulir.update') : route('formulir.store');

        $pageTitle = $isEdit ? 'Edit Data' : 'Pendaftaran Baru';

        $currentJalurId = old('jalur_pendaftaran_id', $pendaftar->jalur_pendaftaran_id ?? null);
        $isPrestasiInitial = false;
        if($currentJalurId) {
            $selectedJalurObj = $jalurPendaftaran->firstWhere('id', $currentJalurId);
            if($selectedJalurObj && $selectedJalurObj->kategori == 'Prestasi') {
                $isPrestasiInitial = true;
            }
        }

    @endphp

    <div class="flex justify-between items-center mb-8">
        <div class="relative w-full max-w-md hidden md:block">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" readonly class="block w-full pl-11 pr-4 py-3 border-none rounded-2xl bg-white text-gray-900 placeholder-gray-400 focus:ring-0 shadow-sm cursor-default" placeholder="Formulir Pendaftaran...">
        </div>
        <div class="flex items-center gap-4">
            <button class="p-3 bg-white rounded-full text-gray-500 hover:text-[#5D5FEF] shadow-sm transition relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="absolute top-2 right-2.5 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
            </button>
        </div>
    </div>


    <div class="max-w-5xl mx-auto">
        

        <div class="relative w-full bg-[#5D5FEF] rounded-[2rem] p-8 mb-8 text-white overflow-hidden shadow-xl shadow-indigo-200">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-20 w-40 h-40 bg-purple-400 opacity-20 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold tracking-wider mb-2 border border-white/10">LANGKAH 1 DARI 1</span>
                    <h2 class="text-3xl font-bold leading-tight">Formulir Pendaftaran</h2>
                    <p class="text-indigo-100 text-sm mt-1">Isi data dengan benar sesuai Ijazah/KTP.</p>
                </div>
                

                <div class="px-5 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center gap-3">
                    <div class="p-2 bg-white text-[#5D5FEF] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-200">Gelombang Aktif</p>
                        <p class="font-bold text-sm">{{ $gelombangAktif->nama_gelombang }}</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ $formAction }}" method="POST" class="space-y-8" 
              x-data="pendaftaranHandler({{ $isPrestasiInitial ? 'true' : 'false' }})">
            @csrf
            
            @if($isEdit)
                @method('PUT')
            @endif


            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">

                <!-- SECTION KHUSUS: TOKEN GURU BK / REKOMENDASI -->
                <!-- ============================================= -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50" x-data="{  viaGuru: false }">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center   justify-center text-purple-500 font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24  24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2   2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Jalur Khusus / Rekomendasi</h3>
                    </div>

                    <div class="space-y-4">
                        <!-- Checkbox Toggle -->
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" x-model="viaGuru" class="rounded border-gray-300     text-[#5D5FEF] shadow-sm focus:border-[#5D5FEF] focus:ring focus:ring-  [#5D5FEF] focus:ring-opacity-50">
                            <span class="ml-3 text-gray-700 font-bold">Saya mendaftar melalui Guru  BK / Koordinator Sekolah</span>
                        </label>

                        <!-- Panel Input Token (Muncul jika dicentang) -->
                        <div x-show="viaGuru" x-transition.opacity.duration.300ms class="mt-4 p-6   bg-purple-50 rounded-2xl border border-purple-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Masukkan Kode     Token</label>

                            <div class="relative max-w-md">
                                <!-- Input Token dengan Event Listener -->
                                <input type="text" 
                                       name="token_bk" 
                                       x-model="tokenInput" 
                                       @input.debounce.500ms="checkTokenStatus()"
                                       class="w-full rounded-xl border-gray-200 uppercase tracking-[0.  2em] font-bold text-center text-lg transition-all focus:ring-2    focus:ring-[#5D5FEF] focus:border-[#5D5FEF]"
                                       :class="{
                                           'border-red-500 focus:ring-red-500 focus:border-red-500':    tokenStatus === 'invalid', 
                                           'border-green-500 focus:ring-green-500   focus:border-green-500': tokenStatus === 'valid'
                                       }"
                                       placeholder="KODE-123">

                                <!-- Icon Loading -->
                                <div x-show="isLoadingToken" class="absolute right-4 top-3.5"   style="display: none;">
                                    <svg class="animate-spin h-5 w-5 text-purple-600" xmlns="http://    www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle    class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"    stroke-width="4"></circle><path class="opacity-75"     fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0    12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.  938l3-2.647z"></path></svg>
                                </div>

                                <!-- Icon Valid (Centang) -->
                                <div x-show="tokenStatus === 'valid' && !isLoadingToken"    class="absolute right-4 top-3.5 text-green-500" style="display: none;  ">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0   0 24 24"><path stroke-linecap="round" stroke-linejoin="round"     stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>

                                <!-- Icon Invalid (Silang) -->
                                <div x-show="tokenStatus === 'invalid' && !isLoadingToken"  class="absolute right-4 top-3.5 text-red-500" style="display: none;">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0   0 24 24"><path stroke-linecap="round" stroke-linejoin="round"     stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                            </div>

                            <!-- Pesan Feedback di Bawah Input -->
                            <p x-show="tokenMessage" x-text="tokenMessage" 
                               class="text-sm mt-3 font-bold transition-all"
                               :class="tokenStatus === 'valid' ? 'text-green-600' : 'text-red-600'"
                               style="display: none;"></p>

                            <p class="text-xs text-gray-500 mt-2 italic">*Kode token bisa didapatkan    dari Guru BK sekolah Anda.</p>
                        </div>
                    </div>
                </div>
                

                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-[#5D5FEF] font-bold">1</div>
                    <h3 class="text-xl font-bold text-gray-800">Jalur & Program Studi</h3>
                </div>


                <div class="space-y-6">

                    {{-- A. PILIH JALUR PENDAFTARAN (Looping dari Database) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Jalur Pendaftaran</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($jalurPendaftaran as $jalur)
                            <label class="relative cursor-pointer group">
                                <input id="jalur-{{$jalur->id}}" type="radio" name="jalur_pendaftaran_id" value="{{ $jalur->id }}" 
                                       class="peer sr-only"
                                       x-model="selectedJalur"
                                       data-kategori="{{ $jalur->nama_jalur }}" 
                                       @change="setKategori('{{ $jalur->nama_jalur }}')"
                                       {{ old('jalur_pendaftaran_id', $pendaftar->jalur_pendaftaran_id ?? '') == $jalur->id ? 'checked' : '' }}>

                                {{-- Tampilan Card Jalur --}}
                                <div class="p-5 rounded-2xl border-2 transition-all h-full"
                                     :class="selectedJalur == '{{ $jalur->id }}' ? 'border-[#5D5FEF] bg-indigo-50/50' : 'border-gray-100 bg-white hover:border-indigo-200'">

                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-gray-800">{{ $jalur->nama_jalur }}</span>
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                             :class="selectedJalur == '{{ $jalur->id }}' ? 'border-[#5D5FEF] bg-[#5D5FEF]' :            'border-gray-300'">
                                            <div class="w-2.5 h-2.5 bg-white rounded-full transition-opacity"
                                                 :class="selectedJalur == '{{ $jalur->id }}' ? 'opacity-100' : 'opacity-0'"></div>
                                        </div>
                                    </div>

                                    @if($jalur->bebas_tes_tulis)
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-md">Bebas Tes</span>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('jalur_pendaftaran_id')" class="mt-2" />
                    </div>

                    {{-- B. PILIHAN PRODI (Tetap sama) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilihan 1 (Wajib)</label>
                            <select name="program_studi_id_1" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4             focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]" required>
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach($fakultas as $f)
                                    <optgroup label="{{ $f->nama_fakultas }}">
                                        @foreach($f->programStudis as $prodi)
                                            <option value="{{ $prodi->kode_prodi }}" {{ old('program_studi_id_1') ==            $prodi->kode_prodi ? 'selected' : '' }}>
                                                {{ $prodi->jenjang }} {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('program_studi_id_1')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilihan 2 (Opsional)</label>
                            <select name="program_studi_id_2" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4             focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                                <option value="">-- Boleh Kosong --</option>
                                @foreach($fakultas as $f)
                                    <optgroup label="{{ $f->nama_fakultas }}">
                                        @foreach($f->programStudis as $prodi)
                                            <option value="{{ $prodi->kode_prodi }}" {{ old('program_studi_id_2') ==            $prodi->kode_prodi ? 'selected' : '' }}>
                                                {{ $prodi->jenjang }} {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- C. KONDISIONAL INPUT (SESUAI DATABASE & KATEGORI) --}}

                    {{-- 1. Input Nilai Rapor (Muncul jika kategori mengandung kata 'Prestasi') --}}
                    <template x-if="currentKategori && currentKategori.includes('Prestasi')">
                        <div class="p-6 bg-yellow-50 rounded-2xl border border-yellow-100 transition-all">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0       01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-yellow-800">Input Nilai Rapor</h4>
                                    <p class="text-sm text-yellow-700 mt-1">Masukkan nilai rata-rata sesuai semester.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                {{-- Sesuai Kolom DB: nilai_rapor_x_1 --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Kelas X Sem 1</label>
                                    <input type="number" step="0.01" name="nilai_rapor_x_1" value="{{ old('nilai_rapor_x_1',            $pendaftar->nilai_rapor_x_1 ?? '') }}" class="w-full rounded-lg border-gray-200         focus:ring-yellow-400 focus:border-yellow-400" placeholder="00.00">
                                </div>
                                {{-- Sesuai Kolom DB: nilai_rapor_x_2 --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Kelas X Sem 2</label>
                                    <input type="number" step="0.01" name="nilai_rapor_x_2" value="{{ old('nilai_rapor_x_2',            $pendaftar->nilai_rapor_x_2 ?? '') }}" class="w-full rounded-lg border-gray-200         focus:ring-yellow-400 focus:border-yellow-400" placeholder="00.00">
                                </div>
                                {{-- Sesuai Kolom DB: nilai_rapor_xi_1 --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Kelas XI Sem 1</label>
                                    <input type="number" step="0.01" name="nilai_rapor_xi_1" value="{{ old('nilai_rapor_xi_1',          $pendaftar->nilai_rapor_xi_1 ?? '') }}" class="w-full rounded-lg border-gray-200            focus:ring-yellow-400 focus:border-yellow-400" placeholder="00.00">
                                </div>
                                {{-- Sesuai Kolom DB: nilai_rapor_xi_2 --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Kelas XI Sem 2</label>
                                    <input type="number" step="0.01" name="nilai_rapor_xi_2" value="{{ old('nilai_rapor_xi_2',          $pendaftar->nilai_rapor_xi_2 ?? '') }}" class="w-full rounded-lg border-gray-200            focus:ring-yellow-400 focus:border-yellow-400" placeholder="00.00">
                                </div>
                            </div>

                            {{-- Prestasi Non Akademik (Opsional jika ada di DB) --}}
                            <div class="mt-4">
                                 <label class="block text-xs font-bold text-gray-600 mb-1">Prestasi Non Akademik (Jika Ada)</label>
                                 <input type="text" name="prestasi_non_akademik" value="{{ old('prestasi_non_akademik',             $pendaftar->prestasi_non_akademik ?? '') }}" class="w-full rounded-lg border-gray-200           focus:ring-yellow-400 focus:border-yellow-400" placeholder="Contoh: Juara 1 Lomba Pidato Provinsi">
                            </div>
                        </div>
                    </template>

                    {{-- 2. Input Hafalan (Muncul jika kategori mengandung kata 'Hafidz' atau 'Tahfidz') --}}
                    <template x-if="currentKategori && currentKategori.includes('Hafidz')">
                        <div class="p-6 bg-green-50 rounded-2xl border border-green-100 transition-all">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477       9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168           5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.       332.477-4.5 1.253"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-green-800">Syarat Hafidz Qur'an</h4>
                                </div>
                            </div>

                            {{-- Sesuai Kolom DB: jumlah_hafalan_juz --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Jumlah Hafalan Juz</label>
                                <input type="number" name="jumlah_hafalan_juz" value="{{ old('jumlah_hafalan_juz',          $pendaftar->jumlah_hafalan_juz ?? '') }}" class="w-full rounded-lg border-gray-200          focus:ring-green-400 focus:border-green-400" placeholder="Contoh: 5">
                                <x-input-error :messages="$errors->get('jumlah_hafalan_juz')" class="mt-1" />
                            </div>
                        </div>
                    </template>

                    {{-- 3. Input UTBK (Muncul jika kategori mengandung kata 'UTBK') --}}
                    <template x-if="currentKategori && currentKategori.includes('UTBK')">
                        <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 transition-all">
                             <div class="flex items-start gap-3 mb-4">
                                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0         002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012        2"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-800">Syarat Nilai UTBK</h4>
                                </div>
                            </div>

                            {{-- Sesuai Kolom DB: nomor_peserta_utbk --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Nomor Peserta UTBK</label>
                                <input type="text" name="nomor_peserta_utbk" value="{{ old('nomor_peserta_utbk',            $pendaftar->nomor_peserta_utbk ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-blue-400          focus:border-blue-400" placeholder="Masukkan nomor peserta">
                                <x-input-error :messages="$errors->get('nomor_peserta_utbk')" class="mt-1" />
                            </div>
                        </div>
                    </template>

                    <template x-if="currentKategori && currentKategori.includes('RPL')">
                        <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 transition-all">
                             <div class="flex items-start gap-3 mb-4">
                                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0         002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012        2"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-800">Syarat Nilai UTBK</h4>
                                </div>
                            </div>

                            {{-- Sesuai Kolom DB: nomor_peserta_utbk --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Nomor Peserta UTBK</label>
                                <input type="text" name="nomor_peserta_utbk" value="{{ old('nomor_peserta_utbk',            $pendaftar->nomor_peserta_utbk ?? '') }}" class="w-full rounded-lg border-gray-200 focus:ring-blue-400          focus:border-blue-400" placeholder="Masukkan nomor peserta">
                                <x-input-error :messages="$errors->get('nomor_peserta_utbk')" class="mt-1" />
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-pink-500 font-bold">2</div>
                    <h3 class="text-xl font-bold text-gray-800">Biodata Diri</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF] transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">NIK</label>
                        <input type="number" name="nik" value="{{ old('nik', $pendaftar->nik ?? '') }}" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF] transition-all" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">NISN</label>
                        <input type="number" name="nisn" value="{{old('nisn', $pendaftar->nisn ?? '')}}" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF] transition-all" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftar->tempat_lahir ?? '') }}" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF] transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir ?? '') }}" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF] transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Agama</label>
                        <select name="agama" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">No. HP (WhatsApp)</label>
                        <input type="number" name="no_hp" value="{{ old('no_hp', $pendaftar->no_hp ?? '') }}" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email (Terdaftar)</label>
                        <input type="email" value="{{ Auth::user()->email }}" class="w-full rounded-xl border-gray-200 bg-gray-200 py-3 px-4 text-gray-500 cursor-not-allowed" readonly>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-500 font-bold">3</div>
                    <h3 class="text-xl font-bold text-gray-800">Sekolah & Orang Tua</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]" placeholder="SMA/SMK..." required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jurusan Sekolah</label>
                        <input type="text" name="jurusan_asal_sekolah" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]" placeholder="IPA/IPS/TKJ...">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ibu Kandung</label>
                        <input type="text" name="nama_ibu_kandung" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ayah Kandung</label>
                        <input type="text" name="nama_ayah_kandung" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 font-bold">4</div>
                    <h3 class="text-xl font-bold text-gray-800">Alamat Domisili</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]" placeholder="Nama Jalan, No. Rumah, Gang..." required></textarea>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">RT</label>
                            <input type="number" name="rt" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">RW</label>
                            <input type="number" name="rw" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                        <select name="province_id" x-model="selectedProvince" @change="fetchRegencies()" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kabupaten/Kota</label>
                        <select name="regency_id" x-model="selectedRegency" @change="fetchDistricts()" :disabled="!selectedProvince" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                            <option value="">-- Pilih Kabupaten --</option>
                            <template x-for="reg in regencies" :key="reg.id">
                                <option :value="reg.id" x-text="reg.name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                        <select name="district_id" x-model="selectedDistrict" @change="fetchVillages()" :disabled="!selectedRegency" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                            <option value="">-- Pilih Kecamatan --</option>
                            <template x-for="dist in districts" :key="dist.id">
                                <option :value="dist.id" x-text="dist.name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Desa/Kelurahan</label>
                        <select name="village_id" :disabled="!selectedDistrict" class="w-full rounded-xl border-gray-200 bg-gray-50 py-3 px-4 focus:ring-2 focus:ring-[#5D5FEF] focus:border-[#5D5FEF]">
                            <option value="">-- Pilih Desa --</option>
                            <template x-for="vil in villages" :key="vil.id">
                                <option :value="vil.id" x-text="vil.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 pb-12">
                <button type="button" onclick="history.back()" class="px-8 py-4 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-8 py-4 rounded-xl bg-gray-900 text-white font-bold hover:bg-black transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Simpan Pendaftaran
                </button>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pendaftaranHandler', (initialPrestasi) => ({
               
                selectedJalur: `{{ old('jalur_pendaftaran_id', $pendaftar->jalur_pendaftaran_id ?? '') }}`,
                isPrestasi: initialPrestasi,

                selectedProvince: `{{ old('province_id', $pendaftar->province_id ?? '') }}`,
                selectedRegency: `{{ old('regency_id', $pendaftar->regency_id ?? '') }}`,
                selectedDistrict: `{{ old('district_id', $pendaftar->district_id ?? '') }}`,
                selectedVillage: `{{ old('village_id', $pendaftar->village_id ?? '') }}`,

                currentKategori: '',

                regencies: [],
                districts: [],
                villages: [],

                tokenInput: '',
                tokenStatus: '',
                tokenMessage: '',
                isLoadingToken: false,

                async init() {

                    if(this.selectedJalur) {
                         this.$nextTick(() => {
                            const checkedRadio = document.querySelector(`input[name="jalur_pendaftaran_id"]:checked`);
                            if(checkedRadio) {
                                this.currentKategori = checkedRadio.getAttribute('data-kategori');
                            }
                         });
                    }
                    
                    if (this.selectedProvince) {
                        
                        await this.fetchRegencies(true); 
                    }

                    if (this.selectedRegency) {
                        await this.fetchDistricts(true);
                    }

                    if (this.selectedDistrict) {
                        await this.fetchVillages(true);
                    }

                },

                
                setKategori(kategori) {
                    this.currentKategori = kategori;
                },

                checkKategori(kategori) {
                    this.isPrestasi = (kategori === 'Prestasi');
                },

                async checkTokenStatus() {
                    // Reset jika input terlalu pendek
                    if (!this.tokenInput || this.tokenInput.length < 3) {
                        this.tokenStatus = ''; 
                        this.tokenMessage = '';
                        return;
                    }

                    this.isLoadingToken = true;
                    this.tokenMessage = 'Mengecek token...';
                    this.tokenStatus = '';

                    try {
                        // Panggil API Laravel
                        let response = await fetch(`/api/check-token?kode=${this.tokenInput}`);
                        let data = await response.json();

                        this.isLoadingToken = false;

                        if (data.valid) {
                            this.tokenStatus = 'valid';
                            this.tokenMessage = '✅ ' + data.message;
                        } else {
                            this.tokenStatus = 'invalid';
                            this.tokenMessage = '❌ ' + data.message;
                        }
                    } catch (e) {
                        console.error(e);
                        this.isLoadingToken = false;
                        this.tokenStatus = 'invalid';
                        this.tokenMessage = 'Terjadi kesalahan koneksi.';
                    }
                },


                async fetchRegencies(keepValue = false) {
                  
                    this.regencies = []; 
                    this.districts = []; 
                    this.villages = [];

  
                    if (!keepValue) {
                        this.selectedRegency = '';
                        this.selectedDistrict = '';
                        this.selectedVillage = '';
                    }

                    if (!this.selectedProvince) return;

                    try {
                        let response = await fetch('/api-wilayah/regencies/' + this.selectedProvince);
                        this.regencies = await response.json();
                    } catch (e) {
                        console.error('Gagal ambil kabupaten', e);
                    }
                },

                async fetchDistricts(keepValue = false) {
                    this.districts = []; 
                    this.villages = [];

                    if (!keepValue) {
                        this.selectedDistrict = '';
                        this.selectedVillage = '';
                    }

                    if (!this.selectedRegency) return;

                    try {
                        let response = await fetch('/api-wilayah/districts/' + this.selectedRegency);
                        this.districts = await response.json();
                    } catch (e) {
                        console.error('Gagal ambil kecamatan', e);
                    }
                },

                async fetchVillages(keepValue = false) {
                    this.villages = [];

                    if (!keepValue) {
                        this.selectedVillage = '';
                    }

                    if (!this.selectedDistrict) return;

                    try {
                        let response = await fetch('/api-wilayah/villages/' + this.selectedDistrict);
                        this.villages = await response.json();
                    } catch (e) {
                        console.error('Gagal ambil desa', e);
                    }
                }
            }))
        })
    </script>
    
</x-student-layout>