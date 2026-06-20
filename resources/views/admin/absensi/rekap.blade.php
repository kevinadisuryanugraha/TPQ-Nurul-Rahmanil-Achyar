@extends('layouts.admin')

@section('title', 'Rekap Absensi')
@section('page_title', 'Rekapitulasi Absensi Bulanan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-gray-500">Evaluasi ringkasan tingkat kehadiran kumulatif santri dalam sebulan.</p>
        <a href="{{ route('admin.absensi.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <!-- Month & Year Picker Card -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.absensi.rekap') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <!-- Month selection -->
            <div>
                <label for="month" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Pilih Bulan</label>
                <select name="month" id="month" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Year selection -->
            <div>
                <label for="year" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Pilih Tahun</label>
                <select name="year" id="year" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <!-- Rekap Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if(empty($attendanceData))
                <div class="p-12 text-center text-gray-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 block"></i>
                    <p class="text-sm">Belum ada data santri aktif terdaftar.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                            <th class="p-4">Nama Santri</th>
                            <th class="p-4 text-center">Hadir</th>
                            <th class="p-4 text-center">Izin</th>
                            <th class="p-4 text-center">Sakit</th>
                            <th class="p-4 text-center">Alpha</th>
                            <th class="p-4 text-center">Total Sesi</th>
                            <th class="p-4 w-[25%]">Tingkat Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($attendanceData as $data)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $data['student']->nama_lengkap }}</div>
                                    <span class="text-xs text-gray-400">Level: {{ $data['student']->currentLevel->nama }}</span>
                                </td>
                                <td class="p-4 text-center text-emerald-800 font-bold">
                                    {{ $data['hadir'] }}
                                </td>
                                <td class="p-4 text-center text-blue-800">
                                    {{ $data['izin'] }}
                                </td>
                                <td class="p-4 text-center text-amber-800">
                                    {{ $data['sakit'] }}
                                </td>
                                <td class="p-4 text-center text-rose-800">
                                    {{ $data['alpha'] }}
                                </td>
                                <td class="p-4 text-center font-bold text-gray-800">
                                    {{ $data['total'] }}
                                </td>
                                <td class="p-4">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-semibold text-emerald-700">{{ $data['hadir_percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 h-full rounded-full" 
                                                style="width: {{ $data['hadir_percent'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
