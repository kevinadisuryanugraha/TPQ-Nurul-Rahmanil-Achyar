@extends('layouts.murid')
@section('title', 'Latihan: ' . $deck->nama)
@section('content')
<style>
    [x-cloak] { display: none !important; }

    /* === 3D Flip Core === */
    .perspective-wrap { perspective: 1200px; }
    .preserve-3d      { transform-style: preserve-3d; }
    .backface-hidden  {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .rotated-back     { transform: rotateY(180deg); }

    /* === Card flip transition === */
    .flip-inner {
        transition: transform .52s cubic-bezier(.4,.2,.2,1);
    }

    /* === Card visibility fade when changing === */
    .card-visible   { opacity: 1;   transform: scale(1)    translateY(0);   transition: opacity .18s ease, transform .18s ease; }
    .card-invisible { opacity: 0;   transform: scale(.95)  translateY(8px); transition: opacity .18s ease, transform .18s ease; }

    /* === Animations === */
    @keyframes star-spin {
        0%   { transform: rotate(0deg)   scale(1); }
        50%  { transform: rotate(180deg) scale(1.25); }
        100% { transform: rotate(360deg) scale(1); }
    }
    @keyframes celebrate-pop {
        0%   { opacity:0; transform: scale(.5); }
        65%  {            transform: scale(1.08); }
        100% { opacity:1; transform: scale(1); }
    }
    @keyframes float-bob {
        0%,100% { transform: translateY(0);  }
        50%      { transform: translateY(-6px); }
    }
    @keyframes progress-grow {
        from { width: 0; }
    }

    .star-spin     { animation: star-spin   1.6s ease-in-out infinite; }
    .float-bob     { animation: float-bob   2.8s ease-in-out infinite; }
    .celebrate-pop { animation: celebrate-pop .48s cubic-bezier(.22,1,.36,1) forwards; }
</style>

<div class="px-5 py-6 pb-28 space-y-6" x-data="flashcardSession()">

    {{-- ===== TOP NAV ===== --}}
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('murid.flashcard.index') }}"
           class="flex items-center gap-1.5 text-xs font-bold text-emerald-800 bg-emerald-50 px-3.5 py-2 rounded-full border border-emerald-100 hover:bg-emerald-100 transition">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> Kembali
        </a>

        <div class="flex items-center gap-1.5">
            {{-- Card counter --}}
            <span x-show="!completed"
                  class="text-xs font-extrabold text-white bg-emerald-600 px-3.5 py-1.5 rounded-full shadow-sm">
                Kartu <span x-text="currentIndex + 1"></span>/<span x-text="cards.length"></span>
            </span>
        </div>
    </div>

    {{-- Deck label --}}
    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Sedang Belajar</p>
    <h2 class="text-base font-extrabold text-gray-900 mb-4">{{ $deck->nama }}</h2>

    {{-- ===== PROGRESS (dots ≤ 12, bar > 12) ===== --}}
    <div class="mb-5" x-show="!completed">
        {{-- Dot progress --}}
        <div class="flex gap-1.5 flex-wrap" x-show="cards.length <= 12">
            <template x-for="(c, i) in cards" :key="i">
                <div class="h-2 rounded-full transition-all duration-400"
                     :class="{
                         'w-7 bg-emerald-500': i === currentIndex,
                         'w-2 bg-emerald-200': i < currentIndex,
                         'w-2 bg-gray-200':    i > currentIndex
                     }"></div>
            </template>
        </div>

        {{-- Bar progress (many cards) --}}
        <div x-show="cards.length > 12">
            <div class="flex items-center gap-2">
                <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full transition-all duration-500"
                         :style="`width:${Math.round(((currentIndex+1)/cards.length)*100)}%`"
                         style="animation: progress-grow .4s ease"></div>
                </div>
                <span class="text-[10px] font-bold text-gray-400 w-8 text-right"
                      x-text="`${Math.round(((currentIndex+1)/cards.length)*100)}%`"></span>
            </div>
        </div>
    </div>

    {{-- ===== PLAY AREA ===== --}}
    <div x-show="!completed">

        {{-- 3-D FLIP CARD --}}
        <div class="perspective-wrap mb-5 cursor-pointer select-none"
             style="height: 310px;"
             @click="flipped = !flipped"
             :class="{'card-visible':visible,'card-invisible':!visible}">

            <div class="flip-inner preserve-3d relative w-full h-full"
                 :class="{'rotated-back':flipped}">

                {{-- ======= FRONT FACE ======= --}}
                <div class="backface-hidden absolute inset-0 bg-white rounded-3xl shadow-lg border-2 border-emerald-100 flex flex-col overflow-hidden">

                    {{-- Gradient top strip --}}
                    <div class="h-2 bg-gradient-to-r from-emerald-400 to-teal-400 shrink-0"></div>

                    {{-- Corner geometric ornament --}}
                    <div class="absolute top-4 right-4 opacity-[.07] pointer-events-none">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <circle cx="24" cy="24" r="22" stroke="#065f46" stroke-width="2.5"/>
                            <path d="M24 8 L24 40 M8 24 L40 24" stroke="#065f46" stroke-width="1.8"/>
                            <path d="M13 13 L35 35 M35 13 L13 35" stroke="#065f46" stroke-width="1"/>
                        </svg>
                    </div>

                    {{-- Label --}}
                    <div class="flex items-center gap-1.5 px-5 pt-4 pb-1">
                        <span class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-question text-amber-500 text-[11px]"></i>
                        </span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Soal / Arti</span>
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 flex items-center justify-center px-6 py-3">
                        <p class="text-[15px] font-extrabold text-emerald-950 text-center leading-relaxed whitespace-pre-line"
                           x-text="cards[currentIndex].front"></p>
                    </div>

                    {{-- Flip hint --}}
                    <div class="flex items-center justify-center gap-1.5 py-3 border-t border-dashed border-emerald-100 mx-5 mb-3 shrink-0">
                        <i class="fa-solid fa-rotate text-emerald-500 text-[11px]"></i>
                        <span class="text-[10px] text-gray-400 font-semibold">Klik kartu untuk membalik</span>
                    </div>
                </div>

                {{-- ======= BACK FACE ======= --}}
                <div class="backface-hidden rotated-back absolute inset-0 bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-900 rounded-3xl shadow-lg flex flex-col overflow-hidden">

                    {{-- Dot pattern bg --}}
                    <div class="absolute inset-0 opacity-[.06]"
                         style="background-image:radial-gradient(circle,#fff 1.5px,transparent 1.5px);background-size:18px 18px;"></div>

                    {{-- Decorative circles --}}
                    <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

                    {{-- Label row --}}
                    <div class="flex items-center justify-between px-5 pt-4 pb-1 relative z-10">
                        <div class="flex items-center gap-1.5">
                            <span class="w-6 h-6 rounded-full bg-amber-400/25 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-square-check text-amber-300 text-[11px]"></i>
                            </span>
                            <span class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider">Lafadz / Jawaban</span>
                        </div>
                        {{-- Spinning star --}}
                        <svg class="star-spin shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10 1.5L12.2 7.8H19L13.6 11.5L15.8 17.8L10 14.1L4.2 17.8L6.4 11.5L1 7.8H7.8L10 1.5Z" fill="#fbbf24"/>
                        </svg>
                    </div>

                    {{-- Arabic / answer content --}}
                    <div class="flex-1 flex flex-col items-center justify-center px-5 py-4 relative z-10 space-y-3">
                        <!-- Arabic Text -->
                        <p class="arabic-text text-2xl sm:text-3xl font-bold text-amber-300 text-center leading-loose"
                           x-text="getBackContent().arabic"
                           dir="rtl"></p>
                        
                        <!-- Transliteration Text (if exists) -->
                        <template x-if="getBackContent().transliteration">
                            <p class="text-xs sm:text-sm font-semibold text-emerald-200 text-center italic leading-relaxed px-2"
                               x-text="getBackContent().transliteration"></p>
                        </template>
                    </div>

                    {{-- Flip hint --}}
                    <div class="flex items-center justify-center gap-1.5 pb-4 relative z-10 shrink-0">
                        <i class="fa-solid fa-rotate text-emerald-400/50 text-[10px]"></i>
                        <span class="text-[10px] text-emerald-400/50 font-semibold">Klik kartu untuk membalik</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ===== NAVIGATION BUTTONS ===== --}}
        <div class="flex items-center gap-3 mb-3">
            <button type="button" @click="prevCard()"
                    :disabled="currentIndex === 0"
                    class="flex-1 py-3.5 bg-white border-2 border-gray-200 text-gray-700 text-xs font-bold rounded-full shadow-sm hover:border-gray-300 hover:bg-gray-50 disabled:opacity-30 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Sebelumnya
            </button>

            <button type="button" @click="nextCard()"
                    x-show="currentIndex < cards.length - 1"
                    class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-full shadow-md transition flex items-center justify-center gap-2">
                Berikutnya <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </button>

            <button type="button" @click="finishSession()"
                    x-show="currentIndex === cards.length - 1"
                    class="flex-1 py-3.5 bg-amber-500 hover:bg-amber-400 text-white text-xs font-bold rounded-full shadow-md transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-check text-[11px]"></i> Selesai!
            </button>
        </div>

        {{-- Reset link --}}
        <div class="text-center">
            <button type="button" @click="resetSession()"
                    class="text-[11px] text-gray-400 hover:text-emerald-700 font-semibold transition">
                <i class="fa-solid fa-rotate-left mr-1"></i> Ulangi dari Awal
            </button>
        </div>
    </div>

    {{-- ===== COMPLETION SCREEN ===== --}}
    <div x-show="completed" x-cloak class="text-center py-8">
        <div class="celebrate-pop">
            {{-- Trophy icon --}}
            <div class="relative inline-block mb-5">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-amber-400 to-orange-400 rounded-full flex items-center justify-center shadow-xl float-bob">
                    <i class="fa-solid fa-trophy text-4xl text-white"></i>
                </div>
                {{-- Sparkle stars --}}
                <svg class="absolute -top-2 -right-1 star-spin" width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M11 2L13 8.5H20L14.5 12.5L16.5 19L11 15L5.5 19L7.5 12.5L2 8.5H9L11 2Z" fill="#fbbf24"/>
                </svg>
                <svg class="absolute -bottom-1 -left-2" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M9 2L10.5 7.5H16.5L12 10.5L13.5 16.5L9 13L4.5 16.5L6 10.5L1.5 7.5H7.5L9 2Z" fill="#34d399"/>
                </svg>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-1.5">
                Hebat! <span class="text-amber-500">Keren Banget!</span>
            </h2>
            <p class="text-sm text-gray-500 leading-relaxed mb-1.5 max-w-xs mx-auto">
                Kamu telah menyelesaikan semua kartu<br>
                <strong class="text-emerald-700">{{ $deck->nama }}</strong>!
            </p>
            <p class="text-xs text-gray-400 mb-4">
                Terus semangat belajar ya!
                <i class="fa-solid fa-heart text-rose-400 ml-0.5"></i>
            </p>

            <!-- XP Reward Banner -->
            <div class="inline-flex items-center gap-1.5 bg-amber-400 text-emerald-950 font-extrabold text-[11px] px-3.5 py-1.5 rounded-full shadow-xs mb-6 select-none animate-bounce">
                <i class="fa-solid fa-star text-xs animate-pulse"></i>
                <span>KAMU DAPAT +10 XP!</span>
            </div>

            <!-- New Badges Congratulation Banner -->
            <template x-if="newBadges && newBadges.length > 0">
                <div class="mb-6 max-w-xs mx-auto p-4 bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-300 rounded-3xl space-y-3 shadow-xs">
                    <p class="text-[10px] text-amber-800 font-extrabold uppercase tracking-wider animate-pulse">🎉 Lencana Baru Didapatkan!</p>
                    <div class="flex flex-col gap-2">
                        <template x-for="badge in newBadges">
                            <div class="flex items-center space-x-2 bg-white px-3 py-2.5 rounded-2xl shadow-xs border border-amber-200 text-left">
                                <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                                    <i :class="badge.icon + ' text-sm'"></i>
                                </span>
                                <div>
                                    <h4 class="font-extrabold text-[10px] text-gray-900 leading-tight" x-text="badge.nama"></h4>
                                    <p class="text-[8px] text-gray-500 leading-normal" x-text="badge.deskripsi"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- 3 stars --}}
            <div class="flex items-center justify-center gap-2 mb-8">
                <i class="fa-solid fa-star text-2xl text-amber-400" style="animation: star-spin 2s ease-in-out infinite .0s"></i>
                <i class="fa-solid fa-star text-3xl text-amber-400" style="animation: star-spin 2s ease-in-out infinite .2s"></i>
                <i class="fa-solid fa-star text-2xl text-amber-400" style="animation: star-spin 2s ease-in-out infinite .4s"></i>
            </div>

            {{-- Action buttons --}}
            <div class="space-y-3 max-w-xs mx-auto">
                <button type="button" @click="resetSession()"
                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-full shadow-md transition">
                    <i class="fa-solid fa-rotate-left mr-1.5"></i> Ulangi Lagi
                </button>
                <a href="{{ route('murid.flashcard.index') }}"
                   class="block w-full py-3.5 bg-white border-2 border-gray-200 text-gray-700 font-bold text-sm rounded-full hover:border-gray-300 transition">
                    <i class="fa-solid fa-layer-group mr-1.5"></i> Pilih Dek Lain
                </a>
            </div>
        </div>
    </div>

