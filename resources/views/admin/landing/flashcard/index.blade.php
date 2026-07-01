@extends('layouts.admin')
@section('title', 'Manajemen Flashcard')
@section('page_title', 'Kelola Dek Flashcard')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-500">Kelola kumpulan kartu interaktif bawaan sistem atau kustom.</p>
        <a href="{{ route('admin.konten.flashcard.create') }}" class="px-4 py-2 bg-emerald-800 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Tambah Dek Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-[10px] text-gray-400 uppercase border-b border-gray-100 font-bold">
                    <th class="px-6 py-4">Nama Dek</th>
                    <th class="px-6 py-4">Tipe Sumber</th>
                    <th class="px-6 py-4">Rekomendasi Level</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                @foreach($decks as $deck)
                <tr>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900 block">{{ $deck->nama }}</span>
                        <span class="text-xs text-gray-400 block mt-0.5">{{ $deck->deskripsi ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($deck->source_type === 'custom')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">KUSTOM (MANUAL)</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">SISTEM (OTOMATIS)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-emerald-800">
                        {{ $deck->level->nama ?? 'Semua Level' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="w-2.5 h-2.5 rounded-full inline-block {{ $deck->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-1">
                        @if($deck->source_type === 'custom')
                            <a href="{{ route('admin.konten.flashcard.items.index', $deck->id) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition">
                                <i class="fa-solid fa-list-check mr-1"></i> Kelola Kartu
                            </a>
                        @endif
                        <a href="{{ route('admin.konten.flashcard.edit', $deck->id) }}" class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-bold transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.konten.flashcard.destroy', $deck->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dek ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-xs font-bold transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
