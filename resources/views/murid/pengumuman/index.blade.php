@extends('layouts.murid')

@section('title', 'Pengumuman')

@section('content')
<div class="px-5 py-6 space-y-5">
    <!-- Header Title -->
    <div class="flex items-center space-x-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Papan Pengumuman</h2>
            <p class="text-[10px] text-gray-500">Melihat pemberitahuan terbaru dari ustadz dan ustadzah</p>
        </div>
    </div>

    <!-- Announcement list -->
    <div class="space-y-4" x-data="{ openId: null }">
        @forelse($pengumumans as $ann)
            <div class="bg-white rounded-3xl border border-gray-150 shadow-xs overflow-hidden">
                <!-- Title bar clickable to toggle -->
                <button @click="openId = (openId === {{ $ann->id }} ? null : {{ $ann->id }})"
                    class="w-full p-5 text-left focus:outline-none flex justify-between items-start gap-3">
                    <div class="space-y-1 flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <h3 class="font-extrabold text-xs text-gray-900 leading-snug">{{ $ann->judul }}</h3>
                        </div>
                        <div class="flex items-center space-x-2 text-[8px] text-gray-400 font-semibold pl-4">
                            <span>Oleh: {{ $ann->admin->nama ?? 'Ustadz' }}</span>
                            <span>&bull;</span>
                            <span>{{ \Carbon\Carbon::parse($ann->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    <i class="fa-solid text-[9px] text-gray-400 mt-1 transition-transform duration-200"
                        :class="openId === {{ $ann->id }} ? 'fa-chevron-up text-emerald-700' : 'fa-chevron-down'"></i>
                </button>

                <!-- Body content -->
                <div x-show="openId === {{ $ann->id }}" x-collapse
                    class="p-5 pt-0 border-t border-gray-50 bg-emerald-50/10 text-[10px] text-gray-600 leading-relaxed font-semibold space-y-2">
                    {!! $ann->isi !!}
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400 shadow-sm">
                <i class="fa-solid fa-bullhorn text-4xl text-gray-300 mb-3"></i>
                <p class="text-xs">Belum ada pengumuman untuk saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
