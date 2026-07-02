@extends('layouts.murid')
@section('title', 'Pembelajaran Flashcard')
@section('content')
<style>
    @keyframes float-star {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50%       { transform: translateY(-7px) rotate(12deg); }
    }
    @keyframes pulse-badge {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,.5); }
        50%       { box-shadow: 0 0 0 7px rgba(245,158,11,0); }
    }
    .float-star { animation: float-star 3.2s ease-in-out infinite; }
    .float-star-slow { animation: float-star 4.5s ease-in-out infinite; animation-delay: 1.1s; }
    .badge-pulse { animation: pulse-badge 2s ease-in-out infinite; }
    .deck-card {
        transition: transform .2s cubic-bezier(.22,1,.36,1), box-shadow .2s ease;
    }
    .deck-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 12px 28px -8px rgba(0,0,0,.18);
    }
</style>

<div class="px-5 py-6 pb-28 space-y-5">

    {{-- ===== HERO HEADER ===== --}}
    <div class="relative rounded-3xl overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #065f46, #022c22); min-height:148px">

        {{-- Background dot grid --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:18px 18px;"></div>

        {{-- Decorative SVG bintang kanan atas --}}
        <div class="absolute top-4 right-5 float-star pointer-events-none">
            <svg width="44" height="44" viewBox="0 0 44 44" fill="none">
                <path d="M22 3L25.4 14.6H38.1L28.3 21.2L31.7 32.8L22 26.2L12.3 32.8L15.7 21.2L5.9 14.6H18.6L22 3Z" fill="#fbbf24"/>
            </svg>
        </div>
        <div class="absolute bottom-4 right-20 float-star-slow pointer-events-none opacity-60">
            <svg width="26" height="26" viewBox="0 0 26 26" fill="none">
                <path d="M13 2L15.4 8.9H23L17.1 13.1L19.5 20L13 15.8L6.5 20L8.9 13.1L3 8.9H10.6L13 2Z" fill="#fbbf24"/>
            </svg>
        </div>
        {{-- Crescent --}}
        <div class="absolute top-4 left-36 opacity-20 pointer-events-none">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="#fbbf24"/>
            </svg>
        </div>

        {{-- Content --}}
        <div class="relative z-10 p-6 pt-5">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-7 h-7 rounded-full bg-amber-400/25 flex items-center justify-center">
                    <i class="fa-solid fa-graduation-cap text-amber-300 text-sm"></i>
                </span>
                <span class="text-[11px] text-amber-300 font-bold uppercase tracking-widest">Latihan Hafalan</span>
            </div>
            <h1 class="text-[22px] font-extrabold text-white leading-tight mb-1.5">
                Yuk, Hafal<br><span class="text-amber-300">Bareng Sekarang!</span>
            </h1>
            <p class="text-xs text-emerald-100/75 leading-relaxed max-w-xs">
                Pilih dek kartu di bawah untuk mulai melatih hafalan dan ingatan kamu.
            </p>
        </div>
    </div>

    {{-- Subtitle row --}}
    <div class="flex items-center justify-between px-1">
        <p class="text-xs font-bold text-gray-500">
            <i class="fa-solid fa-layer-group text-emerald-500 mr-1"></i>
            {{ count($decks) }} Dek Belajar
        </p>
        <div class="flex items-center gap-1 text-[10px] text-emerald-700 font-bold bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
            <i class="fa-solid fa-fire text-amber-500"></i> Semangat belajar!
        </div>
    </div>

    {{-- ===== DECK CARDS ===== --}}
    @php
        $themes = [
            // bg-gradient-strip | card-bg | card-border | icon-bg | text | btn
            ['strip'=>'from-emerald-400 to-teal-500',   'bg'=>'bg-emerald-50', 'border'=>'border-emerald-200', 'icon'=>'bg-emerald-500',  'text'=>'text-emerald-900', 'btn'=>'bg-emerald-600 hover:bg-emerald-700'],
            ['strip'=>'from-amber-400  to-orange-400',  'bg'=>'bg-amber-50',   'border'=>'border-amber-200',   'icon'=>'bg-amber-500',    'text'=>'text-amber-900',  'btn'=>'bg-amber-500  hover:bg-amber-600'],
            ['strip'=>'from-sky-400    to-blue-500',    'bg'=>'bg-sky-50',     'border'=>'border-sky-200',     'icon'=>'bg-sky-500',      'text'=>'text-sky-900',    'btn'=>'bg-sky-600    hover:bg-sky-700'],
            ['strip'=>'from-rose-400   to-pink-500',    'bg'=>'bg-rose-50',    'border'=>'border-rose-200',    'icon'=>'bg-rose-500',     'text'=>'text-rose-900',   'btn'=>'bg-rose-500   hover:bg-rose-600'],
            ['strip'=>'from-violet-400 to-purple-500',  'bg'=>'bg-violet-50',  'border'=>'border-violet-200',  'icon'=>'bg-violet-500',   'text'=>'text-violet-900', 'btn'=>'bg-violet-600 hover:bg-violet-700'],
            ['strip'=>'from-teal-400   to-cyan-500',    'bg'=>'bg-teal-50',    'border'=>'border-teal-200',    'icon'=>'bg-teal-500',     'text'=>'text-teal-900',   'btn'=>'bg-teal-600   hover:bg-teal-700'],
        ];

        $sourceIcons = [
            'system_doa'    => 'fa-hands-praying',
            'system_hadist' => 'fa-book-open',
            'system_quran'  => 'fa-quran',
            'custom'        => 'fa-clone',
        ];

        $sourceLabels = [
            'system_doa'    => 'Doa Harian',
            'system_hadist' => 'Hadits Pilihan',
            'system_quran'  => 'Al-Quran',
            'custom'        => 'Materi Khusus',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4">
        @forelse($decks as $i => $deck)
            @php
                $t = $themes[$i % count($themes)];
                $icon = $sourceIcons[$deck->source_type] ?? 'fa-clone';
                $sourceLabel = $sourceLabels[$deck->source_type] ?? 'Materi';
                $isRecommended = $deck->level_target_id == $student->current_level_id;
            @endphp
            <a href="{{ route('murid.flashcard.show', $deck->id) }}"
               class="deck-card block {{ $t['bg'] }} border-2 {{ $t['border'] }} rounded-3xl overflow-hidden shadow-sm">

                {{-- Colored top strip --}}
                <div class="h-2 bg-gradient-to-r {{ $t['strip'] }}"></div>

                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        {{-- Icon --}}
                        <div class="w-13 h-13 rounded-2xl {{ $t['icon'] }} text-white flex items-center justify-center shadow-md" style="width:52px;height:52px">
                            <i class="fa-solid {{ $icon }} text-xl"></i>
                        </div>

                        {{-- Recommended / source badge --}}
                        <div class="flex flex-col items-end gap-1.5">
                            @if($isRecommended)
                                <span class="badge-pulse px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-400 text-white uppercase tracking-wide flex items-center gap-1 shadow-sm">
                                    <i class="fa-solid fa-star text-[9px]"></i> Untukmu
                                </span>
                            @endif
                            <span class="text-[10px] font-bold text-gray-400 bg-white/80 px-2 py-0.5 rounded-full border border-gray-100">
                                {{ $sourceLabel }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-extrabold {{ $t['text'] }} text-base leading-snug mb-1">{{ $deck->nama }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        {{ $deck->deskripsi ?? 'Latih hafalan dan perbanyak ingatan kamu!' }}
                    </p>

                    {{-- CTA Button --}}
                    <div class="{{ $t['btn'] }} text-white text-xs font-extrabold px-4 py-2.5 rounded-full inline-flex items-center gap-2 shadow transition-all">
                        <i class="fa-solid fa-play text-[10px]"></i> Mulai Belajar
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16 px-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <div class="w-20 h-20 mx-auto mb-4 bg-emerald-50 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-3xl text-emerald-300"></i>
                </div>
                <p class="font-bold text-gray-500 text-sm">Belum Ada Dek Belajar</p>
                <p class="text-xs text-gray-400 mt-1">Tunggu ustadz/ustadzah menambahkan materi ya!</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
