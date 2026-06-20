@extends('layouts.admin')

@section('title', 'Koreksi Absensi')
@section('page_title', 'Koreksi Catatan Kehadiran')

@section('content')
<div class="max-w-xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Absensi</h3>
            <p class="text-xs text-gray-500 mt-1">Sesuaikan status kehadiran santri untuk tanggal dan sesi yang dipilih.</p>
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Batal
        </a>
    </div>

    <form action="{{ route('admin.absensi.update', $record->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Details info -->
        <div class="bg-gray-50 p-4 rounded-xl space-y-2 text-sm border border-gray-100">
            <div class="flex justify-between">
                <span class="text-gray-400">Santri:</span>
                <strong class="text-gray-800">{{ $record->user->nama_lengkap }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Tanggal:</span>
                <strong class="text-gray-800">{{ $record->tanggal->format('d M Y') }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Sesi Sesi:</span>
                <strong class="text-gray-800 bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded border border-emerald-100 text-xs">{{ $record->sesi }}</strong>
            </div>
        </div>

        <!-- Status Radio -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">Status Kehadiran <span class="text-rose-500">*</span></label>
            <div class="grid grid-cols-4 gap-3">
                <!-- Hadir -->
                <label class="border border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:bg-emerald-50/20 hover:border-emerald-500 transition block relative">
                    <input type="radio" name="status" value="hadir" {{ $record->status === 'hadir' ? 'checked' : '' }}
                        class="text-emerald-600 focus:ring-emerald-500">
                    <span class="block text-xs font-bold text-gray-700 mt-1">Hadir</span>
                </label>

                <!-- Izin -->
                <label class="border border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:bg-blue-50/20 hover:border-blue-500 transition block relative">
                    <input type="radio" name="status" value="izin" {{ $record->status === 'izin' ? 'checked' : '' }}
                        class="text-blue-600 focus:ring-blue-500">
                    <span class="block text-xs font-bold text-gray-700 mt-1">Izin</span>
                </label>

                <!-- Sakit -->
                <label class="border border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:bg-amber-50/20 hover:border-amber-500 transition block relative">
                    <input type="radio" name="status" value="sakit" {{ $record->status === 'sakit' ? 'checked' : '' }}
                        class="text-amber-600 focus:ring-amber-500">
                    <span class="block text-xs font-bold text-gray-700 mt-1">Sakit</span>
                </label>

                <!-- Alpha -->
                <label class="border border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:bg-rose-50/20 hover:border-rose-500 transition block relative">
                    <input type="radio" name="status" value="alpha" {{ $record->status === 'alpha' ? 'checked' : '' }}
                        class="text-rose-600 focus:ring-rose-500">
                    <span class="block text-xs font-bold text-gray-700 mt-1">Alpha</span>
                </label>
            </div>
            @error('status')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div>
            <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan / Keterangan Khusus</label>
            <input type="text" name="catatan" id="catatan" value="{{ old('catatan', $record->catatan) }}" placeholder="Contoh: Sakit demam berdarah..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
            @error('catatan')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-3">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                Simpan Koreksi
            </button>
        </div>
    </form>
</div>
@endsection
