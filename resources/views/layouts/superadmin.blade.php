<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Superadmin LMS TPQ</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6f7;
        }
        .arabic-text {
            font-family: 'Amiri', serif;
        }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">
    <!-- Mobile Header/Navbar -->
    <div class="md:hidden bg-emerald-950 text-white px-4 py-3.5 flex items-center justify-between shadow-md z-30">
        <div class="flex items-center space-x-3">
            @if(!empty($appSettings['logo_tpq']) && $appSettings['logo_tpq'] !== '/images/logo-default.png')
                <img src="{{ $appSettings['logo_tpq'] }}" alt="Logo" class="w-8 h-8 rounded-full object-cover">
            @else
                <span class="w-8 h-8 rounded-full bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-mosque"></i></span>
            @endif
            <div>
                <span class="text-white font-bold text-xs tracking-wide block leading-none">{{ $appSettings['nama_tpq'] }}</span>
                <span class="text-[9px] text-amber-400 font-semibold uppercase tracking-wider block mt-1">Panel Superadmin</span>
            </div>
        </div>
        <button type="button" @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-emerald-100 hover:bg-emerald-800 focus:outline-none transition">
            <i class="fa-solid" :class="sidebarOpen ? 'fa-xmark text-lg' : 'fa-bars text-base'"></i>
        </button>
    </div>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-black/60 md:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-emerald-900 text-emerald-100 flex flex-col shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <!-- Sidebar Brand -->
        <div class="p-6 bg-emerald-950 flex items-center justify-between border-b border-emerald-800">
            <div class="flex items-center space-x-3">
                @if(!empty($appSettings['logo_tpq']) && $appSettings['logo_tpq'] !== '/images/logo-default.png')
                    <img src="{{ $appSettings['logo_tpq'] }}" alt="Logo" class="w-8 h-8 rounded-full object-cover">
                @else
                    <span class="w-8 h-8 rounded-full bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-mosque"></i></span>
                @endif
                <div>
                    <h2 class="text-white font-bold text-sm tracking-wide leading-none">{{ $appSettings['nama_tpq'] }}</h2>
                    <span class="text-xs text-amber-400 font-medium">Panel Superadmin</span>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button type="button" @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg text-emerald-300 hover:text-white hover:bg-emerald-800 focus:outline-none transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                <i class="fa-solid fa-chart-line w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('superadmin.admins.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('superadmin.admins.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                <i class="fa-solid fa-user-tie w-5"></i>
                <span>Kelola Pengurus</span>
            </a>
            <a href="{{ route('superadmin.settings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('superadmin.settings') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                <i class="fa-solid fa-sliders w-5"></i>
                <span>Pengaturan Sistem</span>
            </a>
            
            <div class="pt-4 border-t border-emerald-800 mt-4 space-y-1">
                <span class="px-4 text-[10px] font-bold text-emerald-300 uppercase block tracking-wider mb-2">CMS Landing Page</span>
                
                <a href="{{ route('admin.landing.pendaftaran.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('admin.landing.pendaftaran.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                    <i class="fa-solid fa-id-card-clip w-5"></i>
                    <span class="text-sm">Pendaftaran PSB</span>
                </a>
                <a href="{{ route('admin.landing.galeri.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('admin.landing.galeri.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                    <i class="fa-solid fa-images w-5"></i>
                    <span class="text-sm">Galeri Foto</span>
                </a>
                <a href="{{ route('admin.landing.testimoni.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('admin.landing.testimoni.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                    <i class="fa-solid fa-comments w-5"></i>
                    <span class="text-sm">Testimoni Wali</span>
                </a>
                <a href="{{ route('admin.landing.pengurus.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('admin.landing.pengurus.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                    <i class="fa-solid fa-users-gear w-5"></i>
                    <span class="text-sm">Struktur Pengurus</span>
                </a>
                <a href="{{ route('admin.landing.pengaturan.edit') }}" class="flex items-center space-x-3 px-4 py-2 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('admin.landing.pengaturan.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
                    <i class="fa-solid fa-sliders w-5"></i>
                    <span class="text-sm">Pengaturan Landing</span>
                </a>
            </div>

            <div class="pt-4 border-t border-emerald-800 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-emerald-800 transition duration-200 text-amber-300">
                    <i class="fa-solid fa-toolbox w-5"></i>
                    <span>Masuk Panel Pengurus</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar User Footer -->
        <div class="p-4 bg-emerald-950 border-t border-emerald-800 flex items-center justify-between">
            <div class="flex items-center space-x-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-emerald-800 text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr(auth()->guard('admin')->user()->nama, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-bold truncate leading-none mb-1">{{ auth()->guard('admin')->user()->nama }}</p>
                    <span class="text-[10px] text-emerald-300 font-medium truncate block">Superadmin</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Keluar Aplikasi" class="p-2 text-emerald-300 hover:text-white rounded-lg hover:bg-emerald-800 transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shadow-sm z-10">
            <div class="flex items-center space-x-4">
                <h1 class="text-lg font-bold text-gray-800">@yield('page_title', 'LMS TPQ')</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                    Tahun Ajaran: <strong class="font-bold">{{ $appSettings['tahun_ajaran'] }}</strong>
                </span>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="flex-1 p-6 overflow-y-auto">
            <!-- Toast Notifications -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-sm text-sm text-emerald-800 flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm text-sm text-rose-800 flex items-center space-x-3">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl shadow-sm text-sm text-amber-800 flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-amber-500 text-lg"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @livewireScripts
</body>
</html>
