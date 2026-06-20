@extends('layouts.admin')

@section('title', 'Ubah Foto Galeri')
@section('page_title', 'CMS Landing Page - Ubah Foto')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Form Ubah Foto</h3>
            <p class="text-xs text-gray-500 mt-1">Ubah metadata foto atau ganti berkas foto dokumentasi.</p>
        </div>
        <a href="{{ route('admin.landing.galeri.index') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.landing.galeri.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Current Image Preview -->
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-2">Foto Saat Ini</label>
            <img src="{{ $gallery->gambar }}" alt="{{ $gallery->judul ?? 'Gallery Image' }}" class="w-48 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
        </div>

        <!-- Ganti Gambar -->
        <div>
            <label for="gambar" class="block text-xs font-bold text-gray-700 mb-2">Ganti File Foto (Kosongkan jika tidak diubah)</label>
            <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/webp,image/jpg"
                   class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 cursor-pointer">
            <span class="text-[9px] text-gray-400 mt-1.5 block"><i class="fa-solid fa-circle-info"></i> Maksimal ukuran file 2MB (Format: JPG, JPEG, PNG, WEBP). Foto akan dikompresi otomatis.</span>
            @error('gambar')
                <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Judul -->
            <div>
                <label for="judul" class="block text-xs font-bold text-gray-700 mb-2">Judul / Caption (Opsional)</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $gallery->judul) }}" placeholder="Contoh: Belajar Quran Level Iqra 3"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('judul')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="kategori" class="block text-xs font-bold text-gray-700 mb-2">Kategori (Opsional)</label>
                <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $gallery->kategori) }}" placeholder="Contoh: Kegiatan Kelas, Tahfidz, Ibadah"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('kategori')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Urutan -->
            <div>
                <label for="urutan" class="block text-xs font-bold text-gray-700 mb-2">No. Urut Urutan Tampil</label>
                <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $gallery->urutan) }}" min="1"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                <span class="text-[9px] text-gray-400 mt-1 block">Urutan lebih kecil akan tampil di posisi awal.</span>
                @error('urutan')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="block text-xs font-bold text-gray-700 mb-2">Status Tampil</label>
                <select name="is_active" id="is_active" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs bg-white">
                    <option value="1" {{ old('is_active', $gallery->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif (Tampilkan)</option>
                    <option value="0" {{ old('is_active', $gallery->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                </select>
                @error('is_active')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-xs">
                Perbarui Foto
            </button>
        </div>
    </form>
</div>
@endsection
