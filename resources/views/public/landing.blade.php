@extends('layouts.public')

@section('title', 'Membentuk Generasi Qur\'ani Sejak Dini')

@section('content')
<div x-data="galleryData">

    {{-- ═══════════════════════════════════════════════════════════
         HERO SECTION (#beranda)
         Signature: Islamic geometric SVG + Basmallah Arabic calligraphy
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="beranda" class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-950 to-teal-950 text-white min-h-[100dvh] flex items-center pt-16 pb-20 md:pb-28">

        {{-- Islamic geometric pattern overlay --}}
        <div class="absolute inset-0 z-0 pattern-islamic"></div>

        {{-- Ambient glow orbs --}}
        <div class="absolute right-0 top-0 w-[600px] h-[600px] bg-amber-400/8 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-[500px] h-[500px] bg-emerald-400/8 rounded-full blur-[120px] pointer-events-none"></div>

        {{-- Arabic ornament top-center --}}
        <div class="absolute top-6 left-1/2 -translate-x-1/2 pointer-events-none opacity-20">
            <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 12 Q30 0 60 12 Q90 24 120 12" stroke="#d97706" stroke-width="1.5" fill="none"/>
                <circle cx="60" cy="12" r="3" fill="#d97706"/>
                <circle cx="20" cy="8" r="1.5" fill="#d97706"/>
                <circle cx="100" cy="8" r="1.5" fill="#d97706"/>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                {{-- Hero Texts --}}
                <div class="lg:col-span-7 space-y-7 text-center lg:text-left">

                    {{-- Basmallah eyebrow (1 eyebrow total for landing, highly intentional) --}}
                    <div class="flex items-center justify-center lg:justify-start gap-3">
                        <div class="h-px w-8 bg-amber-400/40"></div>
                        <span class="arabic-text text-amber-300 text-lg tracking-wide">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</span>
                        <div class="h-px w-8 bg-amber-400/40"></div>
                    </div>

                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight leading-[1.1] text-white drop-shadow-sm">
                        {{ $landingSettings['hero_headline'] ?? 'Membentuk Generasi Qur\'ani Sejak Dini' }}
                    </h1>

                    <p class="text-base sm:text-lg text-emerald-100/75 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        {{ $landingSettings['hero_subheadline'] ?? 'Belajar membaca, menulis, dan menghafal Al-Qur\'an secara interaktif dan menyenangkan.' }}
                    </p>

                    {{-- Stats Badges (animated via CSS countUp) --}}
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-1">
                        <div class="stat-badge px-5 py-3.5 rounded-2xl bg-white/8 border border-white/12 text-center backdrop-blur-sm">
                            <span class="block text-2xl font-bold text-amber-400">4</span>
                            <span class="text-[11px] text-emerald-200/60 uppercase font-semibold tracking-wider">Domain Belajar</span>
                        </div>
                        <div class="stat-badge px-5 py-3.5 rounded-2xl bg-white/8 border border-white/12 text-center backdrop-blur-sm">
                            <span class="block text-2xl font-bold text-amber-400">20+</span>
                            <span class="text-[11px] text-emerald-200/60 uppercase font-semibold tracking-wider">Santri Aktif</span>
                        </div>
                        <div class="stat-badge px-5 py-3.5 rounded-2xl bg-white/8 border border-white/12 text-center backdrop-blur-sm">
                            <span class="block text-2xl font-bold text-amber-400">Online</span>
                            <span class="text-[11px] text-emerald-200/60 uppercase font-semibold tracking-wider">Progress Hub</span>
                        </div>
                    </div>

                    {{-- Hero CTAs --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('daftar.create') }}"
                           class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-extrabold rounded-full transition-all duration-200 shadow-lg shadow-amber-600/30 hover:shadow-xl hover:shadow-amber-500/40 hover:-translate-y-0.5 active:translate-y-0 text-center text-sm tracking-wide cursor-pointer">
                            Daftarkan Anak Sekarang <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>

                        @php
                            $waClean   = $landingSettings['no_wa'];
                            $waMessage = urlencode("Assalamu'alaikum, saya ingin bertanya mengenai pendaftaran dan program belajar di TPQ.");
                            $waLink    = "https://wa.me/{$waClean}?text={$waMessage}";
                        @endphp
                        <a href="{{ $waLink }}" target="_blank"
                           class="w-full sm:w-auto px-8 py-4 bg-emerald-800/80 hover:bg-emerald-700 border border-emerald-600/60 text-emerald-100 hover:text-white font-bold rounded-full transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 text-center text-sm tracking-wide cursor-pointer backdrop-blur-sm">
                            <i class="fa-brands fa-whatsapp text-lg mr-2 align-middle text-emerald-400"></i> Tanya via WhatsApp
                        </a>
                    </div>
                </div>

                {{-- Hero Visual: Islamic Geometric Frame --}}
                <div class="lg:col-span-5 flex justify-center mt-4 lg:mt-0">
                    <div class="relative">
                        {{-- Outer decorative ring --}}
                        <div class="absolute inset-0 rounded-full border-2 border-amber-400/20 scale-110 animate-spin" style="animation-duration: 30s;"></div>
                        <div class="absolute inset-0 rounded-full border border-emerald-600/30 scale-125"></div>

                        {{-- Main circle visual --}}
                        <div class="relative w-72 h-72 sm:w-80 sm:h-80 md:w-96 md:h-96 rounded-full overflow-hidden border-4 border-emerald-800/50 shadow-2xl shadow-emerald-950/80 bg-emerald-950">

                            {{-- Islamic geometric SVG inside the circle --}}
                            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient id="heroGrad" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%"   stop-color="#065f46" stop-opacity="0.8"/>
                                        <stop offset="100%" stop-color="#022c22" stop-opacity="1"/>
                                    </radialGradient>
                                    <pattern id="heroPattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                                        <polygon points="40,5 75,25 75,55 40,75 5,55 5,25" fill="none" stroke="#d97706" stroke-width="0.8" opacity="0.25"/>
                                        <polygon points="40,15 65,30 65,50 40,65 15,50 15,30" fill="none" stroke="#34d399" stroke-width="0.5" opacity="0.15"/>
                                        <circle cx="40" cy="40" r="3" fill="#d97706" opacity="0.3"/>
                                    </pattern>
                                </defs>
                                <rect width="400" height="400" fill="url(#heroGrad)"/>
                                <rect width="400" height="400" fill="url(#heroPattern)"/>
                            </svg>

                            {{-- Content overlay --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 z-10">
                                <div class="w-20 h-20 rounded-full bg-amber-400/15 border border-amber-400/30 flex items-center justify-center mb-5">
                                    <i class="fa-solid fa-book-open-reader text-4xl text-amber-400"></i>
                                </div>
                                <span class="arabic-text text-2xl text-amber-300 font-bold block mb-2">اِقْرَأْ</span>
                                <span class="text-xs text-emerald-300/70 uppercase tracking-[0.18em] font-semibold">Iqra — Bacalah</span>
                                <div class="mt-4 h-px w-16 bg-amber-400/30"></div>
                                <span class="text-[11px] text-emerald-100/50 mt-3 tracking-widest uppercase">{{ $appSettings['nama_tpq'] }}</span>
                            </div>
                        </div>

                        {{-- Small accent dots --}}
                        <div class="absolute -top-2 -right-2 w-4 h-4 rounded-full bg-amber-400/60"></div>
                        <div class="absolute -bottom-4 -left-4 w-6 h-6 rounded-full bg-emerald-600/40 border border-emerald-500/30"></div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0 overflow-hidden pointer-events-none">
            <svg class="w-full h-16 text-white" viewBox="0 0 1440 64" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,37.3C1120,32,1280,32,1360,32L1440,32L1440,64L0,64Z" fill="currentColor" fill-opacity="0.03"/>
            </svg>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         TENTANG KAMI (#tentang-kami)
         Layout: Split — visual card kiri, teks kanan
         (No eyebrow — h2 berdiri sendiri)
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="tentang-kami" class="py-20 md:py-28 bg-white pattern-islamic-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                {{-- Visual Profile Card --}}
                <div class="lg:col-span-5 reveal">
                    <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 rounded-3xl p-8 text-white shadow-2xl shadow-emerald-900/30 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-32 h-32 pattern-islamic opacity-30"></div>

                        <div class="arabic-text text-6xl text-amber-400/25 leading-none mb-5 select-none">"</div>
                        <p class="text-base font-medium leading-relaxed italic text-emerald-100/90 relative z-10">
                            "Membaca Al-Qur'an adalah pelita hati, penenang jiwa, dan bekal hidup terbaik anak kita di dunia dan akhirat."
                        </p>
                        <div class="border-t border-emerald-700/50 mt-7 pt-5 flex items-center space-x-3 relative z-10">
                            <span class="w-11 h-11 rounded-full bg-amber-500/90 text-emerald-950 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                <i class="fa-solid fa-mosque"></i>
                            </span>
                            <div>
                                <span class="text-sm font-bold block text-white">Lembaga Pendidikan Al-Qur'an</span>
                                <span class="text-xs text-amber-400 font-semibold uppercase tracking-wider">{{ $appSettings['nama_tpq'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile Texts --}}
                <div class="lg:col-span-7 space-y-6 reveal reveal-delay-1">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 tracking-tight leading-tight">
                            Mengenal Lebih Dekat<br>
                            <span class="text-emerald-700">{{ $appSettings['nama_tpq'] }}</span>
                        </h2>
                    </div>

                    <p class="text-base text-gray-600 leading-relaxed max-w-[65ch]">
                        {{ $landingSettings['tentang_kami'] ?? 'TPQ kami berkomitmen memberikan pengajaran Quran terbaik bagi putra-putri Anda.' }}
                    </p>

                    {{-- Visi Misi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                        {{-- Visi --}}
                        <div class="p-6 bg-emerald-50/60 rounded-2xl border border-emerald-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center mb-4 text-base">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <h3 class="text-sm font-bold text-emerald-950 mb-2">Visi Kami</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ $landingSettings['visi'] ?? 'Mewujudkan generasi qurani yang cinta Quran dan berakhlak mulia.' }}
                            </p>
                        </div>

                        {{-- Misi --}}
                        <div class="p-6 bg-amber-50/60 rounded-2xl border border-amber-100">
                            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center mb-4 text-base">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <h3 class="text-sm font-bold text-emerald-950 mb-2">Misi Kami</h3>
                            <ul class="space-y-1.5 text-sm text-gray-500">
                                @if(!empty($landingSettings['misi']) && is_array($landingSettings['misi']))
                                    @foreach($landingSettings['misi'] as $misi)
                                        <li class="flex items-start gap-2">
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-1 flex-shrink-0"></i>
                                            <span>{{ $misi }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="flex items-start gap-2">
                                        <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-1"></i>
                                        <span>Menyelenggarakan KBM Terstruktur.</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         PROGRAM / KURIKULUM (#program)
         Layout: Centered header + Bento-variant 4-card grid
         Cards: 2 gradient emerald, 2 putih — variasi visual
         (No eyebrow)
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="program" class="py-20 md:py-28 bg-emerald-950 relative overflow-hidden">
        <div class="absolute inset-0 pattern-islamic opacity-60"></div>
        <div class="absolute right-0 top-0 w-96 h-96 bg-amber-400/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                    4 Pilar Domain Pembelajaran
                </h2>
                <p class="text-base text-emerald-200/60 leading-relaxed">
                    Metode belajar terintegrasi yang dirancang khusus untuk memandu tumbuh kembang spiritual anak.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Card 1 — Gradient emerald --}}
                <div class="reveal p-7 bg-gradient-to-br from-emerald-800 to-emerald-900 rounded-3xl border border-emerald-700/40 group hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-13 h-13 w-12 h-12 rounded-2xl bg-white/10 text-emerald-200 flex items-center justify-center mb-6 text-xl group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-white mb-3">Baca &amp; Tulis</h3>
                    <p class="text-sm text-emerald-200/70 leading-relaxed">
                        Pembelajaran bertahap metode Iqra hingga kelancaran membaca Al-Qur'an secara tartil dan kaidah penulisan huruf hijaiyah.
                    </p>
                </div>

                {{-- Card 2 — White surface --}}
                <div class="reveal reveal-delay-1 p-7 bg-white rounded-3xl border border-gray-100 group hover:shadow-lg hover:border-amber-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 text-xl group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <h3 class="text-base font-bold text-emerald-950 mb-3">Hafalan (Tahfidz)</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Setoran hafalan surat pendek (Juz 30), doa sehari-hari, serta hadist pendek untuk membekali ingatan rohani anak.
                    </p>
                </div>

                {{-- Card 3 — White surface --}}
                <div class="reveal reveal-delay-2 p-7 bg-white rounded-3xl border border-gray-100 group hover:shadow-lg hover:border-emerald-100 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-6 text-xl group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-hands-praying"></i>
                    </div>
                    <h3 class="text-base font-bold text-emerald-950 mb-3">Praktik Ibadah</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Bimbingan langsung tata cara berwudhu dan gerakan sholat wajib maupun sunnah sesuai sunnah Nabi Muhammad SAW.
                    </p>
                </div>

                {{-- Card 4 — Gradient amber --}}
                <div class="reveal reveal-delay-3 p-7 bg-gradient-to-br from-amber-600 to-amber-700 rounded-3xl border border-amber-500/40 group hover:from-amber-500 hover:to-amber-600 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-white/15 text-amber-100 flex items-center justify-center mb-6 text-xl group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-scroll"></i>
                    </div>
                    <h3 class="text-base font-bold text-white mb-3">Halaqah &amp; Kisah</h3>
                    <p class="text-sm text-amber-100/75 leading-relaxed">
                        Kajian akhlak ringan, kisah Nabi, sahabat, dan cerita moral islami untuk mendidik mentalitas saleh santri.
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         KEUNGGULAN (#keunggulan)
         Layout: Full-width light section (pattern break dari dark)
         (No eyebrow — sudah cukup konteks dari posisi halaman)
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="keunggulan" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section header — split layout berbeda dari section lain --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-end mb-14">
                <div class="lg:col-span-6 reveal">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 tracking-tight leading-tight">
                        Mengapa Memilih<br>
                        <span class="text-amber-600">TPQ Kami?</span>
                    </h2>
                </div>
                <div class="lg:col-span-6 reveal reveal-delay-1">
                    <p class="text-base text-gray-500 leading-relaxed">
                        Kami menggabungkan kenyamanan sistem pembelajaran modern dengan nilai tarbiyah islami yang orisinil dan terstruktur.
                    </p>
                </div>
            </div>

            {{-- Value Points Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @if(!empty($landingSettings['poin_keunggulan']) && is_array($landingSettings['poin_keunggulan']))
                    @foreach($landingSettings['poin_keunggulan'] as $index => $poin)
                        <div class="reveal reveal-delay-{{ ($index % 3) + 1 }} p-6 rounded-2xl border border-gray-100 flex items-start gap-5 hover:border-emerald-100 hover:bg-emerald-50/30 transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-emerald-950 mb-1.5">{{ $poin['title'] ?? 'Keunggulan' }}</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $poin['desc'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    @php
                        $fallbackPoin = [
                            ['icon' => 'fa-graduation-cap', 'title' => 'Kurikulum Terarah', 'desc' => 'Pengajaran berjenjang menyesuaikan daya serap kognitif anak.'],
                            ['icon' => 'fa-chalkboard-user', 'title' => 'Pengajar Berpengalaman', 'desc' => 'Ustadz dan Ustadzah berpengalaman dan sabar dalam membimbing santri.'],
                            ['icon' => 'fa-mobile-screen', 'title' => 'Pantau Progress Online', 'desc' => 'Orang tua dapat memantau perkembangan belajar anak kapan saja.'],
                            ['icon' => 'fa-heart-pulse', 'title' => 'Lingkungan Islami', 'desc' => 'Suasana belajar yang nyaman, islami, dan penuh kasih sayang.'],
                        ];
                    @endphp
                    @foreach($fallbackPoin as $i => $fp)
                        <div class="reveal reveal-delay-{{ ($i % 3) + 1 }} p-6 rounded-2xl border border-gray-100 flex items-start gap-5 hover:border-emerald-100 hover:bg-emerald-50/30 transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid {{ $fp['icon'] }}"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-emerald-950 mb-1.5">{{ $fp['title'] }}</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $fp['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         GALERI KEGIATAN (#galeri)
         Layout: Masonry-like grid, hover overlay smooth
         (No eyebrow)
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="galeri" class="py-20 md:py-28 bg-stone-50 pattern-islamic-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-xl mx-auto mb-14 reveal">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 tracking-tight mb-4">
                    Dokumentasi Aktivitas Santri
                </h2>
                <p class="text-base text-gray-500">Momen keceriaan, belajar, dan pencapaian rohani para santri kami.</p>
            </div>

            @if($galleries->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-300 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-regular fa-image"></i>
                    </div>
                    <p class="text-sm text-gray-400">Belum ada foto dokumentasi di galeri.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($galleries as $index => $gal)
                        <div class="reveal reveal-delay-{{ ($index % 4) + 1 }} relative rounded-2xl overflow-hidden aspect-[4/3] group shadow-sm border border-emerald-50/30 cursor-pointer"
                             @click="openLightbox({{ $index }})">
                            <img src="{{ $gal->gambar }}" alt="{{ $gal->judul ?? 'Foto Kegiatan' }}" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-emerald-950/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                <span class="text-[10px] text-amber-400 font-semibold uppercase tracking-wider mb-1">{{ $gal->kategori ?? 'Umum' }}</span>
                                <h4 class="text-sm font-bold truncate">{{ $gal->judul ?? 'Dokumentasi' }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         TESTIMONI (#testimoni)
         Layout: 3-col grid, quote cards premium
         (No eyebrow — posisi halaman cukup jelas)
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="testimoni" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-xl mx-auto mb-14 reveal">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 tracking-tight mb-4">
                    Kesan Orang Tua Wali
                </h2>
                <p class="text-base text-gray-500">Kata mereka yang telah mempercayakan tarbiyah Al-Qur'an anaknya kepada kami.</p>
            </div>

            @if($testimonials->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-gray-400">Belum ada testimoni terpilih.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($testimonials as $i => $test)
                        <div class="reveal reveal-delay-{{ ($i % 3) + 1 }} p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-300 relative flex flex-col justify-between group">

                            {{-- Quote mark --}}
                            <div class="text-4xl text-emerald-100 mb-4 leading-none font-serif">&ldquo;</div>

                            <div class="flex-1 space-y-3">
                                {{-- Star Rating --}}
                                <div class="flex items-center gap-0.5 text-amber-500">
                                    @for($s = 1; $s <= min(($test->rating ?? 5), 5); $s++)
                                        <i class="fa-solid fa-star text-xs"></i>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed italic">
                                    {{ Str::limit($test->isi, 180) }}
                                </p>
                            </div>

                            <div class="border-t border-gray-100 mt-6 pt-4 flex items-center gap-3">
                                @if($test->foto)
                                    <img src="{{ $test->foto }}" alt="{{ $test->nama }}" class="w-10 h-10 rounded-full object-cover border-2 border-emerald-50 flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($test->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold text-emerald-950">{{ $test->nama }}</h4>
                                    <span class="text-xs text-gray-400 font-medium block mt-0.5">{{ $test->role }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         PENGURUS (#pengurus)
         Layout: Centered grid — berbeda dari section lain
    ═══════════════════════════════════════════════════════════════ --}}
    @if(!$pengurusList->isEmpty())
    <section id="pengurus" class="py-20 md:py-28 bg-emerald-950 relative overflow-hidden">
        <div class="absolute inset-0 pattern-islamic opacity-50"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-amber-400/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-xl mx-auto mb-14 reveal">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                    Ustadz &amp; Ustadzah Pengajar
                </h2>
                <p class="text-base text-emerald-200/60">Bimbingan tulus untuk perkembangan akhlak dan kelancaran mengaji santri.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                @foreach($pengurusList as $i => $peng)
                    <div class="reveal reveal-delay-{{ ($i % 3) + 1 }} bg-white/5 border border-white/10 rounded-3xl overflow-hidden text-center p-7 flex flex-col items-center hover:bg-white/8 hover:border-amber-400/30 transition-all duration-300 group">
                        <div class="mb-5">
                            @if($peng->foto)
                                <img src="{{ $peng->foto }}" alt="{{ $peng->nama }}" class="w-24 h-24 rounded-full object-cover border-4 border-emerald-800 shadow-lg group-hover:border-amber-500/50 transition-all">
                            @else
                                <div class="w-24 h-24 rounded-full bg-emerald-800 text-white flex items-center justify-center font-extrabold text-2xl border-4 border-emerald-700 group-hover:border-amber-500/50 transition-all">
                                    {{ strtoupper(substr($peng->nama, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-white truncate max-w-full">{{ $peng->nama }}</h3>
                        <span class="text-xs text-amber-400 font-semibold block mt-1.5 tracking-wider uppercase">{{ $peng->jabatan }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ═══════════════════════════════════════════════════════════
         KONTAK (#kontak)
         Layout: Split — info kiri, maps kanan
         (No eyebrow)
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="kontak" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- Contact Details --}}
                <div class="lg:col-span-5 space-y-7 reveal">
                    <div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 tracking-tight mb-3">
                            Hubungi TPQ Kami
                        </h2>
                        <p class="text-base text-gray-500 leading-relaxed">
                            Punya pertanyaan seputar kurikulum, pendaftaran, atau jadwal kegiatan? Hubungi kami langsung.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center flex-shrink-0 text-base">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-emerald-950 mb-1">Alamat Lengkap</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $landingSettings['alamat'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 text-base">
                                <i class="fa-solid fa-clock"></i>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-emerald-950 mb-1">Jam Operasional Pendaftaran</h4>
                                <p class="text-sm text-gray-500">{{ $landingSettings['jam_operasional'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- CTA buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="https://wa.me/{{ $landingSettings['no_wa'] }}?text={{ urlencode('Assalamu\'alaikum, mohon info pendaftaran santri baru.') }}" target="_blank"
                           class="px-6 py-3.5 rounded-2xl bg-emerald-800 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition-all duration-200 flex items-center justify-center gap-2 hover:-translate-y-0.5 cursor-pointer">
                            <i class="fa-brands fa-whatsapp text-base text-emerald-400"></i> WhatsApp Resmi
                        </a>
                        <a href="tel:{{ $landingSettings['no_telpon'] }}"
                           class="px-6 py-3.5 rounded-2xl bg-stone-100 hover:bg-stone-200 text-emerald-950 text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-phone text-xs"></i> Hubungi Langsung
                        </a>
                    </div>

                    {{-- Daftar CTA --}}
                    <div class="pt-2 border-t border-gray-100">
                        <a href="{{ route('daftar.create') }}"
                           class="inline-flex items-center gap-2 px-7 py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-bold rounded-2xl shadow-md shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 text-sm cursor-pointer">
                            <i class="fa-solid fa-user-plus"></i>
                            Daftarkan Anak Sekarang
                        </a>
                    </div>
                </div>

                {{-- Google Maps --}}
                <div class="lg:col-span-7 reveal reveal-delay-1">
                    <div class="rounded-3xl overflow-hidden shadow-md border border-gray-100 h-80 sm:h-[420px]">
                        @if(!empty($landingSettings['maps_embed_url']))
                            <iframe src="{{ $landingSettings['maps_embed_url'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi {{ $appSettings['nama_tpq'] }}"></iframe>
                        @else
                            <div class="w-full h-full bg-stone-100 flex flex-col items-center justify-center text-center p-8 text-gray-400">
                                <i class="fa-solid fa-map-marked-alt text-4xl mb-3"></i>
                                <span class="text-sm">Iframe Google Maps belum dikonfigurasi.</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════
         ALPINE.JS LIGHTBOX OVERLAY
    ═══════════════════════════════════════════════════════════════ --}}
    <div x-show="lightboxOpen"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-emerald-950/95 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
         style="display: none;"
         @keydown.escape.window="lightboxOpen = false">

        <button type="button" @click="lightboxOpen = false"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-emerald-200 hover:text-white flex items-center justify-center text-lg focus:outline-none focus:ring-2 focus:ring-white/30 transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="relative max-w-4xl w-full max-h-[85vh] flex flex-col items-center select-none"
             @click.outside="lightboxOpen = false">
            <div class="relative w-full flex items-center justify-center">
                <button type="button" @click.stop="prev()"
                        class="absolute left-2 sm:left-4 p-3 rounded-full bg-black/40 text-emerald-100 hover:text-white hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-white/30 transition">
                    <i class="fa-solid fa-chevron-left text-sm sm:text-base"></i>
                </button>
                <img :src="activeImg" :alt="activeTitle" class="max-w-full max-h-[75vh] object-contain rounded-2xl shadow-2xl">
                <button type="button" @click.stop="next()"
                        class="absolute right-2 sm:right-4 p-3 rounded-full bg-black/40 text-emerald-100 hover:text-white hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-white/30 transition">
                    <i class="fa-solid fa-chevron-right text-sm sm:text-base"></i>
                </button>
            </div>
            <div class="mt-4 text-center text-emerald-100 max-w-md px-4">
                <p class="text-sm font-bold truncate" x-text="activeTitle || 'Dokumentasi'"></p>
                <span class="text-xs text-amber-400 font-semibold mt-1 block" x-text="`${activeIndex + 1} / ${images.length}`"></span>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('galleryData', () => ({
            lightboxOpen: false,
            activeIndex: 0,
            images: @json($galleries->map(fn($g) => ['src' => $g->gambar, 'title' => $g->judul ?? 'Dokumentasi'])->values()),

            get activeImg()   { return this.images[this.activeIndex]?.src ?? ''; },
            get activeTitle() { return this.images[this.activeIndex]?.title ?? ''; },

            openLightbox(index) {
                this.activeIndex = index;
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },
            next() {
                this.activeIndex = (this.activeIndex + 1) % this.images.length;
            },
            prev() {
                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
            },
            closeLightbox() {
                this.lightboxOpen = false;
                document.body.style.overflow = '';
            },
        }));
    });
</script>

{{-- Watch lightboxOpen to restore scroll --}}
<script>
    document.addEventListener('alpine:init', () => {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') document.body.style.overflow = '';
        });
    });
</script>
@endsection
