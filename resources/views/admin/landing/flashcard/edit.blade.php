@extends('layouts.admin')
@section('title', 'Edit Dek Flashcard')
@section('content')
<div class="max-w-2xl bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="h-1.5 bg-emerald-800"></div>
    <form action="{{ route('admin.konten.flashcard.update', $deck->id) }}" method="POST" class="p-8 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Dek</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $deck->nama) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800">
        </div>
        <div>
            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
            <input type="text" name="deskripsi" id="deskripsi" value="{{ old('deskripsi', $deck->deskripsi) }}" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-400 mb-2">Sumber Data (Tidak bisa diubah)</label>
            <input type="text" disabled value="{{ strtoupper($deck->source_type) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-400 focus:outline-none">
        </div>
        <div>
            <label for="level_target_id" class="block text-sm font-bold text-gray-700 mb-2">Rekomendasi Level Santri</label>
            <select name="level_target_id" id="level_target_id" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                <option value="">Semua Level</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ $deck->level_target_id == $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="is_active" class="block text-sm font-bold text-gray-700 mb-2">Status Aktif</label>
            <select name="is_active" id="is_active" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                <option value="1" {{ $deck->is_active ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$deck->is_active ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
            <a href="{{ route('admin.konten.flashcard.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700 transition">Batal</a>
            <button type="submit" class="px-5 py-3 bg-emerald-800 hover:bg-emerald-700 rounded-xl text-xs font-bold text-white transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