</div>

<script>
    function flashcardSession() {
        return {
            currentIndex: 0,
            flipped:      false,
            completed:    false,
            visible:      true,
            cards:        @json($cardsData),
            pointsEarned: 0,
            newBadges:    [],

            getBackContent() {
                const back = this.cards[this.currentIndex].back || '';
                const parts = back.split('\n\n');
                return {
                    arabic: parts[0] || '',
                    transliteration: parts[1] || ''
                };
            },

            _transition(fn) {
                this.visible = false;
                this.flipped = false;
                setTimeout(() => {
                    fn();
                    this.visible = true;
                }, 200);
            },
            nextCard() {
                if (this.currentIndex < this.cards.length - 1) {
                    this._transition(() => this.currentIndex++);
                }
            },
            prevCard() {
                if (this.currentIndex > 0) {
                    this._transition(() => this.currentIndex--);
                }
            },
            finishSession() {
                fetch('{{ route("murid.flashcard.finish", $deck->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.pointsEarned = data.points_earned;
                        this.newBadges = data.new_badges;
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    this._transition(() => this.completed = true);
                });
            },
            resetSession() {
                this._transition(() => {
                    this.currentIndex = 0;
                    this.completed    = false;
                    this.pointsEarned = 0;
                    this.newBadges    = [];
                });
            }
        }
    }
</script>
@endsection
