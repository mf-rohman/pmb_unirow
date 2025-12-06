<x-student-layout>
 
    @php
        $user = Auth::user();
        $pendaftar = $user->pendaftar;
        
        $dokumenWajib = ['Ijazah', 'Foto Terbaru', 'Transkrip Nilai', 'KTP', 'KK', 'Bukti Transfer'];

        if($pendaftar) {
            $pendaftar->refresh();
            $jalur = $pendaftar->jalurPendaftaran;
            $kategori = $jalur->kategori;
            $namaJalur = strtolower($jalur->nama_jalur);

            if($kategori == 'Hafidz' || str_contains($namaJalur, 'hafidz')) {
                $dokumenWajib[] = 'Sertifikat Hafalan';
            }

            if($kategori == 'Prestasi' || str_contains($namaJalur, 'prestasi')) {
                $dokumenWajib[] = 'Sertifikat Prestasi';
            }

            if (str_contains($namaJalur, 'utbk')) {
                $dokumenWajib[] = 'Sertifikat Nilai UTBK';
            }
        }

        $uploadedDocs = $pendaftar ? $pendaftar->dokumenPendaftars->pluck('jenis_dokumen')->toArray() : [];
        
        $jumlahUpload = count(array_intersect($dokumenWajib, $uploadedDocs));
        $totalDokumen = count($dokumenWajib);
        
        $persenData = $pendaftar ? 50 : 0; 
        $persenDokumen = $pendaftar ? ($jumlahUpload / $totalDokumen) * 50 : 0;
        $totalProgres = $persenData + $persenDokumen;

        
    @endphp


    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-[999] flex items-center gap-2 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

  
    <div x-data="{ 
        uploadModalOpen: false, 
        selectedDoc: '',
        openUploadModal(docName) {
            this.selectedDoc = docName;
            this.uploadModalOpen = true;
        }
    }">


        <div class="flex justify-between items-center mb-8">
            <div class="relative w-full max-w-screen-md hidden md:block">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" class="block w-full pl-11 pr-4 py-3 border-none rounded-2xl bg-white text-gray-900 placeholder-gray-400 focus:ring-0 shadow-sm cursor-default" placeholder="Cari informasi...">
            </div>
            <div class="flex items-center gap-4">
                <button class="p-3 bg-white rounded-full text-gray-500 hover:text-[#5D5FEF] shadow-sm transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-2 right-2.5 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
                </button>
            </div>
        </div>


        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
   
            <div class="xl:col-span-8 space-y-8">
                
       
                <div class="relative w-full bg-[#5D5FEF] rounded-[2rem] p-8 md:p-12 text-white overflow-hidden shadow-xl shadow-indigo-200">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-20 w-40 h-40 bg-purple-400 opacity-20 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-md text-xs font-bold tracking-wider mb-4 border border-white/10">PMB 2025</span>
                        <h2 class="text-3xl md:text-4xl font-bold leading-tight mb-6">Siapkan Masa Depanmu <br> Bersama Kampus Impian</h2>
                        @if(!$pendaftar)
                            <a href="{{ route('formulir.create') }}" class="inline-flex items-center px-8 py-3.5 bg-gray-900 text-white rounded-full font-bold hover:bg-black transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                Daftar Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        @else
                            <div class="flex flex-wrap gap-3">
                                <div class="px-5 py-2.5 bg-white text-[#5D5FEF] rounded-full text-sm font-bold shadow-md">No. Reg: {{ $pendaftar->no_pendaftaran }}</div>
                                <div class="px-5 py-2.5 bg-[#4D4DFF] text-white border border-white/20 rounded-full text-sm font-bold">Status: {{ ucwords(str_replace('_', ' ', $pendaftar->status)) }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($pendaftar)
          
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              
                    <div class="bg-white p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm border border-gray-50 hover:shadow-md transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-[#F4F6FA] flex items-center justify-center text-[#5D5FEF] shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Prodi Pilihan</p>
                            <p class="text-base font-bold text-gray-800 truncate">{{ $pendaftar->programStudi1->singkatan ?? Str::limit($pendaftar->programStudi1->nama_prodi, 12) }}</p>
                        </div>
                    </div>
                    
    
                    <div class="bg-white p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm border border-gray-50 hover:shadow-md transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-[#FFF4F2] flex items-center justify-center text-[#FF6B6B] shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Dokumen</p>
                            <p class="text-base font-bold text-gray-800">{{ $jumlahUpload }}/{{ $totalDokumen }} File</p>
                        </div>
                    </div>

           
                    <div class="bg-white p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm border border-gray-50 hover:shadow-md transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-[#F2FBFF] flex items-center justify-center text-[#4D96FF] shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Jalur</p>
                            <p class="text-base font-bold text-gray-800">{{ $pendaftar->jalurPendaftaran->kategori }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Kelengkapan Dokumen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        @foreach($dokumenWajib as $index => $dok)
                            @php 
                             
                                $dokumenSaya = $pendaftar->dokumenPendaftars->firstWhere('jenis_dokumen', $dok);

                                $isUploaded = !empty($dokumenSaya);
                                
                                $colors = ['bg-orange-50 text-orange-500', 'bg-purple-50 text-purple-500', 'bg-blue-50                 text-blue-500', 'bg-pink-50 text-pink-500', 'bg-green-50 text-green-500', 'bg-yellow-50                text-yellow-500'];
                                $theme = $colors[$index % count($colors)];
                            @endphp
                    
                            <div class="group bg-white p-5 rounded-[1.5rem] border border-transparent hover:border-gray-100                    shadow-sm hover:shadow-xl transition-all duration-300 cursor-default">

                                <div class="h-32 rounded-2xl {{ $theme }} flex items-center justify-center mb-4 relative                   overflow-hidden group-hover:scale-[1.02] transition-transform">
                                    
                                    @if($isUploaded)
                                        @php
                                            $ext = pathinfo($dokumenSaya->path_file, PATHINFO_EXTENSION);
                                        @endphp
                    
                                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))

                                            <img src="{{ Storage::url($dokumenSaya->path_file) }}" 
                                                 alt="{{ $dok }}" 
                                                 class="w-full h-full object-cover rounded-2xl">
                                                 

                                            <!-- <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10                   transition-all"></div> -->
                                        @else
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor"                  viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"               stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.            414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-[10px] font-bold mt-1 text-red-500 bg-white px-2 py-0.5                  rounded shadow">PDF FILE</span>
                                            </div>
                                        @endif
                    
                                        <div class="absolute top-2 right-2 bg-green-500 text-white p-1 rounded-full shadow-md">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path                   stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/      ></svg>
                                        </div>
                    
                                    @else

                                        <svg class="w-10 h-10 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24                    24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4                 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    @endif
                                </div>
                    
                                <h4 class="font-bold text-gray-800 text-base mb-1 truncate" title="{{ $dok }}">{{ $dok }}</h4>
                                <p class="text-xs text-gray-400 mb-4">
                                    @if($isUploaded)
                                        {{ $dokumenSaya->nama_asli_file ?? 'File terupload' }}
                                    @else
                                        Wajib (PDF/JPG Max 2MB)
                                    @endif
                                </p>
                                
                                @if($isUploaded)
                                    <div class="flex gap-2">
                                        <a href="{{ Storage::url($dokumenSaya->path_file) }}" target="_blank" class="flex-1                    text-center text-xs font-bold text-indigo-600 bg-indigo-50 py-2.5 rounded-xl                   hover:bg-indigo-100 transition">
                                            Lihat
                                        </a>
                                        <button @click="openUploadModal('{{ $dok }}')" class="flex-1 text-xs font-bold                 text-gray-600 bg-gray-100 py-2.5 rounded-xl hover:bg-gray-200 transition">
                                            Ganti
                                        </button>
                                    </div>
                                @else
                                    <button @click="openUploadModal('{{ $dok }}')" class="w-full text-xs font-bold text-white                  bg-black py-2.5 rounded-xl hover:bg-gray-800 transition shadow-md transform                hover:scale-105">
                                        Upload Sekarang
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            
                    <button 
                        class="w-full flex justify-between items-center px-6 py-4 bg-white hover:bg-gray-50 transition"
                        onclick="toggleV3()">
                        
                        <span class="font-semibold text-gray-800 text-lg">Informasi Tambahan</span>
                
               
                        <svg id="iconV3" class="w-6 h-6 text-gray-500 transform transition-transform duration-300" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                
       
                    <div id="contentV3" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out bg-white px-6">
                
                        <div class="py-6 border-t border-gray-100 space-y-5">
                
                
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100                  transition">
                   
                                <img src="https://cdn.regent.edu/wp-content/uploads/2023/09/student-resources-hero-1760x990.jpg" 
                                     class="w-20 h-20 rounded-xl object-cover" alt="Berita 1">
                
           
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Pendaftaran Gelombang 1 Dibuka</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Pendaftaran mahasiswa baru gelombang 1 resmi dibuka mulai 10 Januari 2025.
                                    </p>
                                </div>
                            </div>
                
              
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100                  transition">
                                <img src="https://cdn.regent.edu/wp-content/uploads/2023/03/how-to-become-a-special-education-teacher-hero-1760x924.jpg" 
                                     class="w-20 h-20 rounded-xl object-cover" alt="Berita 2">
                
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Program Beasiswa Prestasi</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Tersedia program beasiswa prestasi untuk calon mahasiswa berprestasi akademik/                 non-akademik.
                                    </p>
                                </div>
                            </div>
                
     
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100                  transition">
                                <img src="https://www.ucf.edu/wp-content/blogs.dir/20/files/2024/09/UCF_Special-Education-Teacher.jpg" 
                                     class="w-20 h-20 rounded-xl object-cover" alt="Berita 3">
                
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">Jadwal Ujian Seleksi</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Ujian seleksi masuk akan dilaksanakan serentak pada bulan Februari 2025.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script>
                    function toggleV3() {
                        const content = document.getElementById("contentV3");
                        const icon = document.getElementById("iconV3");
                    
                        if (content.style.maxHeight && content.style.maxHeight !== "0px") {
                            content.style.maxHeight = "0px";
                            icon.classList.remove("rotate-180");
                        } else {
                            content.style.maxHeight = content.scrollHeight + "px";
                            icon.classList.add("rotate-180");
                        }
                    }
                </script>

            </div>

           
            <div class="xl:col-span-4 space-y-8">

                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50  top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Statistic</h3>
                    <div class="flex flex-col items-center justify-center mb-8">
                        <div class="relative w-48 h-48">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="96" cy="96" r="88" stroke="#F3F4F6" stroke-width="12" fill="none" />
                                <circle cx="96" cy="96" r="88" stroke="#5D5FEF" stroke-width="12" fill="none" stroke-dasharray="553" stroke-dashoffset="{{ 553 - (553 * ($totalProgres ?? 0)) / 100 }}" stroke-linecap="round" class="transition-all duration-1000 ease-out"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=e0e7ff&color=4f46e5&size=128" class="w-32 h-32 rounded-full object-cover">
                            </div>
                            <div class="absolute top-4 right-4 bg-[#5D5FEF] text-white text-xs font-bold px-3 py-1.5 rounded-full border-4 border-white shadow-lg">{{ number_format($totalProgres ?? 0) }}%</div>
                        </div>
                    </div>
                    <div class="text-center mb-8">
                        <h3 class="text-xl font-bold text-gray-900">Halo, {{ explode(' ', $user->name)[0] }}! 🔥</h3>
                        <p class="text-sm text-gray-400 mt-2 px-4">Lengkapi datamu agar bisa segera diverifikasi.</p>
                    </div>
                    <div class="flex justify-between items-end h-24 px-2 gap-2">
                        @foreach([40, 70, 30, 90, 50] as $h)
                        <div class="w-full bg-[#F4F6FA] rounded-t-lg h-full relative"><div class="absolute bottom-0 w-full bg-[#5D5FEF] rounded-t-lg opacity-80" style="height: {{$h}}%"></div></div>
                        @endforeach
                    </div>
                </div>


                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Bantuan Panitia</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">A</div><div><h4 class="font-bold text-sm text-gray-800">Akademik</h4><p class="text-xs text-gray-400">Info Prodi</p></div></div>
                            <button class="text-xs font-bold text-[#5D5FEF] border border-[#5D5FEF] px-4 py-1.5 rounded-full hover:bg-[#5D5FEF] hover:text-white transition">Chat</button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">K</div><div><h4 class="font-bold text-sm text-gray-800">Keuangan</h4><p class="text-xs text-gray-400">Pembayaran</p></div></div>
                            <button class="text-xs font-bold text-[#5D5FEF] border border-[#5D5FEF] px-4 py-1.5 rounded-full hover:bg-[#5D5FEF] hover:text-white transition">Chat</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="uploadModalOpen" class="fixed inset-0 z-[99] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="uploadModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>


                <div x-show="uploadModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    

                    <form action="{{ route('dokumen.upload') }}" method="POST" enctype="multipart/form-data" class="p-8">
                        @csrf
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Upload Dokumen</h3>
                            <button type="button" @click="uploadModalOpen = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Dokumen</label>
                           
                            <input type="text" name="jenis_dokumen" x-model="selectedDoc" readonly class="w-full bg-gray-100 border-none rounded-xl px-4 py-3 text-gray-600 font-bold cursor-not-allowed">
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File (PDF/JPG)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-[#5D5FEF] transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                                        <p class="text-xs text-gray-500">PDF, JPG or PNG (MAX. 2MB)</p>
                                    </div>
                                    <input id="dropzone-file" name="file" type="file" class="hidden" required />
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-[#5D5FEF] text-white font-bold rounded-xl shadow-lg hover:bg-indigo-600 transition transform hover:-translate-y-1">
                            Simpan Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-student-layout>