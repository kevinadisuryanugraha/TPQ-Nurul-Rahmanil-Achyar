@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan Aplikasi')

@section('content')
<div class="max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white">
        <h3 class="text-lg font-bold text-gray-800">Ubah Profil & Setelan TPQ</h3>
        <p class="text-xs text-gray-500 mt-1">Konfigurasi ini akan berdampak pada nama, logo header, manifest PWA, laporan ekspor, dan sesi absensi.</p>
    </div>

    <form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left inputs -->
            <div class="md:col-span-2 space-y-6">
                <!-- Nama TPQ -->
                <div>
                    <label for="nama_tpq" class="block text-sm font-semibold text-gray-700 mb-2">Nama TPQ <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_tpq" id="nama_tpq" required value="{{ old('nama_tpq', $settings['nama_tpq'] ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('nama_tpq')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Ajaran Aktif -->
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran Aktif <span class="text-rose-500">*</span></label>
                    <input type="text" name="tahun_ajaran" id="tahun_ajaran" required placeholder="Contoh: 2025/2026"
                        value="{{ old('tahun_ajaran', $settings['tahun_ajaran'] ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('tahun_ajaran')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sesi TPQ -->
                <div>
                    <label for="sesi" class="block text-sm font-semibold text-gray-700 mb-2">Daftar Sesi TPQ <span class="text-rose-500">*</span></label>
                    <input type="text" name="sesi" id="sesi" required placeholder="Pagi, Sore, Malam"
                        value="{{ old('sesi', implode(', ', $settings['sesi'] ?? [])) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    <p class="text-[10px] text-gray-400 mt-1.5"><i class="fa-solid fa-circle-info"></i> Pisahkan dengan koma jika ada lebih dari satu sesi (Contoh: Pagi, Sore, Malam).</p>
                    @error('sesi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi TPQ -->
                <div>
                    <label for="deskripsi_tpq" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Visi Misi TPQ</label>
                    <textarea name="deskripsi_tpq" id="deskripsi_tpq" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">{{ old('deskripsi_tpq', $settings['deskripsi_tpq'] ?? '') }}</textarea>
                    @error('deskripsi_tpq')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Logo Upload Panel -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Logo TPQ</label>
                    <div class="border border-dashed border-gray-200 rounded-2xl p-6 text-center space-y-4 bg-gray-50/50">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden mx-auto bg-white border border-gray-100 flex items-center justify-center shadow-sm">
                            @if(!empty($settings['logo_tpq']) && $settings['logo_tpq'] !== '/images/logo-default.png')
                                <img src="{{ $settings['logo_tpq'] }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl text-emerald-800"><i class="fa-solid fa-mosque"></i></span>
                            @endif
                        </div>
                        <div class="space-y-2">
                            <label for="logo" class="inline-block px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 transition rounded-lg text-xs font-semibold text-gray-700 cursor-pointer shadow-sm">
                                Pilih Gambar
                            </label>
                            <input type="file" name="logo" id="logo" class="hidden" accept="image/png,image/svg+xml,image/webp,image/jpeg">
                            <span class="text-[10px] text-gray-400 block">PNG, SVG, WEBP atau JPG, Maksimal 2MB.</span>
                        </div>
                    </div>
                    @error('logo')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-3">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                Simpan Setelan
            </button>
        </div>
    </form>
</div>
@endsection
