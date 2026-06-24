@extends('layouts.murid')

@section('title', 'Asmaul Husna')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ 
    search: '',
    openId: null,
    filterName(latin, arti, arab) {
        if (this.search === '') return true;
        const q = this.search.toLowerCase();
        return latin.toLowerCase().includes(q) || arti.toLowerCase().includes(q) || arab.includes(q);
    }
}">
    <!-- Header -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-kaaba"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Asmaul Husna</h2>
            <p class="text-[10px] text-gray-500">99 Nama Allah Yang Maha Indah</p>
        </div>
    </div>

    <!-- Search -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari nama Allah..."
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
    </div>

    <!-- Counter -->
    <div class="text-[10px] text-gray-400 font-semibold text-center -mt-2">
        <span x-text="document.querySelectorAll('[data-asma-item]').length || '99'"></span> dari 99 Nama
    </div>

    <!-- Accordion Cards -->
    <div class="space-y-2">
        @forelse($names as $name)
            <div data-asma-item x-show="filterName('{{ $name->latin }}', '{{ $name->arti }}', '{{ $name->arab }}')" x-transition
                class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs">
                
                <!-- Card Header (Toggle) -->
                <button @click="openId = (openId === {{ $name->id }} ? null : {{ $name->id }})"
                    class="w-full p-4 flex items-center justify-between text-left focus:outline-none">
                    <div class="flex items-center space-x-3.5">
                        <span class="w-7 h-7 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-[10px] flex items-center justify-center shrink-0">
                            {{ $name->urutan }}
                        </span>
                        <div>
                            <h3 class="arabic-text text-lg font-bold text-emerald-950 leading-none">{{ $name->arab }}</h3>
                            <span class="text-[12px] font-bold text-gray-800">{{ $name->latin }}</span>
                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $name->arti }}</p>
                        </div>
                    </div>
                    <i class="fa-solid text-[10px] text-gray-400 transition-transform duration-200"
                        :class="openId === {{ $name->id }} ? 'fa-chevron-up text-emerald-700' : 'fa-chevron-down'"></i>
                </button>

                <!-- Card Body (Description) -->
                <div x-show="openId === {{ $name->id }}" x-collapse
                    class="border-t border-gray-50 bg-emerald-50/20 p-4">
                    <p class="text-[11px] text-gray-700 leading-relaxed">
                        <strong class="text-emerald-800">{{ $name->latin }}</strong> ({{ $name->arti }}): {{ $name->deskripsi }}
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-kaaba text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Data Asmaul Husna tidak tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
