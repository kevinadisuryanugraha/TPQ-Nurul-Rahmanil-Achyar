@extends('layouts.murid')

@section('title', 'Beranda')

@section('content')
<div class="px-5 py-6 space-y-6">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
        <!-- Gold decoration -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-400 opacity-15 rounded-full blur-xl"></div>
        <div class="absolute -left-10 -top-10 w-28 h-28 bg-emerald-700 opacity-40 rounded-full blur-lg"></div>

        <div class="relative z-10 space-y-3">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-amber-400 border-2 border-emerald-700 text-emerald-950 flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($student->nama_panggilan, 0, 1)) }}
                </div>
                <div>
                    <span class="text-xs text-emerald-200 block font-medium">Assalamu'alaikum,</span>
                    <h2 class="font-extrabold text-lg text-white leading-none">{{ $student->nama_lengkap }}</h2>
                </div>
            </div>

            <div class="pt-3 border-t border-emerald-800 flex items-center justify-between">
                <div>
                    <span class="text-[9px] text-emerald-300 block uppercase tracking-wider font-semibold">Level Saat Ini</span>
                    <span class="text-sm font-bold text-amber-300">{{ $student->currentLevel->nama ?? '-' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[9px] text-emerald-300 block uppercase tracking-wider font-semibold">Tahun Ajaran</span>
                    <span class="text-xs font-semibold text-white">{{ $appSettings['tahun_ajaran'] ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Library Grid -->
    <div>
        <h3 class="font-bold text-gray-800 text-sm mb-3">Modul Belajar</h3>
        <div class="grid grid-cols-2 gap-4">
            <!-- Quran -->
            <a href="{{ route('murid.quran.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-book-quran"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Al-Qur'an</h4>
                    <span class="text-[9px] text-gray-400">114 Surah</span>
                </div>
            </a>

            <!-- Doa -->
            <a href="{{ route('murid.doa.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hands-praying"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Doa Harian</h4>
                    <span class="text-[9px] text-gray-400">Kumpulan Doa</span>
                </div>
            </a>

            <!-- Hadist -->
            <a href="{{ route('murid.hadist.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Hadist Pilihan</h4>
                    <span class="text-[9px] text-gray-400">Hadist Pendek</span>
                </div>
            </a>

            <!-- Cerita -->
            <a href="{{ route('murid.cerita.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Cerita Kisah</h4>
                    <span class="text-[9px] text-gray-400">Kisah Islami</span>
                </div>
            </a>

            <!-- Panduan -->
            <a href="{{ route('murid.panduan.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 col-span-2 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Panduan Praktik Fiqh</h4>
                    <span class="text-[9px] text-gray-400">Panduan langkah-demi-langkah wudhu & shalat</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Grid -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Attendance Stats -->
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Absensi Bulan Ini</span>
            @php
                $percent = $attendanceStats['total'] > 0 ? round(($attendanceStats['hadir'] / $attendanceStats['total']) * 100) : 100;
            @endphp
            <div class="flex items-baseline space-x-1.5 my-1">
                <span class="text-2xl font-extrabold text-emerald-800">{{ $percent }}%</span>
                <span class="text-[10px] text-gray-500">kehadiran</span>
            </div>
            <div class="text-[9px] text-gray-500 flex justify-between pt-2 border-t border-gray-50">
                <span>Hadir: <strong>{{ $attendanceStats['hadir'] }}</strong></span>
                <span>Absen: <strong class="text-rose-600">{{ $attendanceStats['alpha'] }}</strong></span>
            </div>
        </div>

        <!-- Latest Grades Summary -->
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Nilai Bacaan Terakhir</span>
            <div class="flex items-baseline space-x-1.5 my-1">
                <span class="text-2xl font-extrabold text-amber-500">{{ $latestBaca->nilai ?? '-' }}</span>
                <span class="text-[10px] text-gray-500">predikat</span>
            </div>
            <div class="text-[9px] text-gray-500 truncate pt-2 border-t border-gray-50 font-semibold text-emerald-800">
                @if($latestBaca)
                    {{ $latestBaca->surah_bacaan ?? 'Iqra Hal ' . $latestBaca->jilid_halaman }}
                @else
                    Belum ada nilai
                @endif
            </div>
        </div>
    </div>

    <!-- Announcements Slider/List -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800 text-sm">Pengumuman Terbaru</h3>
            <a href="{{ route('murid.pengumuman.index') }}" class="text-[10px] text-emerald-700 font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            @forelse($announcements as $ann)
                <div class="bg-white rounded-2xl border border-gray-150 p-4 shadow-xs relative overflow-hidden">
                    <span class="absolute top-0 left-0 w-1.5 h-full bg-emerald-700"></span>
                    <h4 class="font-bold text-xs text-gray-900 leading-snug">{{ $ann->judul }}</h4>
                    <span class="text-[8px] text-gray-400 block mt-0.5 mb-2">{{ \Carbon\Carbon::parse($ann->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                    <div class="text-[10px] text-gray-600 line-clamp-2 leading-relaxed">
                        {!! strip_tags($ann->isi) !!}
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                    <i class="fa-solid fa-bullhorn text-2xl text-gray-300 mb-2"></i>
                    <p class="text-[10px]">Belum ada pengumuman untuk Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
