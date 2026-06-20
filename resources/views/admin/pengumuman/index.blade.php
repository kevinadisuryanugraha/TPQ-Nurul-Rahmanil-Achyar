@extends('layouts.admin')

@section('title', 'Kirim Pengumuman')
@section('page_title', 'Kirim Pengumuman')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Search Bar -->
    <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="flex items-center gap-3 flex-1">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari pengumuman..." 
                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
        </div>

        @if(request('search'))
            <a href="{{ route('admin.pengumuman.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">
                Reset Filter
            </a>
        @endif
    </form>

    <!-- Create Button -->
    <a href="{{ route('admin.pengumuman.create') }}" 
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition duration-200 shadow-sm flex items-center justify-center space-x-2 text-sm">
        <i class="fa-solid fa-paper-plane"></i>
        <span>Buat Pengumuman Baru</span>
    </a>
</div>

<!-- Announcements Table/List -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengumuman</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Target Penerima</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal Tampil</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pengumumans as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 text-sm">{{ $item->judul }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1 mt-1">{{ strip_tags($item->isi) }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 flex items-center space-x-1">
                                <i class="fa-solid fa-user-pen"></i>
                                <span>Oleh: {{ $item->admin->nama ?? 'Admin' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->target_semua)
                                <span class="bg-emerald-50 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-100">
                                    <i class="fa-solid fa-users text-[10px] mr-1"></i>Semua Santri
                                </span>
                            @else
                                <span class="bg-amber-50 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-100">
                                    <i class="fa-solid fa-arrow-up-9-1 text-[10px] mr-1"></i>Level: {{ $item->levelTarget->nama ?? 'Unknown' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                            <div>Mulai: <strong>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</strong></div>
                            @if($item->tanggal_berakhir)
                                <div>Selesai: <strong>{{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</strong></div>
                            @else
                                <div class="text-gray-400 italic">Selamanya</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status == 'published')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-1.5 whitespace-nowrap">
                            <a href="{{ route('admin.pengumuman.edit', $item->id) }}" 
                                class="text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 p-2.5 rounded-xl transition inline-block"
                                title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 p-2.5 rounded-xl transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-bullhorn text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm">Tidak ada data pengumuman ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($pengumumans->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $pengumumans->links() }}
        </div>
    @endif
</div>
@endsection
