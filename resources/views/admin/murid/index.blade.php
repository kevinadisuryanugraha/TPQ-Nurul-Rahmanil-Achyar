@extends('layouts.admin')

@section('title', 'Data Santri')
@section('page_title', 'Manajemen Data Santri')

@section('content')
<div class="space-y-6">
    <!-- Top actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-gray-500">Kelola informasi santri, rekap absensi, status perkembangan hafalan, dan rapor.</p>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.level.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 border border-gray-200 hover:border-gray-300 font-bold rounded-xl shadow-sm text-gray-700 transition text-sm flex items-center space-x-2">
                <i class="fa-solid fa-list-check"></i>
                <span>Tingkat Level</span>
            </a>
            <a href="{{ route('admin.murid.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-sm transition text-sm flex items-center space-x-2">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Santri</span>
            </a>
        </div>
    </div>

    <!-- Filters Section Card -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.murid.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Search field -->
            <div class="sm:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Cari Santri</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama lengkap / panggilan..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                </div>
            </div>

            <!-- Level filter -->
            <div>
                <label for="level_id" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Tingkat Level</label>
                <select name="level_id" id="level_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="">Semua Level</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl->id }}" {{ request('level_id') == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status filter -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Status Aktif</label>
                <select name="status" id="status" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Student Grid/Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($students->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <i class="fa-regular fa-user text-4xl mb-3 block"></i>
                    <p class="text-sm">Tidak ditemukan data santri yang cocok.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                            <th class="p-4">Santri</th>
                            <th class="p-4">Username</th>
                            <th class="p-4">Jenis Kelamin</th>
                            <th class="p-4">Level Saat Ini</th>
                            <th class="p-4">Wali Santri</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <!-- Photo/Initials -->
                                        @if($student->foto)
                                            <img src="{{ $student->foto }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-gray-100">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($student->nama_panggilan, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-gray-800 leading-none mb-1">{{ $student->nama_lengkap }}</h4>
                                            <span class="text-xs text-gray-400">Panggilan: <strong>{{ $student->nama_panggilan }}</strong></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-xs text-gray-500">
                                    {{ $student->username }}
                                </td>
                                <td class="p-4">
                                    @if($student->jenis_kelamin === 'L')
                                        <span class="text-gray-700"><i class="fa-solid fa-mars text-blue-500 mr-1"></i> Laki-laki</span>
                                    @else
                                        <span class="text-gray-700"><i class="fa-solid fa-venus text-pink-500 mr-1"></i> Perempuan</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100">
                                        {{ $student->currentLevel->nama }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="text-xs">
                                        <p class="font-medium text-gray-800 leading-none mb-1">{{ $student->nama_orang_tua ?? '-' }}</p>
                                        <span class="text-gray-400">{{ $student->no_hp_orang_tua ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($student->is_active)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100"><i class="fa-solid fa-circle text-[8px] mr-1.5 align-middle"></i>Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-800 text-xs font-bold rounded-full border border-rose-100"><i class="fa-solid fa-circle text-[8px] mr-1.5 align-middle"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                    <!-- Detail Button -->
                                    <a href="{{ route('admin.murid.show', $student->id) }}" class="inline-block p-2 text-gray-600 hover:text-emerald-800 rounded-lg hover:bg-gray-50 transition" title="Buka Detail Profil & Evaluasi">
                                        <i class="fa-solid fa-address-card"></i>
                                    </a>
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.murid.edit', $student->id) }}" class="inline-block p-2 text-gray-600 hover:text-emerald-800 rounded-lg hover:bg-gray-50 transition" title="Edit Profil">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </a>
                                    <!-- Deactivate Button -->
                                    @if($student->is_active)
                                        <form action="{{ route('admin.murid.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun santri ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:text-rose-800 rounded-lg hover:bg-rose-50 transition" title="Nonaktifkan Santri">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
