@extends('layouts.murid')
@section('title', 'Latihan: ' . $deck->nama)
@section('content')
<style>
    .perspective-1000 {
        perspective: 1000px;
    }
    .transform-style-3d {
        transform-style: preserve-3d;
    }
    .backface-hidden {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .rotate-y-180 {
        transform: rotateY(180deg);
    }
</style>
<div class="max-w-md mx-auto px-4 py-8 space-y-6" x-data="flashcardSession()">

    <!-- Progress and Back Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('murid.flashcard.index') }}" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-gray-700 text-xs font-bold rounded-xl flex items-center gap-1.5 transition">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> Kembali
        </a>
        <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100" x-text="`Kartu ${currentIndex + 1} dari ${cards.length}`"></span>
    </div>

    <!-- 3D Flip Card Container -->
    <div class="perspective-1000 w-full h-80 cursor-pointer" @click="flipped = !flipped">
        <div class="relative w-full h-full duration-500 transform-style-3d select-none" :class="flipped ? 'rotate-y-180' : ''">
            
            <!-- Front Face -->
            <div class="absolute inset-0 bg-white border border-emerald-50 rounded-3xl p-8 flex flex-col justify-between backface-hidden shadow-lg">
                <div class="flex justify-between items-center text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                    <span>Sisi Depan</span>
                    <span class="text-amber-500"><i class="fa-solid fa-circle-question"></i> Pertanyaan / Arti</span>
                </div>
                
                <div class="flex-1 flex items-center justify-center text-center px-4">
                    <p class="text-sm sm:text-base font-extrabold text-emerald-950 leading-relaxed whitespace-pre-line" x-text="cards[currentIndex].front"></p>
                </div>
                
                <div class="text-center text-[11px] text-gray-400 font-semibold mt-4">
                    <i class="fa-solid fa-rotate mr-1 text-emerald-800"></i> Klik kartu untuk membalik
                </div>
            </div>

            <!-- Back Face -->
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-8 flex flex-col justify-between backface-hidden rotate-y-180 shadow-lg relative overflow-hidden">
                <div class="absolute inset-0 pattern-islamic opacity-10 pointer-events-none"></div>
                
                <div class="flex justify-between items-center text-[10px] text-emerald-300 font-bold uppercase tracking-wider relative z-10">
                    <span>Sisi Belakang</span>
                    <span class="text-amber-400"><i class="fa-solid fa-square-check"></i> Lafadz / Jawaban</span>
                </div>
                
                <div class="flex-1 flex flex-col items-center justify-center text-center px-4 relative z-10 space-y-4">
                    <!-- Arabic view (Amiri font with RTL) -->
                    <p class="arabic-text text-3xl font-bold leading-loose text-amber-300 tracking-wide whitespace-pre-line" x-text="cards[currentIndex].back" dir="rtl"></p>
                </div>
                
                <div class="text-center text-[11px] text-emerald-300/60 font-semibold mt-4 relative z-10">
                    <i class="fa-solid fa-rotate mr-1 text-amber-400"></i> Klik kartu untuk membalik
                </div>
            </div>

        </div>
    </div>

    <!-- Navigation buttons -->
    <div class="flex items-center gap-3">
        <button type="button" @click="prevCard()" :disabled="currentIndex === 0"
                class="flex-1 py-4 bg-white border border-gray-200 hover:bg-gray-50 disabled:opacity-40 text-gray-700 text-xs font-bold rounded-2xl shadow-sm transition flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Kartu Sebelumnya
        </button>
        <button type="button" @click="nextCard()" :disabled="currentIndex === cards.length - 1"
                class="flex-1 py-4 bg-emerald-800 hover:bg-emerald-700 disabled:opacity-40 text-white text-xs font-bold rounded-2xl shadow-sm transition flex items-center justify-center gap-1.5">
            Kartu Berikutnya <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>

    <!-- Reset button -->
    <div class="text-center pt-2">
        <button type="button" @click="resetSession()" class="text-xs text-gray-400 hover:text-emerald-800 font-semibold transition">
            <i class="fa-solid fa-rotate-left mr-1"></i> Reset Latihan
        </button>
    </div>

</div>

<script>
    function flashcardSession() {
        return {
            currentIndex: 0,
            flipped: false,
            cards: @json($cardsData),
            nextCard() {
                if (this.currentIndex < this.cards.length - 1) {
                    this.flipped = false;
                    setTimeout(() => {
                        this.currentIndex++;
                    }, 200);
                }
            },
            prevCard() {
                if (this.currentIndex > 0) {
                    this.flipped = false;
                    setTimeout(() => {
                        this.currentIndex--;
                    }, 200);
                }
            },
            resetSession() {
                this.flipped = false;
                setTimeout(() => {
                    this.currentIndex = 0;
                }, 200);
            }
        }
    }
</script>
@endsection
