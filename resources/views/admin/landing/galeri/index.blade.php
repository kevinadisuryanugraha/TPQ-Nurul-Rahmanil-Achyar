@extends('layouts.admin')

@section('title', 'Kelola Galeri Foto')
@section('page_title', 'CMS Landing Page - Galeri Foto')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Foto Dokumentasi</h3>
            <p class="text-xs text-gray-500 mt-1">Foto-foto yang di-upload di sini akan langsung tampil pada section galeri di halaman depan.</p>
        </div>
        <a href="{{ route('admin.landing.galeri.create') }}" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition text-xs">
            <i class="fa-solid fa-plus mr-1"></i> Tambah Foto Baru
        </a>
    </div>

    <!-- Table / Grid -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($galleries->isEmpty())
            <div class="text-center py-16 p-8">
                <i class="fa-solid fa-images text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-xs text-gray-400 font-light">Belum ada foto galeri. Klik tombol di atas untuk mengunggah foto baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="bg-stone-50 border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                            <th class="p-4 w-24">Thumbnail</th>
                            <th class="p-4">Judul / Kategori</th>
                            <th class="p-4 text-center">Urutan</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($galleries as $gal)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="p-4">
                                    <img src="{{ $gal->gambar }}" alt="{{ $gal->judul ?? 'Foto' }}" class="w-16 h-12 object-cover rounded-lg border border-gray-100 shadow-sm">
                                </td>
                                <td class="p-4">
                                    <h4 class="font-bold text-emerald-950 text-sm">{{ $gal->judul ?? 'Tanpa Judul' }}</h4>
                                    <span class="inline-block px-2.5 py-0.5 mt-1 bg-emerald-50 text-[10px] text-emerald-800 font-semibold rounded-full border border-emerald-100/55 uppercase">{{ $gal->kategori ?? 'Umum' }}</span>
                                </td>
                                <td class="p-4 text-center text-gray-600 font-medium">
                                    {{ $gal->urutan ?? 99 }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($gal->is_active)
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
                                        <a href="{{ route('admin.landing.galeri.edit', $gal->id) }}" class="p-2 text-emerald-800 hover:text-emerald-950 hover:bg-emerald-50 rounded-lg transition" title="Ubah">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.landing.galeri.destroy', $gal->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto galeri ini?')">
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
