@extends('layouts.public')

@section('title', 'Membentuk Generasi Qur\'ani Sejak Dini')

@section('content')
<div x-data="galleryData">
    
    <!-- HERO SECTION (#beranda) -->
    <section id="beranda" class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-950 to-teal-950 text-white pt-24 pb-20 md:py-32">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 z-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        <!-- Light gold glow -->
        <div class="absolute right-0 top-0 w-[500px] h-[500px] bg-amber-400/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-[400px] h-[400px] bg-emerald-400/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Hero Texts -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-amber-400/10 text-amber-400 border border-amber-400/25 tracking-wider uppercase mb-2">
                        🌟 Taman Pendidikan Al-Qur'an (TPQ)
                    </span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white drop-shadow-sm">
                        {{ $landingSettings['hero_headline'] ?? 'Membentuk Generasi Qur\'ani Sejak Dini' }}
                    </h1>
                    <p class="text-base sm:text-lg text-emerald-100/80 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                        {{ $landingSettings['hero_subheadline'] ?? 'Belajar membaca, menulis, dan menghafal Al-Qur\'an secara interaktif dan menyenangkan.' }}
                    </p>
                    
                    <!-- Stats Badges -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                        <div class="px-5 py-3 rounded-2xl bg-white/5 border border-white/10 text-center">
                            <span class="block text-2xl font-bold text-amber-400">4</span>
                            <span class="text-[10px] text-emerald-200/60 uppercase font-semibold tracking-wider">Domain Belajar</span>
                        </div>
                        <div class="px-5 py-3 rounded-2xl bg-white/5 border border-white/10 text-center">
                            <span class="block text-2xl font-bold text-amber-400">20+</span>
                            <span class="text-[10px] text-emerald-200/60 uppercase font-semibold tracking-wider">Santri Aktif</span>
                        </div>
                        <div class="px-5 py-3 rounded-2xl bg-white/5 border border-white/10 text-center">
                            <span class="block text-2xl font-bold text-amber-400">Online</span>
                            <span class="text-[10px] text-emerald-200/60 uppercase font-semibold tracking-wider">Progress Hub</span>
                        </div>
                    </div>

                    <!-- Hero CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                        <a href="{{ route('daftar.create') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold rounded-full transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 text-center text-sm tracking-wide">
                            Daftarkan Anak Sekarang <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                        
                        @php
                            $waClean = $landingSettings['no_wa'];
                            $waMessage = urlencode("Assalamu'alaikum, saya ingin bertanya mengenai pendaftaran dan program belajar di TPQ.");
                            $waLink = "https://wa.me/{$waClean}?text={$waMessage}";
                        @endphp
                        <a href="{{ $waLink }}" target="_blank" class="w-full sm:w-auto px-8 py-4 bg-emerald-800 hover:bg-emerald-700 border border-emerald-600/80 text-emerald-100 hover:text-white font-bold rounded-full transition hover:-translate-y-0.5 active:translate-y-0 text-center text-sm tracking-wide">
                            <i class="fa-brands fa-whatsapp text-lg mr-2 align-middle text-emerald-400"></i> Tanya via WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Hero Graphic Frame (Mosque or Islamic vector fallback) -->
                <div class="lg:col-span-5 relative mt-8 lg:mt-0 flex justify-center">
                    <div class="relative w-72 h-72 sm:w-80 sm:h-80 md:w-96 md:h-96 rounded-full overflow-hidden border-4 border-emerald-800/60 shadow-2xl bg-emerald-900 flex items-center justify-center group">
                        <!-- Fallback Visual -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-950 to-emerald-800 flex flex-col items-center justify-center text-center p-8">
                            <i class="fa-solid fa-book-open-reader text-6xl md:text-8xl text-amber-400/80 mb-4 animate-pulse"></i>
                            <span class="text-xl md:text-2xl font-bold tracking-wide text-white uppercase font-sans">Generasi Qur'ani</span>
                            <span class="text-xs text-amber-500 font-semibold tracking-widest mt-1 block">AL-ISTIQOMAH</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0 h-12 overflow-hidden pointer-events-none">
            <svg class="absolute bottom-0 w-full h-12 text-stone-50/20" viewBox="0 0 1440 74" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,32L120,42.7C240,53,480,75,720,74.7C960,75,1200,53,1320,42.7L1440,32L1440,74L1320,74C1200,74,960,74,720,74C480,74,240,74,120,74L0,74Z" fill="currentColor"></path>
            </svg>
        </div>
    </section>

    <!-- TENTANG KAMI SECTION (#tentang-kami) -->
    <section id="tentang-kami" class="py-20 md:py-28 bg-stone-50/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Visual Profile Card -->
                <div class="lg:col-span-5">
                    <div class="bg-gradient-to-br from-emerald-800 to-emerald-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full blur-2xl"></div>
                        <i class="fa-solid fa-quote-left text-5xl text-amber-400/20 mb-6 block"></i>
                        <p class="text-sm font-medium leading-relaxed italic text-emerald-100">
                            "Membaca Al-Qur'an adalah pelita hati, penenang jiwa, dan bekal hidup terbaik anak kita di dunia dan akhirat."
                        </p>
                        <div class="border-t border-emerald-700/60 mt-6 pt-4 flex items-center space-x-3">
                            <span class="w-10 h-10 rounded-full bg-amber-500 text-emerald-950 flex items-center justify-center font-bold text-sm"><i class="fa-solid fa-mosque"></i></span>
                            <div>
                                <span class="text-xs font-bold block text-white">Lembaga Pendidikan Al-Qur'an</span>
                                <span class="text-[10px] text-amber-400 font-semibold uppercase tracking-wider">{{ $appSettings['nama_tpq'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Texts -->
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block mb-2">💡 Profil Lembaga</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">
                            Mengenal Lebih Dekat {{ $appSettings['nama_tpq'] }}
                        </h2>
                    </div>
                    
                    <p class="text-sm text-gray-600 leading-relaxed font-light">
                        {{ $landingSettings['tentang_kami'] ?? 'TPQ kami berkomitmen memberikan pengajaran Quran terbaik bagi putra-putri Anda.' }}
                    </p>

                    <!-- Visi Misi Container -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <!-- Visi Card -->
                        <div class="p-6 bg-white rounded-2xl border border-emerald-50 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-4 text-lg">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <h3 class="text-sm font-bold text-emerald-950 mb-2">Visi Kami</h3>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                {{ $landingSettings['visi'] ?? 'Mewujudkan generasi qurani yang cinta Quran dan berakhlak mulia.' }}
                            </p>
                        </div>
                        
                        <!-- Misi Card -->
                        <div class="p-6 bg-white rounded-2xl border border-emerald-50 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 text-lg">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <h3 class="text-sm font-bold text-emerald-950 mb-2">Misi Kami</h3>
                            <ul class="space-y-1.5 text-xs text-gray-500">
                                @if(!empty($landingSettings['misi']) && is_array($landingSettings['misi']))
                                    @foreach($landingSettings['misi'] as $misi)
                                        <li class="flex items-start">
                                            <i class="fa-solid fa-circle-check text-emerald-600 text-[10px] mt-1 mr-1.5 flex-shrink-0"></i>
                                            <span>{{ $misi }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-circle-check text-emerald-600 text-[10px] mt-1 mr-1.5"></i>
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

    <!-- PROGRAM / KURIKULUM SECTION (#program) -->
    <section id="program" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block">📖 Kurikulum Belajar</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">4 Pilar Domain Pembelajaran</h2>
                <p class="text-xs text-gray-500">Metode belajar terintegrasi yang dirancang khusus untuk memandu tumbuh kembang spiritual anak.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Program Card 1 -->
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-emerald-100 transition group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-6 text-2xl group-hover:scale-105 transition-all">
                        📖
                    </div>
                    <h3 class="text-base font-bold text-emerald-950 mb-3">Baca & Tulis</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-light">
                        Pembelajaran bertahap metode Iqra hingga kelancaran membaca Al-Qur'an secara tartil, serta kaidah penulisan huruf hijaiyah.
                    </p>
                </div>

                <!-- Program Card 2 -->
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-emerald-100 transition group">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 text-2xl group-hover:scale-105 transition-all">
                        🧠
                    </div>
                    <h3 class="text-base font-bold text-emerald-950 mb-3">Hafalan (Tahfidz)</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-light">
                        Setoran hafalan surat-surat pendek (Juz 30), doa sehari-hari, serta hadist-hadist pendek untuk membekali ingatan rohani anak.
                    </p>
                </div>

                <!-- Program Card 3 -->
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-emerald-100 transition group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-6 text-2xl group-hover:scale-105 transition-all">
                        🤲
                    </div>
                    <h3 class="text-base font-bold text-emerald-950 mb-3">Praktik Ibadah</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-light">
                        Bimbingan langsung tata cara berwudhu dan gerakan sholat wajib maupun sunnah secara benar sesuai sunnah Nabi Muhammad SAW.
                    </p>
                </div>

                <!-- Program Card 4 -->
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md hover:border-emerald-100 transition group">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 text-2xl group-hover:scale-105 transition-all">
                        📚
                    </div>
                    <h3 class="text-base font-bold text-emerald-950 mb-3">Halaqah & Kisah</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-light">
                        Kajian akhlak ringan, pembacaan kisah Nabi, sahabat, dan cerita moral islami untuk mendidik mentalitas saleh santri.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- KEUNGGULAN SECTION (#keunggulan) -->
    <section id="keunggulan" class="py-20 md:py-28 bg-emerald-950 text-white relative overflow-hidden">
        <div class="absolute right-0 bottom-0 w-[400px] h-[400px] bg-amber-400/5 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Content Left -->
                <div class="lg:col-span-5 space-y-4 text-center lg:text-left">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-widest block">🌟 Value Proposition</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Mengapa Memilih TPQ Kami?</h2>
                    <p class="text-xs text-emerald-200/70 leading-relaxed font-light">
                        Kami menggabungkan kenyamanan sistem pembelajaran modern dengan nilai tarbiyah islami orisinil.
                    </p>
                </div>

                <!-- Repeatable Value Items Right -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if(!empty($landingSettings['poin_keunggulan']) && is_array($landingSettings['poin_keunggulan']))
                        @foreach($landingSettings['poin_keunggulan'] as $poin)
                            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 flex items-start space-x-4">
                                <span class="text-amber-400 text-xl mt-1"><i class="fa-solid fa-circle-check"></i></span>
                                <div>
                                    <h3 class="text-sm font-bold text-white mb-1.5">{{ $poin['title'] ?? 'Keunggulan' }}</h3>
                                    <p class="text-[11px] text-emerald-100/70 leading-relaxed font-light">{{ $poin['desc'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback points -->
                        <div class="p-6 rounded-2xl bg-white/5 border border-white/10 flex items-start space-x-4">
                            <span class="text-amber-400 text-xl mt-1"><i class="fa-solid fa-circle-check"></i></span>
                            <div>
                                    <h3 class="text-sm font-bold text-white mb-1.5">Kurikulum Terarah</h3>
                                    <p class="text-[11px] text-emerald-100/70 leading-relaxed">Pengajaran berjenjang menyesuaikan daya serap kognitif anak.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- GALERI KEGIATAN SECTION (#galeri) -->
    <section id="galeri" class="py-20 md:py-28 bg-stone-50/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block">📸 Galeri Kegiatan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">Dokumentasi Aktivitas Santri</h2>
                <p class="text-xs text-gray-500">Momen keceriaan, belajar, dan pencapaian rohani para santri kami.</p>
            </div>

            <!-- Galleries Grid -->
            @if($galleries->isEmpty())
                <div class="text-center py-12 bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                    <p class="text-xs text-gray-400"><i class="fa-solid fa-image text-3xl mb-3 block"></i> Belum ada foto dokumentasi di galeri.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($galleries as $index => $gal)
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] group shadow-sm border border-emerald-50/30 cursor-pointer"
                             @click="openLightbox({{ $index }})">
                            <!-- Image -->
                            <img src="{{ $gal->gambar }}" alt="{{ $gal->judul ?? 'Foto Kegiatan' }}" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300">
                            <!-- Overlay on hover -->
                            <div class="absolute inset-0 bg-emerald-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4 text-white">
                                <span class="text-[10px] text-amber-400 font-semibold uppercase tracking-wider mb-1">{{ $gal->kategori ?? 'Umum' }}</span>
                                <h4 class="text-xs font-bold truncate">{{ $gal->judul ?? 'Dokumentasi' }}</h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- TESTIMONI SECTION (#testimoni) -->
    <section id="testimoni" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block">💬 Testimoni</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">Kesan Orang Tua Wali</h2>
                <p class="text-xs text-gray-500">Kata mereka yang telah mempercayakan tarbiyah Al-Qur'an anaknya kepada kami.</p>
            </div>

            <!-- Testimonial Grid -->
            @if($testimonials->isEmpty())
                <div class="text-center py-8">
                    <p class="text-xs text-gray-400">Belum ada testimoni terpilih.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($testimonials as $test)
                        <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition relative flex flex-col justify-between">
                            <i class="fa-solid fa-quote-left text-3xl text-emerald-100/60 mb-4 block"></i>
                            <div class="flex-1 space-y-3">
                                <!-- Star Rating -->
                                <div class="flex items-center space-x-0.5 text-amber-500">
                                    @for($i = 1; $i <= ($test->rating ?? 5); $i++)
                                        <i class="fa-solid fa-star text-xs"></i>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-600 leading-relaxed font-light">
                                    "{{ $test->isi }}"
                                </p>
                            </div>
                            
                            <div class="border-t border-gray-100 mt-6 pt-4 flex items-center space-x-3.5">
                                @if($test->foto)
                                    <img src="{{ $test->foto }}" alt="{{ $test->nama }}" class="w-10 h-10 rounded-full object-cover border border-emerald-50">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($test->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-xs font-bold text-emerald-950">{{ $test->nama }}</h4>
                                    <span class="text-[9px] text-gray-400 font-semibold block mt-0.5">{{ $test->role }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- STRUKTUR PENGURUS SECTION (#pengurus) -->
    @if(!$pengurusList->isEmpty())
    <section id="pengurus" class="py-20 md:py-28 bg-stone-50/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block">🕌 Struktur Pengurus</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">Ustadz & Ustadzah Pengajar</h2>
                <p class="text-xs text-gray-500">Bimbingan tulus untuk perkembangan akhlak dan kelancaran mengaji santri.</p>
            </div>

            <!-- Team profiles grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                @foreach($pengurusList as $peng)
                    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition text-center p-6 flex flex-col items-center">
                        <div class="mb-4">
                            @if($peng->foto)
                                <img src="{{ $peng->foto }}" alt="{{ $peng->nama }}" class="w-24 h-24 rounded-full object-cover border-2 border-emerald-50 shadow-inner">
                            @else
                                <div class="w-24 h-24 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-2xl shadow-sm border border-emerald-100">
                                    {{ strtoupper(substr($peng->nama, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-sm font-bold text-emerald-950 truncate max-w-full">{{ $peng->nama }}</h3>
                        <span class="text-[10px] text-amber-600 font-semibold block mt-1 tracking-wider uppercase">{{ $peng->jabatan }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- LOKASI & KONTAK SECTION (#kontak) -->
    <section id="kontak" class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Contact Details Left -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block mb-2">📞 Informasi Kontak</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">Hubungi TPQ Kami</h2>
                        <p class="text-xs text-gray-500 mt-2 font-light">Punya pertanyaan seputar kurikulum, pendaftaran santri baru, atau jadwal kegiatan? Hubungi kami langsung.</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Alamat -->
                        <div class="flex items-start space-x-4">
                            <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-map-location-dot"></i></span>
                            <div>
                                <h4 class="text-xs font-bold text-emerald-950">Alamat Lengkap</h4>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $landingSettings['alamat'] }}</p>
                            </div>
                        </div>

                        <!-- Jam Operasional -->
                        <div class="flex items-start space-x-4">
                            <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-clock"></i></span>
                            <div>
                                <h4 class="text-xs font-bold text-emerald-950">Jam Operasional Pendaftaran</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $landingSettings['jam_operasional'] }}</p>
                            </div>
                        </div>

                        <!-- Kontak Cepat WA & Telpon -->
                        <div class="pt-4 flex flex-col sm:flex-row gap-3">
                            <a href="https://wa.me/{{ $landingSettings['no_wa'] }}?text={{ urlencode('Assalamu\'alaikum, mohon info pendaftaran santri baru.') }}" target="_blank"
                               class="px-5 py-3 rounded-2xl bg-emerald-800 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center">
                                <i class="fa-brands fa-whatsapp text-base mr-2"></i> WhatsApp Resmi
                            </a>
                            <a href="tel:{{ $landingSettings['no_telpon'] }}"
                               class="px-5 py-3 rounded-2xl bg-stone-100 hover:bg-stone-200 text-emerald-950 text-xs font-bold transition flex items-center justify-center">
                                <i class="fa-solid fa-phone text-xs mr-2"></i> Hubungi Langsung
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed Right -->
                <div class="lg:col-span-7 rounded-3xl overflow-hidden shadow-sm border border-gray-100 h-80 sm:h-96">
                    @if(!empty($landingSettings['maps_embed_url']))
                        <iframe src="{{ $landingSettings['maps_embed_url'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @else
                        <div class="w-full h-full bg-stone-100 flex flex-col items-center justify-center text-center p-8 text-gray-400">
                            <i class="fa-solid fa-map-marked-alt text-4xl mb-2"></i>
                            <span class="text-xs">Iframe Google Maps belum dikonfigurasi.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ALPINE.JS LIGHTBOX OVERLAY -->
    <div x-show="lightboxOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-emerald-950/95 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6" 
         style="display: none;"
         @keydown.escape.window="lightboxOpen = false">
        
        <!-- Close Button -->
        <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 text-emerald-200 hover:text-white p-2 text-xl focus:outline-none transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Main Lightbox Container -->
        <div class="relative max-w-4xl w-full max-h-[85vh] flex flex-col items-center select-none" @click.outside="lightboxOpen = false">
            
            <!-- Image Panel -->
            <div class="relative w-full flex items-center justify-center">
                
                <!-- Left Nav Arrow -->
                <button type="button" @click.stop="prev()" class="absolute left-2 sm:left-4 p-3 rounded-full bg-black/40 text-emerald-100 hover:text-white hover:bg-black/60 focus:outline-none transition">
                    <i class="fa-solid fa-chevron-left text-sm sm:text-base"></i>
                </button>

                <!-- Current Image -->
                <img :src="activeImg" :alt="activeTitle" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl">

                <!-- Right Nav Arrow -->
                <button type="button" @click.stop="next()" class="absolute right-2 sm:right-4 p-3 rounded-full bg-black/40 text-emerald-100 hover:text-white hover:bg-black/60 focus:outline-none transition">
                    <i class="fa-solid fa-chevron-right text-sm sm:text-base"></i>
                </button>
            </div>

            <!-- Image Title Footer -->
            <div class="mt-4 text-center text-emerald-100 max-w-md px-4">
                <p class="text-sm font-bold truncate" x-text="activeTitle || 'Dokumentasi'"></p>
                <span class="text-[10px] text-amber-400 font-semibold mt-1 block" x-text="`${activeIndex + 1} / ${images.length}`"></span>
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
            activeImg: '',
            activeTitle: '',
            activeIndex: 0,
            images: [
                @foreach($galleries as $gal)
                { src: '{{ $gal->gambar }}', title: '{{ $gal->judul ?? "Dokumentasi" }}' },
                @endforeach
            ],
            openLightbox(index) {
                this.activeIndex = index;
                this.activeImg = this.images[index].src;
                this.activeTitle = this.images[index].title;
                this.lightboxOpen = true;
            },
            next() {
                if (this.images.length === 0) return;
                this.activeIndex = (this.activeIndex + 1) % this.images.length;
                this.activeImg = this.images[this.activeIndex].src;
                this.activeTitle = this.images[this.activeIndex].title;
            },
            prev() {
                if (this.images.length === 0) return;
                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                this.activeImg = this.images[this.activeIndex].src;
                this.activeTitle = this.images[this.activeIndex].title;
            }
        }));
    });
</script>
@endsection
