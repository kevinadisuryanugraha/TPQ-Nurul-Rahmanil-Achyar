<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#064e3b">
    <title>@yield('title') - LMS TPQ</title>
    <!-- Manifest & Icons for PWA -->
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    
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
            background-color: #f0f4f1;
            /* Prevent bounce scrolling on mobile iOS */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
        }
        .app-container {
            width: 100%;
            max-width: 480px; /* Mobile frame size on desktop */
            height: 100%;
            background-color: #f7faf8;
            box-shadow: 0 0 25px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: flex-col;
            position: relative;
            overflow: hidden;
        }
        .scrollable-content {
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 80px; /* Space for bottom tab bar */
        }
        .arabic-text {
            font-family: 'Amiri', serif;
        }
        /* Pull-to-refresh & custom scrollbar */
        .scrollable-content::-webkit-scrollbar {
            width: 4px;
        }
        .scrollable-content::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>
<body class="antialiased">
    <div class="app-container flex flex-col">
        <!-- Top App Header -->
        <header class="bg-emerald-900 text-white px-5 py-4 flex items-center justify-between shrink-0 shadow-md border-b border-emerald-950">
            <div class="flex items-center space-x-2.5">
                @if(!empty($appSettings['logo_tpq']) && $appSettings['logo_tpq'] !== '/images/logo-default.png')
                    <img src="{{ $appSettings['logo_tpq'] }}" alt="Logo" class="w-8 h-8 rounded-full object-cover">
                @else
                    <span class="w-8 h-8 rounded-full bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-sm"><i class="fa-solid fa-mosque"></i></span>
                @endif
                <div>
                    <h1 class="font-bold text-sm leading-none tracking-wide text-amber-300">
                        {{ $appSettings['nama_tpq'] ?? 'TPQ Al-Istiqomah' }}
                    </h1>
                    <span class="text-[9px] font-medium text-emerald-200">Portal Santri</span>
                </div>
            </div>

            <!-- Logout button -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('murid.pengumuman.index') }}" class="relative p-1.5 text-emerald-200 hover:text-white rounded-lg transition" title="Pengumuman">
                    <i class="fa-solid fa-bell text-lg"></i>
                    @php
                        // Check if there are active announcements for student
                        $activeAnnCount = \App\Models\Pengumuman::where('status', 'published')
                            ->where('tanggal_mulai', '<=', now())
                            ->where(function($q) {
                                $q->whereNull('tanggal_berakhir')->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->where(function($q) {
                                $q->where('target_semua', true)
                                  ->orWhere('level_target_id', auth()->user()->current_level_id);
                            })->count();
                    @endphp
                    @if($activeAnnCount > 0)
                        <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-amber-400 border border-emerald-900 rounded-full"></span>
                    @endif
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 text-emerald-200 hover:text-white rounded-lg transition" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="scrollable-content flex-1">
            <!-- Toast notification -->
            @if (session('success'))
                <div class="m-4 p-3 bg-green-50 border-l-4 border-green-500 rounded shadow-sm text-xs text-green-800 flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="m-4 p-3 bg-rose-50 border-l-4 border-rose-500 rounded shadow-sm text-xs text-rose-800 flex items-center space-x-2">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- PWA Install Banner -->
        <div id="pwa-install-banner" class="hidden absolute bottom-20 left-4 right-4 bg-emerald-950 text-white rounded-2xl p-4 shadow-xl border border-emerald-800 flex items-center justify-between z-40 transition duration-300">
            <div class="flex items-center space-x-3">
                <span class="w-10 h-10 rounded-xl bg-amber-400 text-emerald-950 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-mosque"></i></span>
                <div>
                    <h4 class="font-bold text-xs text-amber-300">Instal Aplikasi TPQ</h4>
                    <p class="text-[9px] text-emerald-200 leading-tight">Gunakan offline dan akses lebih cepat dari HP Anda!</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <button id="pwa-btn-dismiss" class="text-xs text-emerald-300 px-2 py-1">Nanti</button>
                <button id="pwa-btn-install" class="bg-amber-400 text-emerald-950 text-xs font-bold px-3 py-1.5 rounded-lg">Instal</button>
            </div>
        </div>

        <!-- Bottom Tab Navigation -->
        <nav class="absolute bottom-0 left-0 right-0 h-[64px] bg-white border-t border-gray-200 flex justify-around items-center z-30 shadow-lg">
            <!-- Home -->
            <a href="{{ route('murid.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('murid.dashboard') ? 'text-emerald-700 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <i class="fa-solid fa-house text-lg mb-1"></i>
                <span class="text-[9px]">Beranda</span>
            </a>
            
            <!-- Quran -->
            <a href="{{ route('murid.quran.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('murid.quran.*') ? 'text-emerald-700 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <i class="fa-solid fa-book-open text-lg mb-1"></i>
                <span class="text-[9px]">Al-Qur'an</span>
            </a>

            <!-- Doa / Hadist -->
            <a href="{{ route('murid.doa.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('murid.doa.*') || request()->routeIs('murid.hadist.*') || request()->routeIs('murid.cerita.*') || request()->routeIs('murid.panduan.*') || request()->routeIs('murid.asmaul-husna.*') ? 'text-emerald-700 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <i class="fa-solid fa-compass text-lg mb-1"></i>
                <span class="text-[9px]">Perpustakaan</span>
            </a>

            <!-- Latihan / Flashcards -->
            <a href="{{ route('murid.flashcard.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('murid.flashcard.*') ? 'text-emerald-700 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <i class="fa-solid fa-clone text-lg mb-1"></i>
                <span class="text-[9px]">Latihan</span>
            </a>

            <!-- Grades / Nilai -->
            <a href="{{ route('murid.nilai.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('murid.nilai.index') ? 'text-emerald-700 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <i class="fa-solid fa-star text-lg mb-1"></i>
                <span class="text-[9px]">Nilaiku</span>
            </a>

            <!-- Attendance -->
            <a href="{{ route('murid.absensi.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('murid.absensi.index') ? 'text-emerald-700 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <i class="fa-solid fa-calendar-check text-lg mb-1"></i>
                <span class="text-[9px]">Absensi</span>
            </a>
        </nav>
    </div>

    <!-- PWA Install Code -->
    <script>
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const btnInstall = document.getElementById('pwa-btn-install');
        const btnDismiss = document.getElementById('pwa-btn-dismiss');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to show the install banner
            if (localStorage.getItem('pwa-dismissed') !== 'true') {
                installBanner.classList.remove('hidden');
            }
        });

        btnInstall.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            // Show the prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response to install prompt: ${outcome}`);
            // We've used the prompt, and can't use it again, throw it away
            deferredPrompt = null;
            // Hide the banner
            installBanner.classList.add('hidden');
        });

        btnDismiss.addEventListener('click', () => {
            installBanner.classList.add('hidden');
            // Save preference to local storage
            localStorage.setItem('pwa-dismissed', 'true');
        });

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => console.log('Service Worker registered successfully:', reg.scope))
                    .catch((err) => console.log('Service Worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
