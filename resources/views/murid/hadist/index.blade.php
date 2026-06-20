@extends('layouts.murid')

@section('title', 'Hadist Pilihan')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ search: '' }">
    <!-- Header Title -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-quote-left"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Hadist Pilihan</h2>
            <p class="text-[10px] text-gray-500">Hadist-hadist pendek tentang akhlak dan adab</p>
        </div>
    </div>

    <!-- Search bar -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari hadist (terjemahan, perawi)..." 
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
    </div>

    <!-- Hadist list cards -->
    <div class="space-y-4">
        @forelse($hadists as $hadist)
            <div x-show="search === '' || '{{ strtolower($hadist->terjemahan) }}'.includes(search.toLowerCase()) || '{{ strtolower($hadist->perawi) }}'.includes(search.toLowerCase())" x-transition
                class="bg-white rounded-2xl p-5 border border-gray-150 shadow-xs relative overflow-hidden flex flex-col justify-between">
                <span class="absolute top-0 right-0 w-20 h-20 bg-emerald-500 opacity-5 rounded-full -mr-5 -mt-5"></span>
                
                <div class="space-y-4">
                    <!-- Arabic -->
                    <div class="text-right">
                        <p class="arabic-text text-lg font-bold text-emerald-950 leading-loose">
                            {{ $hadist->teks_arab }}
                        </p>
                    </div>

                    <!-- Translation -->
                    <p class="text-[10px] text-gray-600 leading-relaxed font-semibold italic">
                        "{{ $hadist->terjemahan }}"
                    </p>
                </div>

                <!-- Footer (Sanad & source) -->
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-[9px] text-gray-500 font-bold">
                    <span>
                        @if($hadist->perawi)
                            Perawi: {{ $hadist->perawi }}
                        @else
                            Sanad Shahih
                        @endif
                    </span>
                    <span class="bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded border border-emerald-100 uppercase tracking-wide">
                        HR. {{ $hadist->sumber_kitab }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-quote-left text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Data hadist tidak tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
