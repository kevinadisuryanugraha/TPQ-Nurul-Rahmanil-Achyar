@extends('layouts.murid')

@section('title', $surah->nama_latin)

@section('content')
<div class="px-5 py-6 space-y-6" x-data="{ 
    arabicSize: 'text-2xl',
    playingId: null,
    audioObject: null,
    
    toggleAudio(surahId, ayahNo) {
        const targetId = `${surahId}_${ayahNo}`;
        
        if (this.playingId === targetId) {
            if (this.audioObject) {
                this.audioObject.pause();
            }
            this.playingId = null;
            return;
        }
        
        if (this.audioObject) {
            this.audioObject.pause();
            this.audioObject = null;
        }
        
        const surahStr = String(surahId).padStart(3, '0');
        const ayahStr = String(ayahNo).padStart(3, '0');
        const audioUrl = `https://everyayah.com/data/Alafasy_128kbps/${surahStr}${ayahStr}.mp3`;
        
        this.playingId = targetId;
        this.audioObject = new Audio(audioUrl);
        
        this.audioObject.play().catch(err => {
            console.error('Audio playback failed:', err);
            this.playingId = null;
        });
        
        this.audioObject.addEventListener('ended', () => {
            this.playingId = null;
            this.audioObject = null;
        });
    }
}">
    <!-- Navigation Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('murid.quran.index') }}" class="text-xs font-bold text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Surah</span>
        </a>

        <!-- Font size togglers -->
        <div class="flex items-center space-x-1.5 bg-white border border-gray-200 px-2 py-1 rounded-xl shadow-xs shrink-0">
            <span class="text-[9px] text-gray-400 font-bold pr-1">Ukuran:</span>
            <button @click="arabicSize = 'text-xl'" :class="arabicSize === 'text-xl' ? 'bg-emerald-700 text-white' : 'text-gray-500'" class="w-6 h-6 rounded-lg text-xs font-bold transition">A-</button>
            <button @click="arabicSize = 'text-2xl'" :class="arabicSize === 'text-2xl' ? 'bg-emerald-700 text-white' : 'text-gray-500'" class="w-6 h-6 rounded-lg text-xs font-bold transition">A</button>
            <button @click="arabicSize = 'text-3xl'" :class="arabicSize === 'text-3xl' ? 'bg-emerald-700 text-white' : 'text-gray-500'" class="w-6 h-6 rounded-lg text-xs font-bold transition">A+</button>
        </div>
    </div>

    <!-- Surah Intro Card -->
    <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-6 text-center shadow-md relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-24 h-24 bg-amber-400 opacity-15 rounded-full blur-lg"></div>
        <div class="relative z-10 space-y-2">
            <h2 class="font-extrabold text-lg text-amber-300">{{ $surah->nama_latin }}</h2>
            <p class="text-[10px] text-emerald-200 uppercase tracking-widest font-semibold">
                {{ $surah->arti }} &bull; {{ $surah->tempat_turun == 'Mekah' ? 'Makkiyyah' : 'Madaniyyah' }} &bull; {{ $surah->jumlah_ayat }} Ayat
            </p>
            
            @if($surah->id != 1 && $surah->id != 9)
                <div class="pt-4 border-t border-emerald-800 mt-4">
                    <p class="arabic-text text-xl text-amber-100">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Verses List -->
    <div class="space-y-4">
        @foreach($surah->ayats as $ayat)
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs space-y-4">
                <!-- Verse Top bar -->
                <div class="flex items-center justify-between border-b border-gray-50 pb-2.5">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold flex items-center justify-center shrink-0">
                            {{ $ayat->nomor_ayat }}
                        </div>
                        
                        <!-- Murottal Audio Player Trigger -->
                        <button type="button" 
                                @click="toggleAudio({{ $surah->id }}, {{ $ayat->nomor_ayat }})"
                                class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-850 flex items-center justify-center hover:bg-emerald-100 transition shrink-0 select-none">
                            <i class="fa-solid text-[9px] pointer-events-none" 
                               :class="playingId === '{{ $surah->id }}_{{ $ayat->nomor_ayat }}' ? 'fa-pause text-amber-500' : 'fa-play'"></i>
                        </button>
                    </div>

                    <div class="flex space-x-2">
                        <span class="text-[9px] text-gray-400 font-semibold uppercase tracking-wider">Surah {{ $surah->id }}:{{ $ayat->nomor_ayat }}</span>
                    </div>
                </div>

                <!-- Verse Arabic text -->
                <div class="text-right">
                    <span dir="rtl" class="arabic-text font-bold text-emerald-950 leading-loose block" :class="arabicSize">
                        {{ $ayat->teks_arab }}
                    </span>
                </div>

                <!-- Verse Transliterasi (Latin) -->
                @if($ayat->teks_latin)
                    <p class="text-[10px] text-emerald-700 italic leading-relaxed">
                        {{ $ayat->teks_latin }}
                    </p>
                @endif

                <!-- Verse Translation -->
                <p class="text-[11px] text-gray-600 leading-relaxed font-medium">
                    {{ $ayat->terjemahan }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
