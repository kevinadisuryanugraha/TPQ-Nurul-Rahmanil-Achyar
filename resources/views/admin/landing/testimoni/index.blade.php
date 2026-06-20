@extends('layouts.admin')

@section('title', 'Kelola Testimoni')
@section('page_title', 'CMS Landing Page - Testimoni Wali Santri')

@section('content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Testimoni</h3>
            <p class="text-xs text-gray-500 mt-1">Ulasan positif dari orang tua atau wali murid yang dipasang di halaman beranda.</p>
        </div>
        <a href="{{ route('admin.landing.testimoni.create') }}" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition text-xs">
            <i class="fa-solid fa-plus mr-1"></i> Tambah Testimoni Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($testimonials->isEmpty())
            <div class="text-center py-16 p-8">
                <i class="fa-solid fa-comments text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-xs text-gray-400 font-light">Belum ada testimoni. Klik tombol di atas untuk menambah testimoni pertama.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="bg-stone-50 border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                            <th class="p-4 w-12 text-center">Foto</th>
                            <th class="p-4">Nama / Wali</th>
                            <th class="p-4">Isi Testimoni</th>
                            <th class="p-4 text-center">Rating</th>
                            <th class="p-4 text-center">Urutan</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($testimonials as $test)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="p-4 text-center">
                                    @if($test->foto)
                                        <img src="{{ $test->foto }}" alt="{{ $test->nama }}" class="w-10 h-10 rounded-full object-cover border border-gray-100 shadow-sm mx-auto">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-xs shadow-inner mx-auto">
                                            {{ strtoupper(substr($test->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <h4 class="font-bold text-emerald-950 text-sm">{{ $test->nama }}</h4>
                                    <span class="text-[10px] text-gray-400 font-semibold block mt-0.5">{{ $test->role }}</span>
                                </td>
                                <td class="p-4 max-w-sm">
                                    <p class="text-gray-600 line-clamp-2 leading-relaxed">{{ $test->isi }}</p>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center text-amber-500 space-x-0.5">
                                        @for($i = 1; $i <= ($test->rating ?? 5); $i++)
                                            <i class="fa-solid fa-star text-[10px]"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td class="p-4 text-center text-gray-600 font-semibold">
                                    {{ $test->urutan ?? 99 }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($test->is_active)
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
                                        <a href="{{ route('admin.landing.testimoni.edit', $test->id) }}" class="p-2 text-emerald-800 hover:text-emerald-950 hover:bg-emerald-50 rounded-lg transition" title="Ubah">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.landing.testimoni.destroy', $test->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')">
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
