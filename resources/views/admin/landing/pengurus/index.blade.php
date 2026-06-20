@extends('layouts.admin')

@section('title', 'Struktur Pengurus')
@section('page_title', 'CMS Landing Page - Struktur Pengurus')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Pengurus & Pengajar</h3>
            <p class="text-xs text-gray-500 mt-1">Kelola data profil Ustadz / Ustadzah yang akan ditampilkan di section Struktur Pengurus.</p>
        </div>
        <a href="{{ route('admin.landing.pengurus.create') }}" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition text-xs">
            <i class="fa-solid fa-plus mr-1"></i> Tambah Pengurus Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($pengurusList->isEmpty())
            <div class="text-center py-16 p-8">
                <i class="fa-solid fa-users-gear text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-xs text-gray-400 font-light">Belum ada pengurus terdaftar. Klik tombol di atas untuk menambah profil pengurus.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="bg-stone-50 border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                            <th class="p-4 w-12 text-center">Foto</th>
                            <th class="p-4">Nama Lengkap</th>
                            <th class="p-4">Jabatan / Peran</th>
                            <th class="p-4 text-center">Urutan</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengurusList as $peng)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="p-4 text-center">
                                    @if($peng->foto)
                                        <img src="{{ $peng->foto }}" alt="{{ $peng->nama }}" class="w-10 h-10 rounded-full object-cover border border-gray-100 shadow-sm mx-auto">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-xs shadow-inner mx-auto">
                                            {{ strtoupper(substr($peng->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <h4 class="font-bold text-emerald-950 text-sm">{{ $peng->nama }}</h4>
                                </td>
                                <td class="p-4">
                                    <span class="text-xs text-amber-700 font-semibold uppercase tracking-wider">{{ $peng->jabatan }}</span>
                                </td>
                                <td class="p-4 text-center text-gray-600 font-semibold">
                                    {{ $peng->urutan ?? 99 }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($peng->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600 border border-stone-200">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.landing.pengurus.edit', $peng->id) }}" class="p-2 text-emerald-800 hover:text-emerald-950 hover:bg-emerald-50 rounded-lg transition" title="Ubah">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.landing.pengurus.destroy', $peng->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil pengurus ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
