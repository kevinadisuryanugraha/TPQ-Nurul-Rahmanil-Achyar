@extends('layouts.superadmin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-6">
    <!-- Stat Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card Active Admin -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-2">
                <span class="text-sm font-semibold text-gray-500 block">Pengurus Aktif</span>
                <span class="text-3xl font-bold text-gray-800 block">{{ $adminCount }}</span>
                <span class="text-xs text-emerald-600 font-semibold"><i class="fa-solid fa-circle-check"></i> Hak akses penuh</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>

        <!-- Card Active Student -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-2">
                <span class="text-sm font-semibold text-gray-500 block">Santri Aktif</span>
                <span class="text-3xl font-bold text-gray-800 block">{{ $studentCount }}</span>
                <span class="text-xs text-emerald-600 font-semibold"><i class="fa-solid fa-chart-line"></i> Total terdaftar</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-800 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

        <!-- Card Published Content -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div class="space-y-2">
                <span class="text-sm font-semibold text-gray-500 block">Konten Terbit</span>
                <span class="text-3xl font-bold text-gray-800 block">{{ $contentCount }}</span>
                <span class="text-xs text-emerald-600 font-semibold"><i class="fa-solid fa-eye"></i> Cerita & Panduan</span>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-800 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>
    </div>

    <!-- Main Grid: Logs & App Version -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Log Audit/Activity Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                <h3 class="text-lg font-bold text-gray-800">Aktivitas Terakhir Sistem</h3>
                <span class="text-xs text-emerald-800 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 font-semibold">Audit Trail</span>
            </div>
            <div class="overflow-x-auto">
                @if($activities->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>
                        <p class="text-sm">Belum ada catatan aktivitas perubahan level sistem.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                                <th class="p-4">Tanggal & Waktu</th>
                                <th class="p-4">Pengurus</th>
                                <th class="p-4">Santri</th>
                                <th class="p-4">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @foreach($activities as $log)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 whitespace-nowrap text-xs text-gray-400">
                                        {{ $log->created_at }}
                                    </td>
                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $log->admin->nama ?? 'Sistem' }}
                                    </td>
                                    <td class="p-4">
                                        {{ $log->user->nama_lengkap ?? 'N/A' }}
                                    </td>
                                    <td class="p-4">
                                        @if($log->tipe === 'awal')
                                            <span class="px-2 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-full border border-blue-100">Set Level Awal: {{ $log->level->nama }}</span>
                                        @elseif($log->tipe === 'naik')
                                            <span class="px-2 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100">Naik ke: {{ $log->level->nama }}</span>
                                        @else
                                            <span class="px-2 py-1 bg-rose-50 text-rose-800 text-xs font-bold rounded-full border border-rose-100">Turun ke: {{ $log->level->nama }}</span>
                                        @endif
                                        @if($log->catatan)
                                            <span class="text-xs text-gray-400 block mt-1">"{{ $log->catatan }}"</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Info / Version Card -->
        <div class="space-y-6">
            <!-- App Version & Engine Info -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">Informasi Sistem</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Aplikasi</span>
                        <strong class="text-gray-800 font-semibold">LMS TPQ Web App</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Platform</span>
                        <strong class="text-gray-800 font-semibold">Progressive Web App (PWA)</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Versi Rilis</span>
                        <strong class="text-amber-500 font-bold bg-amber-50 px-2 py-0.5 rounded border border-amber-100 text-xs">v1.0.0 (Draft Final)</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Laravel Framework</span>
                        <strong class="text-gray-800 font-semibold">11.x</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">PHP Engine</span>
                        <strong class="text-gray-800 font-semibold">8.3</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Action Card -->
            <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 p-6 rounded-2xl text-white shadow-sm space-y-4 relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.1),transparent)] pointer-events-none"></div>
                <h3 class="text-lg font-bold">Menu Pintasan</h3>
                <p class="text-xs text-emerald-100">Gunakan pintasan di bawah untuk mengakses manajemen konfigurasi.</p>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('superadmin.admins.create') }}" class="px-3 py-2 bg-white/10 hover:bg-white/20 transition rounded-xl text-xs font-semibold text-center flex items-center justify-center space-x-1">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>+ Pengurus</span>
                    </a>
                    <a href="{{ route('superadmin.settings') }}" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 transition rounded-xl text-xs font-semibold text-center text-emerald-950 flex items-center justify-center space-x-1">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Setelan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
