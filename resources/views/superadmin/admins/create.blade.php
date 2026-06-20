@extends('layouts.superadmin')

@section('title', 'Tambah Pengurus')
@section('page_title', 'Tambah Pengurus Baru')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Form Akun Baru</h3>
            <p class="text-xs text-gray-500 mt-1">Buat akun akses login sistem untuk ustadz/ustadzah atau administrator baru.</p>
        </div>
        <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('superadmin.admins.store') }}" method="POST" class="p-6 space-y-6">
        @csrf

        <!-- Nama -->
        <div>
            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" id="nama" required value="{{ old('nama') }}" placeholder="Contoh: Ustadzah Fatimah"
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
            @error('nama')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="fatimah@gmail.com"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. HP -->
            <div>
                <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">No. HP / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="0812xxxxxxxx"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('no_hp')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Peran Akses <span class="text-rose-500">*</span></label>
                <select name="role" id="role" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengurus/Ustadz)</option>
                    <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin (Developer/IT)</option>
                </select>
                @error('role')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">Status Akun <span class="text-rose-500">*</span></label>
                <select name="is_active" id="is_active" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Login <span class="text-rose-500">*</span></label>
            <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
            @error('password')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-3">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                Simpan Akun
            </button>
        </div>
    </form>
</div>
@endsection
