@extends('layouts.admin')

@section('title', 'Riwayat Absensi')
@section('page_title', 'Jurnal Riwayat Absensi')

@section('content')
<div class="space-y-6">
    <!-- Top Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-gray-500">Kelola koreksi absensi harian dan rekap persentase kehadiran bulanan santri.</p>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.absensi.rekap') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 border border-gray-200 hover:border-gray-300 font-bold rounded-xl shadow-sm text-gray-700 transition text-sm flex items-center space-x-2">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Rekap Bulanan</span>
            </a>
            <a href="{{ route('admin.absensi.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-sm transition text-sm flex items-center space-x-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>Input Absensi</span>
            </a>
        </div>
    </div>

    <!-- Filters Section Card -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.absensi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Filter Santri -->
            <div>
                <label for="user_id" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Santri</label>
                <select name="user_id" id="user_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="">Semua Santri</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ request('user_id') == $st->id ? 'selected' : '' }}>{{ $st->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div>
                <label for="tanggal" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
            </div>

            <!-- Filter Sesi -->
            <div>
                <label for="sesi" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Sesi</label>
                <select name="sesi" id="sesi" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="">Semua Sesi</option>
                    @foreach($appSettings['sesi'] as $s)
                        <option value="{{ $s }}" {{ request('sesi') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Status Kehadiran</label>
                <select name="status" id="status" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ request('status') === 'alpha' ? 'selected' : '' }}>Alpha (Alpa)</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Log Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($records->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <i class="fa-regular fa-clipboard text-4xl mb-3 block"></i>
                    <p class="text-sm">Tidak ditemukan riwayat catatan absensi.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                            <th class="p-4">Tanggal & Sesi</th>
                            <th class="p-4">Nama Santri</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Catatan / Alasan</th>
                            <th class="p-4">Petugas</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($records as $rec)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $rec->tanggal->format('d M Y') }}</div>
                                    <span class="text-xs text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 font-semibold">{{ $rec->sesi }}</span>
                                </td>
                                <td class="p-4 font-semibold text-gray-800">
                                    {{ $rec->user->nama_lengkap }}
                                </td>
                                <td class="p-4">
                                    @if($rec->status === 'hadir')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100">Hadir</span>
                                    @elseif($rec->status === 'izin')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-full border border-blue-100">Izin</span>
                                    @elseif($rec->status === 'sakit')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-full border border-amber-100">Sakit</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-800 text-xs font-bold rounded-full border border-rose-100">Alpha</span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-500 text-xs max-w-xs truncate">
                                    {{ $rec->catatan ?? '-' }}
                                </td>
                                <td class="p-4 text-xs text-gray-400">
                                    {{ $rec->admin->nama ?? '-' }}
                                </td>
                                <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.absensi.edit', $rec->id) }}" class="inline-block p-2 text-gray-600 hover:text-emerald-800 rounded-lg hover:bg-gray-50 transition" title="Koreksi Absensi">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.absensi.destroy', $rec->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan absensi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:text-rose-800 rounded-lg hover:bg-rose-50 transition" title="Hapus Catatan">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pagination -->
        @if($records->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
