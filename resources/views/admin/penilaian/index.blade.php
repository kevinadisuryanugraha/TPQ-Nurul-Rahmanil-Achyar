@extends('layouts.admin')

@section('title', 'Penilaian Santri')
@section('page_title', 'Dashboard Penilaian Santri')

@section('content')
<div class="space-y-6">
    <p class="text-sm text-gray-500">Evaluasi perkembangan santri di 4 bidang utama: membaca Al-Qur'an/Iqra, menghafal, menulis huruf Arab, dan praktik ibadah.</p>

    <!-- 4 Domains Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Bacaan -->
        <a href="{{ route('admin.penilaian.baca') }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-emerald-200 transition group h-40">
            <div class="space-y-2">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-lg group-hover:bg-emerald-800 group-hover:text-white transition"><i class="fa-solid fa-book-open-reader"></i></span>
                <h3 class="text-base font-bold text-gray-800 pt-2">1. Bacaan</h3>
                <p class="text-xs text-gray-400 leading-snug">Progress bacaan Iqra & Tadarus Al-Qur'an.</p>
            </div>
        </a>

        <!-- Hafalan -->
        <a href="{{ route('admin.penilaian.hafalan') }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-emerald-200 transition group h-40">
            <div class="space-y-2">
                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center text-lg group-hover:bg-amber-500 group-hover:text-emerald-950 transition"><i class="fa-solid fa-brain"></i></span>
                <h3 class="text-base font-bold text-gray-800 pt-2">2. Hafalan</h3>
                <p class="text-xs text-gray-400 leading-snug">Checklist setoran surat pendek, doa, & hadits.</p>
            </div>
        </a>

        <!-- Menulis -->
        <a href="{{ route('admin.penilaian.tulis') }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-emerald-200 transition group h-40">
            <div class="space-y-2">
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center text-lg group-hover:bg-blue-600 group-hover:text-white transition"><i class="fa-solid fa-pen-nib"></i></span>
                <h3 class="text-base font-bold text-gray-800 pt-2">3. Menulis Arab</h3>
                <p class="text-xs text-gray-400 leading-snug">Evaluasi penulisan huruf & kaligrafi.</p>
            </div>
        </a>

        <!-- Praktik -->
        <a href="{{ route('admin.penilaian.praktik') }}" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-emerald-200 transition group h-40">
            <div class="space-y-2">
                <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-800 flex items-center justify-center text-lg group-hover:bg-indigo-800 group-hover:text-white transition"><i class="fa-solid fa-compress"></i></span>
                <h3 class="text-base font-bold text-gray-800 pt-2">4. Praktik Ibadah</h3>
                <p class="text-xs text-gray-400 leading-snug">Ujian tata cara Wudhu, Sholat, & Tayamum.</p>
            </div>
        </a>
    </div>

    <!-- Latest Evaluations Log Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Catatan Penilaian Terkini</h3>
        </div>

        <div class="overflow-x-auto">
            @if($allLogs->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>
                    <p class="text-sm">Belum ada catatan evaluasi baru diinput hari ini.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                            <th class="p-4">Tanggal Penilaian</th>
                            <th class="p-4">Nama Santri</th>
                            <th class="p-4">Bidang/Domain</th>
                            <th class="p-4">Rincian Evaluasi</th>
                            <th class="p-4">Ustadz/Ustadzah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($allLogs as $log)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $log->tanggal->format('d M Y') }}
                                </td>
                                <td class="p-4 font-bold text-gray-800">
                                    {{ $log->user->nama_lengkap ?? 'N/A' }}
                                </td>
                                <td class="p-4">
                                    @if($log->type_label === 'Bacaan')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100">Bacaan</span>
                                    @elseif($log->type_label === 'Hafalan')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-full border border-amber-100">Hafalan</span>
                                    @elseif($log->type_label === 'Menulis')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-full border border-blue-100">Menulis</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-800 text-xs font-bold rounded-full border border-indigo-100">Praktik</span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-600 max-w-sm truncate" title="{{ $log->desc }}">
                                    {{ $log->desc }}
                                </td>
                                <td class="p-4 text-xs text-gray-400">
                                    {{ $log->admin->nama ?? '-' }}
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
