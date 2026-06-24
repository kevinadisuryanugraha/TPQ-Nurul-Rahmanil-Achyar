<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title') - {{ $appSettings['nama_tpq'] }}</title>
    <meta name="description" content="@yield('meta_description', $appSettings['deskripsi_tpq'])">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title') - {{ $appSettings['nama_tpq'] }}">
    <meta property="og:description" content="@yield('meta_description', $appSettings['deskripsi_tpq'])">
    <meta property="og:image" content="{{ asset($appSettings['logo_tpq']) }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title') - {{ $appSettings['nama_tpq'] }}">
    <meta property="twitter:description" content="@yield('meta_description', $appSettings['deskripsi_tpq'])">
    <meta property="twitter:image" content="{{ asset($appSettings['logo_tpq']) }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Manifest & Icons for PWA -->
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    <meta name="theme-color" content="#064e3b">

    <!-- CSS & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fcfdfc;
        }
        .arabic-text {
            font-family: 'Amiri', serif;
        }
        .gradient-brand {
            background: linear-gradient(135deg, #064e3b 0%, #022c22 100%);
        }
        .text-brand-gold {
            color: #d97706;
        }
        .bg-brand-gold {
            background-color: #d97706;
        }
        .border-brand-gold {
            border-color: #d97706;
        }
        .bg-brand-gold-hover:hover {
            background-color: #b45309;
        }
    </style>
    @yield('styles')
</head>
<body class="text-gray-800 antialiased bg-stone-50/20" x-data="{ mobileMenuOpen: false }">

    <!-- Sticky Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-emerald-50/80 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Brand Name -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-3">
                        @if(!empty($appSettings['logo_tpq']) && $appSettings['logo_tpq'] !== '/images/logo-default.png')
                            <img src="{{ $appSettings['logo_tpq'] }}" alt="Logo" class="w-10 h-10 rounded-full object-cover shadow-sm border border-emerald-100">
                        @else
                            <span class="w-10 h-10 rounded-full bg-emerald-800 text-amber-400 flex items-center justify-center font-bold text-xl shadow-sm"><i class="fa-solid fa-mosque"></i></span>
                        @endif
                        <div>
                            <span class="text-lg font-extrabold text-emerald-900 tracking-wide block leading-none">{{ $appSettings['nama_tpq'] }}</span>
                            <span class="text-[10px] text-amber-600 font-semibold tracking-wider uppercase block mt-1">Taman Pendidikan Al-Qur'an</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ request()->routeIs('landing') ? '#beranda' : route('landing') . '#beranda' }}" class="text-sm font-semibold text-emerald-950 hover:text-emerald-700 transition">Beranda</a>
                    <a href="{{ request()->routeIs('landing') ? '#tentang-kami' : route('landing') . '#tentang-kami' }}" class="text-sm font-semibold text-emerald-950 hover:text-emerald-700 transition">Tentang Kami</a>
                    <a href="{{ request()->routeIs('landing') ? '#program' : route('landing') . '#program' }}" class="text-sm font-semibold text-emerald-950 hover:text-emerald-700 transition">Program</a>
                    <a href="{{ request()->routeIs('landing') ? '#galeri' : route('landing') . '#galeri' }}" class="text-sm font-semibold text-emerald-950 hover:text-emerald-700 transition">Galeri</a>
                    <a href="{{ request()->routeIs('landing') ? '#testimoni' : route('landing') . '#testimoni' }}" class="text-sm font-semibold text-emerald-950 hover:text-emerald-700 transition">Testimoni</a>
                    <a href="{{ request()->routeIs('landing') ? '#kontak' : route('landing') . '#kontak' }}" class="text-sm font-semibold text-emerald-950 hover:text-emerald-700 transition">Kontak</a>
                </nav>

                <!-- Desktop CTA Buttons -->
                <div class="hidden md:flex items-center space-x-3">
                    @auth('admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-bold rounded-full hover:bg-emerald-100 transition shadow-sm">
                            <i class="fa-solid fa-toolbox mr-1.5"></i> Dashboard Admin
                        </a>
                    @elseauth('web')
                        <a href="{{ route('murid.dashboard') }}" class="px-5 py-2.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-bold rounded-full hover:bg-emerald-100 transition shadow-sm">
                            <i class="fa-solid fa-graduation-cap mr-1.5"></i> Dashboard Santri
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-bold rounded-full hover:bg-emerald-100 transition shadow-sm">
                            <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Login
                        </a>
                    @endauth
                    
                    <a href="{{ route('daftar.create') }}" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-sm font-bold rounded-full transition shadow-md hover:shadow-lg active:scale-95">
                        Daftar Sekarang <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Mobile Hamburger Toggle -->
                <div class="flex items-center md:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-lg text-emerald-900 hover:bg-emerald-50 focus:outline-none transition">
                        <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-2xl' : 'fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Side Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden border-t border-emerald-50 bg-white shadow-xl py-4 absolute top-20 left-0 right-0 z-40">
            <div class="px-4 space-y-2">
                <a href="{{ request()->routeIs('landing') ? '#beranda' : route('landing') . '#beranda' }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-sm font-semibold rounded-xl text-emerald-950 hover:bg-emerald-50">Beranda</a>
                <a href="{{ request()->routeIs('landing') ? '#tentang-kami' : route('landing') . '#tentang-kami' }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-sm font-semibold rounded-xl text-emerald-950 hover:bg-emerald-50">Tentang Kami</a>
                <a href="{{ request()->routeIs('landing') ? '#program' : route('landing') . '#program' }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-sm font-semibold rounded-xl text-emerald-950 hover:bg-emerald-50">Program Belajar</a>
                <a href="{{ request()->routeIs('landing') ? '#galeri' : route('landing') . '#galeri' }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-sm font-semibold rounded-xl text-emerald-950 hover:bg-emerald-50">Galeri</a>
                <a href="{{ request()->routeIs('landing') ? '#testimoni' : route('landing') . '#testimoni' }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-sm font-semibold rounded-xl text-emerald-950 hover:bg-emerald-50">Testimoni</a>
                <a href="{{ request()->routeIs('landing') ? '#kontak' : route('landing') . '#kontak' }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-sm font-semibold rounded-xl text-emerald-950 hover:bg-emerald-50">Kontak</a>
                
                <div class="pt-4 border-t border-emerald-50 flex flex-col space-y-2.5">
                    @auth('admin')
                        <a href="{{ route('admin.dashboard') }}" class="w-full py-2.5 bg-emerald-50 text-emerald-800 text-center text-sm font-bold rounded-xl hover:bg-emerald-100 transition">
                            <i class="fa-solid fa-toolbox mr-1.5"></i> Dashboard Admin
                        </a>
                    @elseauth('web')
                        <a href="{{ route('murid.dashboard') }}" class="w-full py-2.5 bg-emerald-50 text-emerald-800 text-center text-sm font-bold rounded-xl hover:bg-emerald-100 transition">
                            <i class="fa-solid fa-graduation-cap mr-1.5"></i> Dashboard Santri
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full py-2.5 bg-emerald-50 text-emerald-800 text-center text-sm font-bold rounded-xl hover:bg-emerald-100 transition">
                            <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Login Portal
                        </a>
                    @endauth
                    
                    <a href="{{ route('daftar.create') }}" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-center text-sm font-bold rounded-xl transition">
                        Daftar Murid Baru
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Sections -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-emerald-950 text-emerald-100/90 pt-16 pb-8 border-t border-emerald-900 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Branding Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        @if(!empty($appSettings['logo_tpq']) && $appSettings['logo_tpq'] !== '/images/logo-default.png')
                            <img src="{{ $appSettings['logo_tpq'] }}" alt="Logo" class="w-10 h-10 rounded-full object-cover border border-emerald-800">
                        @else
                            <span class="w-10 h-10 rounded-full bg-emerald-800 text-amber-400 flex items-center justify-center font-bold text-xl"><i class="fa-solid fa-mosque"></i></span>
                        @endif
                        <span class="text-xl font-extrabold text-white tracking-wide">{{ $appSettings['nama_tpq'] }}</span>
                    </div>
                    <p class="text-xs text-emerald-200/70 max-w-sm leading-relaxed">
                        {{ $appSettings['deskripsi_tpq'] }}
                    </p>
                    <div class="flex items-center space-x-4 pt-2">
                        @php 
                            $insta = \App\Models\LandingSetting::getValue('instagram_url');
                            $fb = \App\Models\LandingSetting::getValue('facebook_url');
                            $email = \App\Models\LandingSetting::getValue('email');
                        @endphp
                        @if($insta)
                            <a href="{{ $insta }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-900/60 hover:bg-amber-500 hover:text-emerald-950 flex items-center justify-center text-sm transition">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif
                        @if($fb)
                            <a href="{{ $fb }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-900/60 hover:bg-amber-500 hover:text-emerald-950 flex items-center justify-center text-sm transition">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                        @endif
                        @if($email)
                            <a href="mailto:{{ $email }}" class="w-8 h-8 rounded-full bg-emerald-900/60 hover:bg-amber-500 hover:text-emerald-950 flex items-center justify-center text-sm transition">
                                <i class="fa-solid fa-envelope"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Navigation Quick links -->
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">Tautan Cepat</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#beranda" class="hover:text-amber-400 transition">Beranda</a></li>
                        <li><a href="#tentang-kami" class="hover:text-amber-400 transition">Tentang Kami</a></li>
                        <li><a href="#program" class="hover:text-amber-400 transition">Program Belajar</a></li>
                        <li><a href="#galeri" class="hover:text-amber-400 transition">Galeri Kegiatan</a></li>
                        <li><a href="#testimoni" class="hover:text-amber-400 transition">Testimoni Wali</a></li>
                    </ul>
                </div>

                <!-- Contacts info -->
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">Kontak & Alamat</h4>
                    <p class="text-xs text-emerald-200/70 leading-relaxed">
                        <i class="fa-solid fa-map-location-dot text-amber-500 mr-1.5"></i>
                        {{ \App\Models\LandingSetting::getValue('alamat') }}
                    </p>
                    <p class="text-xs text-emerald-200/70">
                        <i class="fa-solid fa-clock text-amber-500 mr-1.5"></i>
                        {{ \App\Models\LandingSetting::getValue('jam_operasional') }}
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-xs text-amber-400 hover:text-amber-300 font-bold transition">
                            Login Portal LMS <i class="fa-solid fa-arrow-right-to-bracket ml-1.5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer copyright -->
            <div class="border-t border-emerald-900/60 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between text-[11px] text-emerald-200/50">
                <p>&copy; {{ date('Y') }} {{ $appSettings['nama_tpq'] }}. All rights reserved.</p>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <span>Tahun Ajaran: {{ $appSettings['tahun_ajaran'] }}</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => console.log('Service Worker registered successfully on landing page:', reg.scope))
                    .catch((err) => console.log('Service Worker registration failed:', err));
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
