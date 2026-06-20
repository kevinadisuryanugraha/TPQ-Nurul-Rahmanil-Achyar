@extends('layouts.admin')

@section('title', 'Tingkat Level')
@section('page_title', 'Tingkat Level Pembelajaran')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Tingkat kelas/jenjang kurikulum terstruktur dari Pra-Iqra hingga Al-Qur'an.</p>
        <a href="{{ route('admin.murid.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Santri
        </a>
    </div>

    <!-- Levels Card Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                        <th class="p-4 w-20">Urutan</th>
                        <th class="p-4">Nama Level</th>
                        <th class="p-4">Deskripsi Tingkatan</th>
                        <th class="p-4 text-center">Jumlah Santri Aktif</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach($levels as $lvl)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 font-mono font-bold text-gray-400">
                                Lvl {{ $lvl->urutan }}
                            </td>
                            <td class="p-4 font-bold text-emerald-800">
                                {{ $lvl->nama }}
                            </td>
                            <td class="p-4 text-gray-500 text-xs">
                                {{ $lvl->deskripsi ?? 'Belum ada deskripsi.' }}
                            </td>
                            <td class="p-4 text-center font-extrabold text-gray-800">
                                {{ $lvl->users_count }} <span class="text-xs font-normal text-gray-400">santri</span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.murid.index', ['level_id' => $lvl->id]) }}" class="inline-block px-3 py-1.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 hover:border-gray-300 rounded-lg text-xs font-bold text-emerald-800 transition">
                                    Lihat Santri <i class="fa-solid fa-arrow-right-long ml-1 align-middle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
