@extends('layouts.murid')

@section('title', 'Al-Qur\'an')

@section('content')
<div class="px-5 py-6 space-y-4">
    <!-- Header banner -->
    <div class="flex items-center space-x-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-book-open"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Al-Qur'anul Karim</h2>
            <p class="text-[10px] text-gray-500">Membaca ayat suci Al-Qur'an secara digital</p>
        </div>
    </div>

    <!-- Search box -->
    <form action="{{ route('murid.quran.index') }}" method="GET" class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" 
            placeholder="Cari surah (misal: Al-Kahfi)..." 
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
        
        @if(request('search'))
            <a href="{{ route('murid.quran.index') }}" class="absolute right-3.5 top-3.5 text-[9px] text-rose-500 font-bold hover:underline">
                Clear
            </a>
        @endif
    </form>

    <!-- Surah list -->
    <div class="space-y-2">
        @forelse($surahs as $surah)
            <a href="{{ route('murid.quran.show', $surah->id) }}" 
                class="bg-white rounded-2xl p-4 border border-gray-100 flex items-center justify-between shadow-xs hover:border-emerald-300 transition duration-150">
                
                <div class="flex items-center space-x-3.5">
                    <!-- Surah number badge -->
                    <div class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                        {{ $surah->id }}
                    </div>
                    
                    <div>
                        <h3 class="font-extrabold text-xs text-gray-900">{{ $surah->nama_latin }}</h3>
                        <span class="text-[9px] text-gray-400">
                            {{ $surah->arti }} &bull; {{ $surah->jumlah_ayat }} Ayat
                        </span>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="arabic-text font-bold text-lg text-emerald-950 leading-none pt-1">
                        {{ $surah->nama_arab }}
                    </span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 pl-1"></i>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-circle-exclamation text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Surah tidak ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
