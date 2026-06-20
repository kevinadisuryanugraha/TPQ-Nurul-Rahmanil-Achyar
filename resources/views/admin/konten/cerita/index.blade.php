@extends('layouts.admin')

@section('title', 'Manajemen Cerita Kisah')
@section('page_title', 'Manajemen Cerita Kisah')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Search and Filter -->
    <form action="{{ route('admin.konten.cerita.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
        <div class="relative flex-1 min-w-[240px]">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari cerita berdasarkan judul..." 
                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
        </div>

        <select name="kategori" onchange="this.form.submit()" 
            class="border border-gray-300 rounded-xl px-4 py-2.5 bg-white text-sm focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Kategori</option>
            <option value="kisah_nabi" {{ request('kategori') == 'kisah_nabi' ? 'selected' : '' }}>Kisah Nabi</option>
            <option value="kisah_sahabat" {{ request('kategori') == 'kisah_sahabat' ? 'selected' : '' }}>Kisah Sahabat</option>
            <option value="islami_lainnya" {{ request('kategori') == 'islami_lainnya' ? 'selected' : '' }}>Islami Lainnya</option>
        </select>

        @if(request('search') || request('kategori'))
            <a href="{{ route('admin.konten.cerita.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">
                Reset Filter
            </a>
        @endif
    </form>

    <!-- Create Button -->
    <a href="{{ route('admin.konten.cerita.create') }}" 
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition duration-200 shadow-sm flex items-center justify-center space-x-2 text-sm">
        <i class="fa-solid fa-plus"></i>
        <span>Tulis Cerita Baru</span>
    </a>
</div>

<!-- Cerita Grid/List -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($ceritas as $cerita)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition duration-200">
            <div>
                <!-- Cover Image/Thumbnail -->
                @if($cerita->thumbnail)
                    <img src="{{ $cerita->thumbnail }}" alt="{{ $cerita->judul }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-emerald-800 to-emerald-950 flex flex-col items-center justify-center text-white relative">
                        <i class="fa-solid fa-feather-pointed text-4xl text-amber-400 mb-2"></i>
                        <span class="text-sm font-bold opacity-85">LMS TPQ Stories</span>
                        <div class="absolute bottom-3 left-3 flex gap-2">
                            <span class="text-[10px] bg-emerald-900/80 px-2 py-0.5 rounded text-amber-400 font-semibold border border-emerald-700">No Image</span>
                        </div>
                    </div>
                @endif

                <div class="p-6">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-100 font-bold px-2 py-1 rounded-full uppercase tracking-wider">
                            {{ str_replace('_', ' ', $cerita->kategori) }}
                        </span>
                        
                        <div class="flex items-center gap-1.5">
                            @if($cerita->status == 'published')
                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-1 rounded">PUBLISHED</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-[10px] font-bold px-2 py-1 rounded">DRAFT</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-1">{{ $cerita->judul }}</h3>
                    
                    <p class="text-sm text-gray-500 mb-4 line-clamp-3">
                        {{ strip_tags($cerita->konten) }}
                    </p>
                </div>
            </div>

            <div class="p-6 pt-0 border-t border-gray-100 mt-auto flex items-center justify-between bg-gray-50">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 bg-emerald-700 text-white rounded-full flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr($cerita->admin->nama ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-[10px] text-gray-500">
                        <p class="font-bold text-gray-800 leading-none mb-0.5">{{ $cerita->admin->nama ?? 'Admin' }}</p>
                        <p class="leading-none">{{ $cerita->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex space-x-1.5">
                    <span class="text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-1.5 rounded-lg border border-gray-200">
                        Target: {{ $cerita->levelTarget->nama ?? 'Semua Level' }}
                    </span>
                    <a href="{{ route('admin.konten.cerita.edit', $cerita->id) }}" 
                        class="text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                        title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.konten.cerita.destroy', $cerita->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cerita ini?')">
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
            <i class="fa-solid fa-feather-pointed text-5xl text-gray-300 mb-3"></i>
            <p class="text-sm">Tidak ada data cerita kisah ditemukan.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($ceritas->hasPages())
    <div class="mt-6">
        {{ $ceritas->links() }}
    </div>
@endif
@endsection
