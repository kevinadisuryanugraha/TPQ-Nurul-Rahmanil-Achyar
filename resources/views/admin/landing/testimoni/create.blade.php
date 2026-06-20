@extends('layouts.admin')

@section('title', 'Tambah Testimoni')
@section('page_title', 'CMS Landing Page - Tambah Testimoni')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Form Testimoni Baru</h3>
            <p class="text-xs text-gray-500 mt-1">Masukkan data ulasan wali santri untuk ditampilkan ke publik.</p>
        </div>
        <a href="{{ route('admin.landing.testimoni.index') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.landing.testimoni.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nama -->
            <div>
                <label for="nama" class="block text-xs font-bold text-gray-700 mb-2">Nama Pemberi Ulasan <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama') }}" placeholder="Contoh: Ibu Fatimah"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('nama')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-xs font-bold text-gray-700 mb-2">Keterangan / Peran <span class="text-rose-500">*</span></label>
                <input type="text" name="role" id="role" required value="{{ old('role') }}" placeholder="Contoh: Orang Tua dari Ahmad (Iqra 4)"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('role')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Rating -->
            <div>
                <label for="rating" class="block text-xs font-bold text-gray-700 mb-2">Rating Bintang <span class="text-rose-500">*</span></label>
                <select name="rating" id="rating" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs bg-white">
                    <option value="5" {{ old('rating', '5') == '5' ? 'selected' : '' }}>5 Bintang (Sangat Puas)</option>
                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Bintang (Puas)</option>
                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Bintang (Cukup)</option>
                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                </select>
                @error('rating')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-xs font-bold text-gray-700 mb-2">Foto Profil / Avatar (Opsional)</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp,image/jpg"
                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 cursor-pointer">
                <span class="text-[9px] text-gray-400 mt-1.5 block">File gambar, max 2MB. Di-resize otomatis ke 200x200px.</span>
                @error('foto')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Isi Testimoni -->
        <div>
            <label for="isi" class="block text-xs font-bold text-gray-700 mb-2">Isi Testimoni <span class="text-rose-500">*</span></label>
            <textarea name="isi" id="isi" rows="4" required placeholder="Tuliskan ulasan wali santri secara lengkap (Maksimal 500 karakter)..."
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('isi') }}</textarea>
            @error('isi')
                <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Urutan -->
            <div>
                <label for="urutan" class="block text-xs font-bold text-gray-700 mb-2">Urutan Tampil</label>
                <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" min="1"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('urutan')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="block text-xs font-bold text-gray-700 mb-2">Status Tampil</label>
                <select name="is_active" id="is_active" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs bg-white">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif (Tampilkan)</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                </select>
                @error('is_active')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-xs">
                Simpan Testimoni
            </button>
        </div>
    </form>
</div>
@endsection
