@extends('layouts.admin')

@section('title', 'Edit Santri')
@section('page_title', 'Ubah Profil Santri')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Pribadi</h3>
            <p class="text-xs text-gray-500 mt-1">Ubah data identitas, kontak, dan status keaktifan santri.</p>
        </div>
        <a href="{{ route('admin.murid.show', $student->id) }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Profil
        </a>
    </div>

    <form action="{{ route('admin.murid.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Identitas Utama -->
        <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-id-card mr-1.5"></i> Identitas Utama</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap', $student->nama_lengkap) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('nama_lengkap')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Panggilan -->
            <div>
                <label for="nama_panggilan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Panggilan <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_panggilan" id="nama_panggilan" required value="{{ old('nama_panggilan', $student->nama_panggilan) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('nama_panggilan')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Jenis Kelamin -->
            <div>
                <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select name="jenis_kelamin" id="jenis_kelamin" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="L" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat Lahir -->
            <div>
                <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $student->tempat_lahir) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tempat_lahir')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $student->tanggal_lahir ? $student->tanggal_lahir->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal_lahir')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Orang Tua & Kontak -->
        <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-users-rectangle mr-1.5"></i> Orang Tua & Kontak</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Wali -->
            <div>
                <label for="nama_orang_tua" class="block text-sm font-semibold text-gray-700 mb-2">Nama Orang Tua / Wali</label>
                <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua', $student->nama_orang_tua) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('nama_orang_tua')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kontak HP -->
            <div>
                <label for="no_hp_orang_tua" class="block text-sm font-semibold text-gray-700 mb-2">No. HP Orang Tua / WhatsApp</label>
                <input type="text" name="no_hp_orang_tua" id="no_hp_orang_tua" value="{{ old('no_hp_orang_tua', $student->no_hp_orang_tua) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('no_hp_orang_tua')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Alamat -->
        <div>
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Rumah Lengkap</label>
            <textarea name="alamat" id="alamat" rows="3"
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">{{ old('alamat', $student->alamat) }}</textarea>
            @error('alamat')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Akademik & Akun -->
        <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-circle-user mr-1.5"></i> Setelan Akademik & Keaktifan</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Tanggal Masuk -->
            <div>
                <label for="tanggal_masuk" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk TPQ <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_masuk" id="tanggal_masuk" required value="{{ old('tanggal_masuk', $student->tanggal_masuk->format('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal_masuk')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div>
                <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">Status Santri <span class="text-rose-500">*</span></label>
                <select name="is_active" id="is_active" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="1" {{ old('is_active', $student->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $student->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-semibold text-gray-700 mb-2">Ubah Foto Profil</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 cursor-pointer">
                @error('foto')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-emerald-950 mb-2">Username Login <span class="text-rose-500">*</span></label>
                <input type="text" name="username" id="username" required value="{{ old('username', $student->username) }}"
                    class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                <p class="text-[10px] text-emerald-800/60 mt-1"><i class="fa-solid fa-circle-info"></i> Username untuk masuk ke akun murid.</p>
                @error('username')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-3">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
