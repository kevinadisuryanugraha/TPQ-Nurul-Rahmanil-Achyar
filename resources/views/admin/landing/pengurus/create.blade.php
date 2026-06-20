@extends('layouts.admin')

@section('title', 'Tambah Pengurus / Pengajar')
@section('page_title', 'CMS Landing Page - Tambah Pengurus')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Form Pengurus Baru</h3>
            <p class="text-xs text-gray-500 mt-1">Lengkapi data ustadz / ustadzah pengajar TPQ.</p>
        </div>
        <a href="{{ route('admin.landing.pengurus.index') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.landing.pengurus.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nama -->
            <div>
                <label for="nama" class="block text-xs font-bold text-gray-700 mb-2">Nama Lengkap + Gelar <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama') }}" placeholder="Contoh: Ustadz Ahmad, S.Pd.I"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('nama')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan -->
            <div>
                <label for="jabatan" class="block text-xs font-bold text-gray-700 mb-2">Jabatan / Posisi <span class="text-rose-500">*</span></label>
                <input type="text" name="jabatan" id="jabatan" required value="{{ old('jabatan') }}" placeholder="Contoh: Kepala Madrasah, Ustadzah Tahfidz"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                @error('jabatan')
                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Foto -->
        <div>
            <label for="foto" class="block text-xs font-bold text-gray-700 mb-2">Foto Profil (Avatar) (Opsional)</label>
            <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp,image/jpg"
                   class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 cursor-pointer">
            <span class="text-[9px] text-gray-400 mt-1.5 block">File gambar, max 2MB. Di-resize otomatis ke format square 300x300px.</span>
            @error('foto')
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
                Simpan Profil
            </button>
        </div>
    </form>
</div>
@endsection
