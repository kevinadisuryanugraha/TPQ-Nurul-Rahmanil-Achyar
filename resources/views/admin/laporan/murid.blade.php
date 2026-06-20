@extends('layouts.admin')

@section('title', 'Cetak Rapor Santri')
@section('page_title', 'Cetak Rapor Santri')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Search and Filter -->
    <form action="{{ route('admin.laporan.murid') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
        <div class="relative flex-1 min-w-[240px]">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari santri..." 
                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
        </div>

        <select name="level_id" onchange="this.form.submit()" 
            class="border border-gray-300 rounded-xl px-4 py-2.5 bg-white text-sm focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Level / Kelas</option>
            @foreach($levels as $lvl)
                <option value="{{ $lvl->id }}" {{ request('level_id') == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
            @endforeach
        </select>

        @if(request('search') || request('level_id'))
            <a href="{{ route('admin.laporan.murid') }}" class="text-xs text-rose-600 hover:underline font-semibold">
                Reset Filter
            </a>
        @endif
    </form>

    <a href="{{ route('admin.laporan.index') }}" 
        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl transition text-sm flex items-center space-x-1 shadow-sm border border-gray-200">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Laporan</span>
    </a>
</div>

<!-- Students List -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Level Kelas</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Rapor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($student->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm">{{ $student->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">Panggilan: {{ $student->nama_panggilan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $student->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-800">
                            {{ $student->currentLevel->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($student->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('admin.laporan.export-pdf', ['user_id' => $student->id]) }}" 
                                class="bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold px-4 py-2 rounded-xl transition text-xs border border-rose-200 inline-flex items-center space-x-1.5 shadow-sm">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>Unduh PDF</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-graduation-cap text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm">Tidak ada data santri ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
