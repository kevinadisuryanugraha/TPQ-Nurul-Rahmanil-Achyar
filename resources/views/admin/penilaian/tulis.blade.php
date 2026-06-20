@extends('layouts.admin')

@section('title', 'Penilaian Menulis')
@section('page_title', 'Evaluasi Kemampuan Menulis Huruf Arab')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-data="{ user_id: '{{ $selectedUserId ?? '' }}' }">
    
    <!-- Left form (2/3 width) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Catat Nilai Menulis</h3>
                <p class="text-xs text-gray-500 mt-1">Input nilai menulis huruf Arab/hijaiyah sambung (skala 0 - 100), sistem otomatis menghitung grade.</p>
            </div>
            <a href="{{ route('admin.penilaian.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.penilaian.tulis.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Santri Dropdown -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Santri <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required x-model="user_id"
                    @change="window.location.href = '{{ route('admin.penilaian.tulis') }}?user_id=' + user_id"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="">-- Pilih Santri --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ $selectedUserId == $st->id ? 'selected' : '' }}>{{ $st->nama_lengkap }} (Lvl: {{ $st->currentLevel->nama }})</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Picker -->
            <div>
                <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Penilaian <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Materi -->
                <div>
                    <label for="materi" class="block text-sm font-semibold text-gray-700 mb-2">Materi Menulis <span class="text-rose-500">*</span></label>
                    <input type="text" name="materi" id="materi" required value="{{ old('materi') }}" placeholder="Contoh: Huruf Sambung Alif-Ya"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('materi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nilai -->
                <div>
                    <label for="nilai" class="block text-sm font-semibold text-gray-700 mb-2">Nilai Angka (0 - 100) <span class="text-rose-500">*</span></label>
                    <input type="number" name="nilai" id="nilai" required min="0" max="100" value="{{ old('nilai') }}" placeholder="85"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('nilai')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Khusus</label>
                <textarea name="catatan" id="catatan" rows="3" placeholder="Evaluasi kerapian tulisan, kebenaran harakat, dsb..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
                <button type="submit" :disabled="!user_id"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 disabled:opacity-50 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>

    <!-- Right history panel (1/3 width) -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-6">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-3"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Nilai Terakhir</h3>
            
            @if(empty($history))
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Pilih santri terlebih dahulu untuk menampilkan riwayat nilai menulis.</p>
                </div>
            @elseif($history->isEmpty())
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Belum ada riwayat menulis terdaftar untuk santri ini.</p>
                </div>
            @else
                <div class="space-y-4 mt-4 overflow-y-auto max-h-[500px] pr-1">
                    @foreach($history as $log)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2 relative group text-xs text-gray-600">
                            <!-- Delete button (visible on hover) -->
                            <form action="{{ route('admin.penilaian.tulis.delete', $log->id) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block" onsubmit="return confirm('Hapus catatan penilaian ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700" title="Hapus Catatan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>

                            <div class="flex items-center justify-between">
                                <span class="font-bold text-indigo-800">Menulis</span>
                                <span class="text-[9px] text-gray-400">{{ $log->tanggal->format('d M Y') }}</span>
                            </div>
                            <div class="font-bold text-gray-800">{{ $log->materi }}</div>
                            <div class="flex justify-between items-center">
                                <span>Nilai: <strong class="text-gray-900 font-bold text-sm">{{ $log->nilai }}</strong></span>
                                <span class="px-2 py-0.5 bg-emerald-800 text-white font-extrabold rounded-md">Grade {{ $log->grade }}</span>
                            </div>
                            @if($log->catatan)
                                <p class="text-[10px] text-gray-400 italic">"{{ $log->catatan }}"</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
