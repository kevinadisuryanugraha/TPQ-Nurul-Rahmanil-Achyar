@extends('layouts.murid')

@section('title', 'Panduan Praktik')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ search: '' }">
    <!-- Header Title -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-compass"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Panduan Praktik Fiqh</h2>
            <p class="text-[10px] text-gray-500">Belajar tata cara ibadah langkah-demi-langkah</p>
        </div>
    </div>

    <!-- Search bar -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari panduan (misal: wudhu)..." 
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
    </div>

    <!-- Guides List -->
    <div class="space-y-4">
        @forelse($panduans as $panduan)
            <a href="{{ route('murid.panduan.show', $panduan->id) }}"
                x-show="search === '' || '{{ strtolower($panduan->judul) }}'.includes(search.toLowerCase()) || '{{ strtolower($panduan->jenis_praktik) }}'.includes(search.toLowerCase())" x-transition
                class="bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-xs block hover:border-emerald-300 transition duration-150">
                
                <!-- Cover Image -->
                @if($panduan->cover_image)
                    <img src="{{ $panduan->cover_image }}" alt="{{ $panduan->judul }}" class="w-full h-36 object-cover">
                @else
                    <div class="w-full h-28 bg-gradient-to-br from-rose-800 to-rose-950 flex flex-col items-center justify-center text-white relative">
                        <i class="fa-solid fa-compass text-3xl text-amber-300 mb-1"></i>
                        <span class="text-[10px] font-bold opacity-80 uppercase tracking-widest">{{ $panduan->jenis_praktik }}</span>
                    </div>
                @endif

                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="bg-rose-50 text-rose-800 text-[9px] font-bold px-2 py-0.5 rounded border border-rose-100 uppercase tracking-wider inline-block">
                            {{ $panduan->jenis_praktik }}
                        </span>
                        
                        <span class="text-[8px] bg-gray-50 border border-gray-250 px-2 py-0.5 rounded text-gray-500 font-bold">
                            {{ $panduan->langkahs->count() }} Langkah
                        </span>
                    </div>

                    <h3 class="font-extrabold text-xs text-gray-900 leading-snug line-clamp-1">{{ $panduan->judul }}</h3>
                    <p class="text-[10px] text-gray-500 line-clamp-2 leading-relaxed">
                        {{ $panduan->deskripsi }}
                    </p>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-compass text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Panduan praktik belum tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
