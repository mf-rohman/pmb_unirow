<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine JS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ mobileMenuOpen: false }">
    
    <div class="flex min-h-screen">
        
        <aside class="w-64 bg-white border-r border-gray-100 hidden md:flex flex-col fixed inset-y-0 z-50">
           {{-- ... (kode sidebar desktop sama seperti sebelumnya) ... --}}
           <div class="h-16 flex items-center px-8 border-b border-gray-50">
                <div class="flex items-center gap-2 text-indigo-600 font-bold text-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    PMB Unirow
                </div>
            </div>

            <div class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Overview</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </a>

                @php 
                    $user = Auth::user();
                    $pendaftar = $user->pendaftar;
                    $hasRegistered = $user && $user->pendaftar; 
                    $formRoute = $hasRegistered ? route('formulir.edit') : route('formulir.create');
                    $isActive = request()->routeIs('formulir.*');
                @endphp

                <a href="{{ $formRoute }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ $isActive ? 'bg-[#5D5FEF] text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-[#5D5FEF]' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="font-medium">{{ $hasRegistered ? 'Edit Formulir' : 'Isi Formulir' }}</span>
                </a>

                @if($hasRegistered && $pendaftar->status == 'lulus')
                    <a href="{{ route('cetak.kartu') }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-gray-500 hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Kartu Peserta
                    </a>
                @elseif($hasRegistered && $pendaftar->status == 'verifikasi_data')
                    <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-500 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Menunggu Verifikasi
                    </button>
                @else
                    <a href="{{ $formRoute }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl    transition-all duration-200 group {{ $isActive ? 'bg-[#5D5FEF] text-white shadow-lg    shadow-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-[#5D5FEF]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2    5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2    2 0 01-2 2z"/></svg>
                        <span class="font-medium">Isi Formulir !</span>
                    </a>
                @endif
                
                <div class="pt-4"></div>
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Settings</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>

             <div class="p-6 border-t border-gray-50">
                <div class="flex items-center gap-3">
                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e0e7ff&color=4f46e5" alt="{{ Auth::user()->name }}">
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-gray-900 truncate w-32">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate w-32">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile Menu Logic --}}
        <div x-show="mobileMenuOpen" class="relative z-50 md:hidden" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80"></div>

            <div class="fixed inset-0 flex">
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex w-full max-w-xs flex-1">
                    
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="mobileMenuOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                         <div class="flex h-16 shrink-0 items-center text-[#5D5FEF] font-bold text-xl gap-2 mt-4">
                            PMB App Mobile
                        </div>

                        <div class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Overview</p>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                Dashboard
                            </a>

                            @php 
                                $user = Auth::user();
                                $hasRegistered = $user && $user->pendaftar; 
                                $formRoute = $hasRegistered ? route('formulir.edit') : route('formulir.create');
                                $isActive = request()->routeIs('formulir.*');
                            @endphp

                            <a href="{{ $formRoute }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 group {{ $isActive ? 'bg-[#5D5FEF] text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-[#5D5FEF]' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="font-medium">{{ $hasRegistered ? 'Edit Formulir' : 'Isi Formulir' }}</span>
                            </a>

                            <div class="pt-4"></div>
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Settings</p>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>

                            @if($hasRegistered && $pendaftar->status == 'lulus')
                                <a href="{{ route('cetak.kartu') }}" target="_blank" class="w-full inline-flex          justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent           rounded-md font-semibold text-gray-500 hover:bg-indigo-700 transition">
                                    <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path           stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0           002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0         00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></         path></svg>
                                    Cetak Kartu Peserta
                                </a>
                            @elseif($hasRegistered && $pendaftar->status == 'verifikasi_data')
                                <button disabled class="w-full inline-flex justify-center items-center px-4 py-2            bg-gray-300 border border-transparent rounded-md font-semibold text-gray-500        cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24             24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12            15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4          0 00-8 0v4h8z"></path></svg>
                                    Menunggu Verifikasi
                                </button>
                            @else
                                <a href="{{ $formRoute }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl                transition-all duration-200 group {{ $isActive ? 'bg-[#5D5FEF] text-white shadow-lg             shadow-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-[#5D5FEF]' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6           4h6m2    5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.        293.707V19a2    2 0 01-2 2z"/></svg>
                                    <span class="font-medium">Isi Formulir !</span>
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <main class="flex-1 md:ml-64 p-6 md:p-10 overflow-x-hidden w-full">
            <div class="md:hidden mb-6 flex justify-between items-center">
                <span class="font-bold text-xl text-[#5D5FEF]">PMB App</span>
                <button type="button" @click="mobileMenuOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden bg-white rounded-lg shadow-sm border">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            {{-- Slot ini tempat Form berada --}}
            {{ $slot }}
            
        </main>
    </div>
</body>
</html>