@extends('layouts.admin')

@section('title', 'Manajemen Panduan Praktik')
@section('page_title', 'Panduan Praktik Fiqh')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Search and Filter -->
    <form action="{{ route('admin.konten.panduan.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
        <div class="relative flex-1 min-w-[240px]">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari panduan..." 
                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
        </div>

        @if(request('search'))
            <a href="{{ route('admin.konten.panduan.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">
                Reset Filter
            </a>
        @endif
    </form>

    <!-- Create Button -->
    <a href="{{ route('admin.konten.panduan.create') }}" 
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition duration-200 shadow-sm flex items-center justify-center space-x-2 text-sm">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Panduan Baru</span>
    </a>
</div>

<!-- Panduan Grid/List -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($panduans as $panduan)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition duration-200">
            <div>
                <!-- Cover Image -->
                @if($panduan->cover_image)
                    <img src="{{ $panduan->cover_image }}" alt="{{ $panduan->judul }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-emerald-800 to-emerald-950 flex flex-col items-center justify-center text-white relative">
                        <i class="fa-solid fa-compass text-4xl text-amber-400 mb-1"></i>
                        <span class="text-xs font-bold uppercase tracking-wider opacity-80">{{ $panduan->jenis_praktik }}</span>
                    </div>
                @endif

                <div class="p-5">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="bg-emerald-50 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-100 uppercase tracking-wide">
                            {{ $panduan->jenis_praktik }}
                        </span>
                        @if($panduan->status == 'published')
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded">PUBLISHED</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-[10px] font-bold px-2 py-0.5 rounded">DRAFT</span>
                        @endif
                    </div>

                    <h3 class="font-bold text-gray-900 text-base mb-1 line-clamp-1">{{ $panduan->judul }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $panduan->deskripsi }}</p>

                    <div class="flex items-center space-x-2 text-xs text-gray-600 font-semibold bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                        <i class="fa-solid fa-list-check text-emerald-600"></i>
                        <span>Jumlah Langkah: <strong>{{ $panduan->langkahs->count() }}</strong> langkah</span>
                    </div>
                </div>
            </div>

            <div class="p-5 pt-0 mt-auto flex items-center justify-between border-t border-gray-100 bg-gray-50/50">
                <span class="text-[10px] bg-gray-100 text-gray-700 font-bold px-2 py-1 rounded-lg border border-gray-200">
                    Target: {{ $panduan->levelTarget->nama ?? 'Semua Level' }}
                </span>
                
                <div class="flex space-x-1">
                    <a href="{{ route('admin.konten.panduan.show', $panduan->id) }}" 
                        class="text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-lg transition"
                        title="Detail & Langkah">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.konten.panduan.edit', $panduan->id) }}" 
                        class="text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                        title="Edit Metadata">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.konten.panduan.destroy', $panduan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus panduan ini beserta semua langkahnya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-500">
            <i class="fa-solid fa-compass text-5xl text-gray-300 mb-3"></i>
            <p class="text-sm">Tidak ada data panduan praktik ditemukan.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($panduans->hasPages())
    <div class="mt-6">
        {{ $panduans->links() }}
    </div>
@endif
@endsection
