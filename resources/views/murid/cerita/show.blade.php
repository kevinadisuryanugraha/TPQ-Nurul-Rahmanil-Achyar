@extends('layouts.murid')

@section('title', $cerita->judul)

@section('content')
<div class="px-5 py-6 space-y-5">
    <!-- Back Header -->
    <div>
        <a href="{{ route('murid.cerita.index') }}" class="text-xs font-bold text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Kisah</span>
        </a>
    </div>

    <!-- Story Main Body -->
    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
        <!-- Cover image -->
        @if($cerita->thumbnail)
            <img src="{{ $cerita->thumbnail }}" alt="{{ $cerita->judul }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-32 bg-gradient-to-br from-purple-800 to-purple-950 flex flex-col items-center justify-center text-white relative">
                <i class="fa-solid fa-feather-pointed text-3xl text-amber-300 mb-1"></i>
                <span class="text-[10px] font-bold opacity-80 uppercase tracking-widest">{{ str_replace('_', ' ', $cerita->kategori) }}</span>
            </div>
        @endif

        <div class="p-5 space-y-4">
            <!-- Badges & Title -->
            <div class="space-y-2">
                <span class="bg-purple-50 text-purple-800 text-[9px] font-bold px-2.5 py-0.5 rounded border border-purple-100 uppercase tracking-wider inline-block">
                    {{ str_replace('_', ' ', $cerita->kategori) }}
                </span>
                <h2 class="font-extrabold text-sm text-gray-900 leading-snug">{{ $cerita->judul }}</h2>
                <div class="flex items-center space-x-2 text-[9px] text-gray-400">
                    <span class="font-semibold text-gray-700">Penulis: {{ $cerita->admin->nama ?? 'Ustadz' }}</span>
                    <span>&bull;</span>
                    <span>Tgl Terbit: {{ $cerita->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="border-t border-gray-50 pt-4 text-xs text-gray-700 leading-relaxed space-y-3 font-medium">
                {!! $cerita->konten !!}
            </div>
        </div>
    </div>
</div>
@endsection
