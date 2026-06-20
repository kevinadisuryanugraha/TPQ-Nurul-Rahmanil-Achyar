@extends('layouts.admin')

@section('title', 'Tambah Santri')
@section('page_title', 'Registrasi Santri Baru')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Form Biodata Santri</h3>
            <p class="text-xs text-gray-500 mt-1">Lengkapi data pribadi dan pilih level awal santri untuk memulai pencatatan progress.</p>
        </div>
        <a href="{{ route('admin.murid.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.murid.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        <input type="hidden" name="pendaftar_id" value="{{ request('pendaftar_id') }}">

        <!-- 1. Identitas Utama -->
        <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-id-card mr-1.5"></i> Identitas Utama</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap', request('prefill_nama')) }}" placeholder="Contoh: Muhammad Ali"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('nama_lengkap')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Panggilan -->
            <div>
                <label for="nama_panggilan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Panggilan <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_panggilan" id="nama_panggilan" required value="{{ old('nama_panggilan') }}" placeholder="Contoh: Ali"
                    oninput="suggestUsername(this.value)"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
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
                    <option value="L" {{ old('jenis_kelamin', request('prefill_jenis_kelamin')) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', request('prefill_jenis_kelamin')) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat Lahir -->
            <div>
                <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', request('prefill_tempat_lahir')) }}" placeholder="Jakarta"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('tempat_lahir')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', request('prefill_tanggal_lahir')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal_lahir')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- 2. Orang Tua & Kontak -->
        <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-users-rectangle mr-1.5"></i> Orang Tua & Kontak</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Wali -->
            <div>
                <label for="nama_orang_tua" class="block text-sm font-semibold text-gray-700 mb-2">Nama Orang Tua / Wali</label>
                <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua', request('prefill_nama_ortu')) }}" placeholder="Bapak Ahmad / Ibu Siti"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('nama_orang_tua')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kontak HP -->
            <div>
                <label for="no_hp_orang_tua" class="block text-sm font-semibold text-gray-700 mb-2">No. HP Orang Tua / WhatsApp</label>
                <input type="text" name="no_hp_orang_tua" id="no_hp_orang_tua" value="{{ old('no_hp_orang_tua', request('prefill_no_hp_ortu')) }}" placeholder="0812xxxxxxxx"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                @error('no_hp_orang_tua')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Alamat -->
        <div>
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Rumah Lengkap</label>
            <textarea name="alamat" id="alamat" rows="3" placeholder="Jl. Anggrek No. 12, RT 02/03..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">{{ old('alamat', request('prefill_alamat')) }}</textarea>
            @error('alamat')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 3. Akademik & Akun -->
        <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-graduation-cap mr-1.5"></i> Setelan Akademik & Akun</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Tanggal Masuk -->
            <div>
                <label for="tanggal_masuk" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk TPQ <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_masuk" id="tanggal_masuk" required value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal_masuk')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Level Awal -->
            <div>
                <label for="current_level_id" class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Level Awal <span class="text-rose-500">*</span></label>
                <select name="current_level_id" id="current_level_id" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl->id }}" {{ old('current_level_id') == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
                    @endforeach
                </select>
                @error('current_level_id')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil (Avatar)</label>
                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp"
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 cursor-pointer">
                @error('foto')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-emerald-950 mb-2">Username Login <span class="text-rose-500">*</span></label>
                <input type="text" name="username" id="username" required value="{{ old('username') }}" placeholder="alilms"
                    class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-emerald-900/30 text-sm bg-white">
                <p class="text-[10px] text-emerald-800/60 mt-1"><i class="fa-solid fa-circle-info"></i> Gunakan huruf kecil tanpa spasi.</p>
                @error('username')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-emerald-950 mb-2">Password Wali Santri <span class="text-rose-500">*</span></label>
                <input type="text" name="password" id="password" required value="{{ old('password', 'tpq123') }}" placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-3 border border-emerald-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-emerald-900/30 text-sm bg-white">
                <p class="text-[10px] text-emerald-800/60 mt-1"><i class="fa-solid fa-circle-info"></i> Password awal bawaan yang akan diberikan ke orang tua.</p>
                @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-3">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                Daftarkan Santri
            </button>
        </div>
    </form>
</div>

<script>
    function suggestUsername(val) {
        let usernameInput = document.getElementById('username');
        if (val) {
            // strip spaces and make lowercase
            usernameInput.value = val.toLowerCase().replace(/\s+/g, '').replace(/[^a-z0-9]/g, '');
        }
    }
</script>
@endsection
