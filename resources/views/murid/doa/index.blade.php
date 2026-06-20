@extends('layouts.murid')

@section('title', 'Doa Harian')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ 
    activeCategory: 'Semua',
    search: '',
    openDuaId: null,
    filterDua(kategori, judul) {
        let matchesCategory = this.activeCategory === 'Semua' || kategori === this.activeCategory;
        let matchesSearch = this.search === '' || judul.toLowerCase().includes(this.search.toLowerCase());
        return matchesCategory && matchesSearch;
    }
}">
    <!-- Header Title -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-hands-praying"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Doa-Doa Harian</h2>
            <p class="text-[10px] text-gray-500">Kumpulan doa harian untuk dibaca dan dihafal</p>
        </div>
    </div>

    <!-- Search input -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari doa..." 
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
    </div>

    <!-- Category Tabs (Horizontal Scrollable) -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-1 -mx-5 px-5 scrollbar-none">
        <button @click="activeCategory = 'Semua'" 
            :class="activeCategory === 'Semua' ? 'bg-emerald-800 text-white font-bold' : 'bg-white text-gray-500 border border-gray-100'"
            class="px-4 py-1.5 rounded-full text-[10px] whitespace-nowrap shadow-xs transition">
            Semua
        </button>
        @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat }}'" 
                :class="activeCategory === '{{ $cat }}' ? 'bg-emerald-800 text-white font-bold' : 'bg-white text-gray-500 border border-gray-100'"
                class="px-4 py-1.5 rounded-full text-[10px] whitespace-nowrap shadow-xs transition">
                {{ $cat }}
            </button>
        @endforeach
    </div>

    <!-- Doa Accordion Cards -->
    <div class="space-y-2">
        @forelse($doas as $doa)
            <div x-show="filterDua('{{ $doa->kategori }}', '{{ $doa->judul }}')" x-transition
                class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs">
                
                <!-- Card Header (Toggle Clickable) -->
                <button @click="openDuaId = (openDuaId === {{ $doa->id }} ? null : {{ $doa->id }})"
                    class="w-full p-4 flex items-center justify-between text-left focus:outline-none">
                    <div class="flex items-center space-x-3.5">
                        <span class="w-6 h-6 rounded-full bg-amber-50 border border-amber-100 text-amber-600 font-bold text-[10px] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-star text-[8px]"></i>
                        </span>
                        <div>
                            <h3 class="font-extrabold text-xs text-gray-900 leading-snug">{{ $doa->judul }}</h3>
                            <span class="text-[8px] text-emerald-800 font-bold uppercase tracking-wider">{{ $doa->kategori }}</span>
                        </div>
                    </div>
                    <i class="fa-solid text-[10px] text-gray-400 transition-transform duration-200"
                        :class="openDuaId === {{ $doa->id }} ? 'fa-chevron-up text-emerald-700' : 'fa-chevron-down'"></i>
                </button>

                <!-- Card Body (Arabic, Translation) -->
                <div x-show="openDuaId === {{ $doa->id }}" x-collapse
                    class="border-t border-gray-50 bg-emerald-50/20 p-4 space-y-3">
                    <div class="text-right">
                        <p class="arabic-text text-xl font-bold text-emerald-950 leading-loose">
                            {{ $doa->teks_arab }}
                        </p>
                    </div>
                    <p class="text-[10px] text-emerald-700 italic font-semibold leading-relaxed">
                        {{ $doa->transliterasi }}
                    </p>
                    <p class="text-[10px] text-gray-600 leading-relaxed">
                        <strong>Artinya:</strong> "{{ $doa->terjemahan }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-hands-praying text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Data doa tidak tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
