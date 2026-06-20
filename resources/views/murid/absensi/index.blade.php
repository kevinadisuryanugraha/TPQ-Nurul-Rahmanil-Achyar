@extends('layouts.murid')

@section('title', 'Absensi')

@section('content')
<div class="px-5 py-6 space-y-5">
    <!-- Header Title -->
    <div class="flex items-center space-x-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Daftar Kehadiranku</h2>
            <p class="text-[10px] text-gray-500">Melihat rekap dan riwayat kehadiran harianmu</p>
        </div>
    </div>

    <!-- Monthly Stats Counters -->
    <div class="bg-white rounded-3xl border border-gray-150 p-4 shadow-xs">
        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-3 text-center">Rekap Kehadiran Bulan Ini</span>
        <div class="grid grid-cols-4 gap-2 text-center">
            <!-- Hadir -->
            <div class="bg-green-50 rounded-2xl p-2 border border-green-100">
                <span class="text-lg font-extrabold text-green-700 block">{{ $absensiStats['hadir'] }}</span>
                <span class="text-[8px] text-green-600 font-bold block uppercase tracking-wider">Hadir</span>
            </div>

            <!-- Izin -->
            <div class="bg-amber-50 rounded-2xl p-2 border border-amber-100">
                <span class="text-lg font-extrabold text-amber-600 block">{{ $absensiStats['izin'] }}</span>
                <span class="text-[8px] text-amber-600 font-bold block uppercase tracking-wider">Izin</span>
            </div>

            <!-- Sakit -->
            <div class="bg-blue-50 rounded-2xl p-2 border border-blue-100">
                <span class="text-lg font-extrabold text-blue-600 block">{{ $absensiStats['sakit'] }}</span>
                <span class="text-[8px] text-blue-600 font-bold block uppercase tracking-wider">Sakit</span>
            </div>

            <!-- Alpha -->
            <div class="bg-rose-50 rounded-2xl p-2 border border-rose-100">
                <span class="text-lg font-extrabold text-rose-700 block">{{ $absensiStats['alpha'] }}</span>
                <span class="text-[8px] text-rose-600 font-bold block uppercase tracking-wider">Alpha</span>
            </div>
        </div>
    </div>

    <!-- Attendance Timeline Log -->
    <div class="space-y-3">
        <h3 class="font-bold text-gray-800 text-sm">Riwayat Absensi</h3>
        <div class="space-y-2">
            @forelse($history as $record)
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-center justify-between">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold shrink-0 text-xs
                            @if($record->status === 'hadir') bg-green-150 text-green-800
                            @elseif($record->status === 'izin') bg-amber-150 text-amber-800
                            @elseif($record->status === 'sakit') bg-blue-150 text-blue-800
                            @else bg-rose-150 text-rose-800
                            @endif">
                            @if($record->status === 'hadir') <i class="fa-solid fa-check"></i>
                            @elseif($record->status === 'izin') <i class="fa-solid fa-envelope"></i>
                            @elseif($record->status === 'sakit') <i class="fa-solid fa-house-medical"></i>
                            @else <i class="fa-solid fa-xmark"></i>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-900 block leading-tight">
                                {{ \Carbon\Carbon::parse($record->tanggal)->translatedFormat('l, d F Y') }}
                            </span>
                            <span class="text-[9px] text-gray-400 block mt-0.5 uppercase tracking-wider font-semibold">
                                Sesi: {{ $record->sesi ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Status badge -->
                    <span class="text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border shrink-0
                        @if($record->status === 'hadir') bg-green-50 text-green-800 border-green-100
                        @elseif($record->status === 'izin') bg-amber-50 text-amber-800 border-amber-100
                        @elseif($record->status === 'sakit') bg-blue-50 text-blue-800 border-blue-100
                        @else bg-rose-50 text-rose-800 border-rose-100
                        @endif">
                        {{ $record->status }}
                    </span>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                    <i class="fa-solid fa-calendar-xmark text-3xl text-gray-300 mb-2"></i>
                    <p class="text-xs">Belum ada riwayat absensi.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($history->hasPages())
            <div class="pt-2">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
