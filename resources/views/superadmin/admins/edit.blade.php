@extends('layouts.superadmin')

@section('title', 'Edit Pengurus')
@section('page_title', 'Ubah Profil Pengurus')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <!-- General Profile Data Form -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Edit Profil</h3>
                <p class="text-xs text-gray-500 mt-1">Sesuaikan informasi kontak dan tingkat hak akses pengurus.</p>
            </div>
            <a href="{{ route('superadmin.admins.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('superadmin.admins.update', $admin->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama', $admin->nama) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('nama')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $admin->email) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No. HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $admin->no_hp) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
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
                        <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Admin (Pengurus/Ustadz)</option>
                        <option value="superadmin" {{ old('role', $admin->role) === 'superadmin' ? 'selected' : '' }}>Superadmin (Developer/IT)</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">Status Akun <span class="text-rose-500">*</span></label>
                    <select name="is_active" id="is_active" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white"
                        {{ auth()->guard('admin')->id() == $admin->id ? 'disabled' : '' }}>
                        <option value="1" {{ old('is_active', $admin->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $admin->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @if(auth()->guard('admin')->id() == $admin->id)
                        <input type="hidden" name="is_active" value="1">
                        <span class="text-[10px] text-gray-400 block mt-1"><i class="fa-solid fa-triangle-exclamation"></i> Anda tidak bisa menonaktifkan akun sendiri.</span>
                    @endif
                    @error('is_active')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Password Reset Form Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white">
            <h3 class="text-lg font-bold text-gray-800">Reset Password</h3>
            <p class="text-xs text-gray-500 mt-1">Ubah kata sandi login pengurus ini secara paksa.</p>
        </div>

        <form action="{{ route('superadmin.admins.reset-password', $admin->id) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru <span class="text-rose-500">*</span></label>
                <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
                <button type="submit"
                    class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-emerald-950 font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
