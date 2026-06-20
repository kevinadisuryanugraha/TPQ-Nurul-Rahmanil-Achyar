@extends('layouts.admin')

@section('title', 'Dashboard Pengurus')
@section('page_title', 'Ringkasan Aktivitas Hari Ini')

@section('content')
<div class="space-y-6">
    <!-- Grid Utama Atas (Hari Ini Widget) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Present Today Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-2">
                <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 uppercase tracking-wider">Hadir Hari Ini</span>
                <span class="text-3xl font-extrabold text-gray-800 block">{{ $presentToday }} <span class="text-sm font-medium text-gray-400">/ {{ $totalSantri }} santri</span></span>
                <a href="{{ route('admin.absensi.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold block hover:underline">Lihat absensi &rarr;</a>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
        </div>

        <!-- Missing Attendance Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-2">
                <span class="text-xs font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 uppercase tracking-wider">Belum Diabsen</span>
                <span class="text-3xl font-extrabold text-gray-800 block">{{ $absentNotInputToday }} <span class="text-sm font-medium text-gray-400">santri</span></span>
                <a href="{{ route('admin.absensi.create') }}" class="text-xs text-amber-600 hover:text-amber-700 font-semibold block hover:underline">Input absensi sesi ini &rarr;</a>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-800 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-slash"></i>
            </div>
        </div>

        <!-- Total Santri Card -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-2">
                <span class="text-xs font-bold text-indigo-800 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 uppercase tracking-wider">Total Santri Aktif</span>
                <span class="text-3xl font-extrabold text-gray-800 block">{{ $totalSantri }} <span class="text-sm font-medium text-gray-400">santri</span></span>
                <a href="{{ route('admin.murid.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold block hover:underline">Kelola santri &rarr;</a>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-800 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>
    </div>

    <!-- Grid Tengah (Reminder vs Pengumuman) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Reminder Penilaian (2/3 width) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Reminder Penilaian</h3>
                    <p class="text-xs text-gray-400 mt-1">Daftar santri yang belum menerima penilaian dalam 7 hari terakhir.</p>
                </div>
                <a href="{{ route('admin.penilaian.index') }}" class="text-xs font-semibold text-emerald-800 hover:underline">
                    Buka Penilaian
                </a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @if($reminderSantri->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-4xl mb-3 block"></i>
                        <p class="text-sm">Hebat! Semua santri sudah dinilai dalam 7 hari terakhir.</p>
                    </div>
                @else
                    @foreach($reminderSantri as $santri)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($santri->nama_panggilan, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $santri->nama_lengkap }}</h4>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Level: {{ $santri->currentLevel->nama }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.penilaian.baca', ['user_id' => $santri->id]) }}" class="px-2.5 py-1.5 bg-emerald-800 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                    <i class="fa-solid fa-plus mr-1"></i> Nilai
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Latest Announcement (1/3 width) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">Pengumuman Terkini</h3>
                    <i class="fa-solid fa-bullhorn text-emerald-700"></i>
                </div>

                @if(!$latestAnnouncement)
                    <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                        <p class="text-xs">Tidak ada pengumuman aktif hari ini.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        <h4 class="text-sm font-bold text-gray-800 leading-snug">{{ $latestAnnouncement->judul }}</h4>
                        <span class="text-[10px] text-gray-400 block"><i class="fa-regular fa-calendar mr-1"></i> Mulai: {{ $latestAnnouncement->tanggal_mulai->format('d M Y') }}</span>
                        <p class="text-xs text-gray-600 line-clamp-4 leading-relaxed mt-2">{{ $latestAnnouncement->isi }}</p>
                    </div>
                @endif
            </div>

            <div class="pt-6 border-t border-gray-100 mt-6">
                <a href="{{ route('admin.pengumuman.create') }}" class="w-full py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 hover:border-gray-300 transition text-emerald-800 font-bold rounded-xl text-xs text-center block shadow-sm">
                    Buat Pengumuman Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Bawah (Santri per Level Breakdown) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white">
            <h3 class="text-lg font-bold text-gray-800">Distribusi Santri Per Jenjang Level</h3>
            <p class="text-xs text-gray-400 mt-1">Jumlah santri aktif di setiap tingkat kelas pembelajaran.</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach($levels as $level)
                    <div class="p-4 rounded-xl border border-gray-100 text-center space-y-2 bg-gray-50/30 hover:border-emerald-200 transition">
                        <span class="text-xs font-bold text-gray-400 block tracking-wide uppercase">Lvl {{ $level->urutan }}</span>
                        <h4 class="text-sm font-bold text-emerald-800 truncate" title="{{ $level->nama }}">{{ $level->nama }}</h4>
                        <span class="text-2xl font-extrabold text-gray-800 block">{{ $level->users_count }}</span>
                        <span class="text-[10px] text-gray-400 block">santri</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
