@extends('layouts.murid')

@section('title', 'Cerita Kisah')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ search: '' }">
    <!-- Header Title -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-feather-pointed"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Cerita & Kisah Islami</h2>
            <p class="text-[10px] text-gray-500">Membaca kisah teladan para nabi dan sahabat</p>
        </div>
    </div>

    <!-- Search bar -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari cerita kisah..." 
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
    </div>

    <!-- Stories List -->
    <div class="space-y-4">
        @forelse($ceritas as $cerita)
            <a href="{{ route('murid.cerita.show', $cerita->id) }}"
                x-show="search === '' || '{{ strtolower($cerita->judul) }}'.includes(search.toLowerCase())" x-transition
                class="bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-xs block hover:border-emerald-300 transition duration-150">
                
                <!-- Cover Image -->
                @if($cerita->thumbnail)
                    <img src="{{ $cerita->thumbnail }}" alt="{{ $cerita->judul }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-32 bg-gradient-to-br from-purple-800 to-purple-950 flex flex-col items-center justify-center text-white relative">
                        <i class="fa-solid fa-feather-pointed text-3xl text-amber-300 mb-1"></i>
                        <span class="text-[10px] font-bold opacity-80 uppercase tracking-widest">{{ str_replace('_', ' ', $cerita->kategori) }}</span>
                    </div>
                @endif

                <div class="p-4 space-y-2">
                    <span class="bg-purple-50 text-purple-800 text-[9px] font-bold px-2 py-0.5 rounded border border-purple-100 uppercase tracking-wider inline-block">
                        {{ str_replace('_', ' ', $cerita->kategori) }}
                    </span>
                    <h3 class="font-extrabold text-xs text-gray-900 leading-snug line-clamp-1">{{ $cerita->judul }}</h3>
                    <p class="text-[10px] text-gray-500 line-clamp-2 leading-relaxed">
                        {{ strip_tags($cerita->konten) }}
                    </p>
                    
                    <div class="pt-2.5 border-t border-gray-50 flex items-center justify-between text-[8px] text-gray-400">
                        <span>Oleh: {{ $cerita->admin->nama ?? 'Ustadz' }}</span>
                        <span>{{ $cerita->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-feather-pointed text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Belum ada cerita yang tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
