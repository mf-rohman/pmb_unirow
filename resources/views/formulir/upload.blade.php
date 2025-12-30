<x-student-layout>
    <div class="flex justify-between items-center mb-6 sm:mb-8 px-4 sm:px-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Langkah 2: Upload Berkas</h1>
    </div>

    @if(session('error'))
        <div class="mb-6 mx-4 sm:mx-0 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-start gap-3">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="font-bold">Gagal Menyelesaikan:</p>
                <p class="text-sm sm:text-base">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-indigo-600 rounded-2xl sm:rounded-[2rem] p-6 sm:p-8 mb-6 sm:mb-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-20 w-40 h-40 bg-purple-400 opacity-20 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <span class="inline-block py-1 px-3 rounded-full bg-white/20 text-xs font-bold mb-2">LANGKAH TERAKHIR</span>
                <h2 class="text-xl sm:text-2xl font-bold">Lengkapi Dokumen Anda</h2>
                <p class="text-indigo-100 text-xs sm:text-sm mt-1">Silakan upload dokumen wajib di bawah ini sebelum menyelesaikan pendaftaran.</p>
            </div>
        </div>

        @php
            // 1. Dokumen Dasar
            $dokumenWajib = ['Ijazah', 'Foto Terbaru', 'Transkrip Nilai', 'KTP', 'KK', 'Bukti Transfer'];
            
            // 2. Dokumen Tambahan (Sesuai Jalur)
            $jalur = $pendaftar->jalurPendaftaran;
            if ($jalur) {
                $namaJalur = strtolower($jalur->nama_jalur);
                $kategori = $jalur->kategori;

                if ($kategori == 'Hafidz' || str_contains($namaJalur, 'hafidz')) {
                    $dokumenWajib[] = 'Sertifikat Hafalan';
                }
                if ($kategori == 'Prestasi' || str_contains($namaJalur, 'prestasi')) {
                    $dokumenWajib[] = 'Sertifikat Prestasi';
                }
                if (str_contains($namaJalur, 'utbk')) {
                    $dokumenWajib[] = 'Sertifikat Nilai UTBK';
                }
            }
            
            // 3. Dokumen yang sudah ada di database
            $uploadedDocs = $pendaftar->dokumenPendaftars->pluck('jenis_dokumen')->toArray();
        @endphp

        <div x-data="uploadHandler(@js($dokumenWajib), @js($uploadedDocs))">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($dokumenWajib as $dok)
                    @php $isUploaded = in_array($dok, $uploadedDocs); @endphp
                    
                    <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl border transition group relative"
                         :class="isUploaded('{{ $dok }}') ? 'border-green-200 ring-2 ring-green-50' : 'border-gray-200 shadow-sm hover:shadow-md'"
                         id="card-{{ Str::slug($dok) }}">
                        
                        <div class="h-28 sm:h-32 rounded-xl bg-gray-50 flex items-center justify-center mb-3 sm:mb-4 relative overflow-hidden">
                            <div class="upload-preview" id="preview-{{ Str::slug($dok) }}">
                                @if($isUploaded)
                                    <div class="text-green-500 flex flex-col items-center">
                                        <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] sm:text-xs font-bold mt-2">Terupload</span>
                                    </div>
                                @else

                                    <template x-if="!isUploaded('{{ $dok }}')">
                                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </template>
                                    <template x-if="isUploaded('{{ $dok }}')">
                                        <div class="text-green-500 flex flex-col items-center">
                                            <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="text-[10px] sm:text-xs font-bold mt-2 text-center">Baru Saja Diupload</span>
                                        </div>
                                    </template>
                                @endif
                            </div>
                            
                            <input type="file" 
                                   id="file-{{ Str::slug($dok) }}" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   @change="uploadFile($event, '{{ $dok }}', '{{ Str::slug($dok) }}')">
                        </div>

                        <h4 class="font-bold text-sm sm:text-base text-gray-800 mb-1">{{ $dok }}</h4>
                        
                        <p class="text-[10px] sm:text-xs mb-3 sm:mb-4 font-semibold" 
                           :class="isUploaded('{{ $dok }}') ? 'text-green-600' : 'text-red-500'"
                           id="status-{{ Str::slug($dok) }}">
                           <span x-text="isUploaded('{{ $dok }}') ? 'Siap dikirim' : 'Wajib diupload (PDF/JPG)'"></span>
                        </p>

                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2 hidden" id="progress-bar-{{ Str::slug($dok) }}">
                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 0%"></div>
                        </div>

                        <button class="w-full py-2 sm:py-2.5 rounded-lg text-[11px] sm:text-xs font-bold border transition"
                                :class="isUploaded('{{ $dok }}') ? 'border-green-200 text-green-700 bg-green-50' : 'border-gray-200 hover:bg-gray-50'"
                                onclick="document.getElementById('file-{{ Str::slug($dok) }}').click()">
                            <span x-text="isUploaded('{{ $dok }}') ? 'Ganti File' : 'Pilih File'"></span>
                        </button>
                    </div>
                @endforeach
            </div>


            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center mt-8 sm:mt-10 pb-10 sm:pb-12 gap-3 sm:gap-4">
                
                <a href="{{ route('formulir.edit') }}" class=" w-full px-5 sm:px-6 py-3 mb-4 sm:py-4 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali Edit Data
                </a>    

                <form action="{{ route('formulir.finish', $pendaftar->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" 
                            :disabled="!checkCompletion()"
                            class="w-full px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-white shadow-lg transition flex items-center justify-center transform text-sm sm:text-base"
                            :class="checkCompletion() 
                                ? 'bg-gray-900 hover:bg-black hover:-translate-y-1 cursor-pointer' 
                                : 'bg-gray-300 cursor-not-allowed opacity-75 shadow-none'">
                        
                        <span>Selesaikan Pendaftaran</span>
                        

                        <svg x-show="!checkCompletion()" class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        
                        <svg x-show="checkCompletion()" class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                    
                    <p x-show="!checkCompletion()" class="text-[10px] sm:text-xs text-red-500 mt-2 text-center sm:text-right font-bold">
                        *Harap upload semua dokumen wajib
                    </p>
                </form>
            </div>

        </div>
    </div>


    <script>
        function uploadHandler(requiredDocs, initialUploads) {
            return {
             
                required: requiredDocs,
                
                uploaded: initialUploads,

                isUploaded(docName) {
                    return this.uploaded.includes(docName);
                },

                checkCompletion() {
                    return this.required.every(doc => this.uploaded.includes(doc));
                },

                async uploadFile(event, jenisDokumen, slug) {
                    let file = event.target.files[0];
                    if (!file) return;

                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('jenis_dokumen', jenisDokumen);
                    
                    let progressBar = document.getElementById(`progress-bar-${slug}`);
                    let statusText = document.getElementById(`status-${slug}`);
                    let card = document.getElementById(`card-${slug}`);
                    
                    progressBar.classList.remove('hidden');
                    statusText.innerText = 'Mengupload...';
                    statusText.className = 'text-[10px] sm:text-xs mb-3 sm:mb-4 font-bold text-yellow-600';

                    try {
                        let response = await fetch("{{ route('formulir.store_upload', $pendaftar->id) }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });

                        if (response.ok) {
                            statusText.innerText = 'Berhasil diupload!';
                            statusText.className = 'text-[10px] sm:text-xs mb-3 sm:mb-4 font-bold text-green-600';
                            
                            if (!this.uploaded.includes(jenisDokumen)) {
                                this.uploaded.push(jenisDokumen);
                            }

                        } else {
                            throw new Error('Gagal');
                        }
                    } catch (error) {
                        statusText.innerText = 'Gagal upload. Coba lagi.';
                        statusText.className = 'text-[10px] sm:text-xs mb-3 sm:mb-4 font-bold text-red-600';
                    } finally {
                        progressBar.classList.add('hidden');
                    }
                }
            }
        }
    </script>
</x-student-layout>