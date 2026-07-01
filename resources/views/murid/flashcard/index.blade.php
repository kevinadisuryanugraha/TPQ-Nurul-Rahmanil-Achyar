@extends('layouts.murid')
@section('title', 'Pembelajaran Flashcard')
@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-emerald-800 to-teal-950 p-6 rounded-3xl text-white relative overflow-hidden shadow-md">
        <div class="absolute inset-0 pattern-islamic opacity-10"></div>
        <div class="relative z-10 space-y-1">
            <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider">Interaktif & Edukatif</span>
            <h2 class="text-xl font-extrabold">Ayo Belajar dengan Flashcard!</h2>
            <p class="text-xs text-emerald-100/75 max-w-sm">Pilih dek kartu di bawah ini untuk mulai melatih ingatan dan hafalanmu.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($decks as $deck)
            @php
                $isRecommended = $deck->level_target_id == $student->current_level_id;
            @endphp
            <a href="{{ route('murid.flashcard.show', $deck->id) }}" class="block p-5 bg-white rounded-2xl border {{ $isRecommended ? 'border-amber-200 bg-amber-50/20' : 'border-gray-100' }} hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-clone"></i>
                    </span>
                    @if($isRecommended)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-500 text-white uppercase tracking-wider flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-star"></i> Untukmu
                        </span>
                    @endif
                </div>
                <h3 class="font-extrabold text-gray-900 text-sm">{{ $deck->nama }}</h3>
                <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ $deck->deskripsi ?? 'Ayo klik untuk mulai latihan.' }}</p>
                
                <div class="border-t border-dashed border-gray-100 mt-4 pt-3 flex justify-between items-center text-[10px] text-emerald-800 font-bold">
                    <span>Mulai Belajar</span>
                    <i class="fa-solid fa-arrow-right-long text-amber-600"></i>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
