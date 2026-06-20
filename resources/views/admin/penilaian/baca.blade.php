@extends('layouts.admin')

@section('title', 'Penilaian Bacaan')
@section('page_title', 'Evaluasi Bacaan (Iqra / Al-Qur\'an)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-data="{ user_id: '{{ $selectedUserId ?? '' }}' }">
    
    <!-- Left form (2/3 width) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Catat Penilaian Baru</h3>
                <p class="text-xs text-gray-500 mt-1">Input jilid Iqra, juz Al-Qur'an, halaman, serta evaluasi tajwid santri.</p>
            </div>
            <a href="{{ route('admin.penilaian.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.penilaian.baca.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Santri Dropdown -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Santri <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required x-model="user_id"
                    @change="window.location.href = '{{ route('admin.penilaian.baca') }}?user_id=' + user_id"
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
                <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Evaluasi <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bacaan Details -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6" x-data="{ jenis: '{{ old('jenis_bacaan', 'iqra') }}' }">
                <!-- Jenis Bacaan -->
                <div>
                    <label for="jenis_bacaan" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Bacaan <span class="text-rose-500">*</span></label>
                    <select name="jenis_bacaan" id="jenis_bacaan" required x-model="jenis"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                        <option value="iqra">Iqra</option>
                        <option value="alquran">Al-Qur'an</option>
                        <option value="tilawah">Tilawah</option>
                    </select>
                    @error('jenis_bacaan')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jilid / Juz -->
                <div>
                    <label for="jilid_juz" class="block text-sm font-semibold text-gray-700 mb-2">
                        <span x-show="jenis === 'iqra'">Jilid (1 - 6)</span>
                        <span x-show="jenis !== 'iqra'">Juz (1 - 30)</span>
                    </label>
                    <input type="number" name="jilid_juz" id="jilid_juz" min="1" max="30" value="{{ old('jilid_juz') }}" placeholder="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('jilid_juz')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Halaman / Ayat -->
                <div>
                    <label for="halaman_ayat" class="block text-sm font-semibold text-gray-700 mb-2">
                        <span x-show="jenis === 'iqra'">Halaman</span>
                        <span x-show="jenis !== 'iqra'">Ayat</span>
                    </label>
                    <input type="number" name="halaman_ayat" id="halaman_ayat" min="1" value="{{ old('halaman_ayat') }}" placeholder="1"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('halaman_ayat')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Keterangan Posisi -->
                <div>
                    <label for="keterangan_posisi" class="block text-sm font-semibold text-gray-700 mb-2">Surat & Ayat (Al-Qur'an)</label>
                    <input type="text" name="keterangan_posisi" id="keterangan_posisi" value="{{ old('keterangan_posisi') }}" placeholder="Misal: Al-Baqarah 1-5"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">
                    @error('keterangan_posisi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelancaran -->
                <div>
                    <label for="kelancaran" class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Kelancaran <span class="text-rose-500">*</span></label>
                    <select name="kelancaran" id="kelancaran" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                        <option value="lancar">Lancar</option>
                        <option value="cukup">Cukup</option>
                        <option value="perlu_latihan">Perlu Latihan</option>
                    </select>
                    @error('kelancaran')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="catatan_tajwid" class="block text-sm font-semibold text-gray-700 mb-2">Evaluasi Tajwid / Makhraj</label>
                <textarea name="catatan_tajwid" id="catatan_tajwid" rows="2" placeholder="Catatan perbaikan tajwid, ghunnah, mad, dsb..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">{{ old('catatan_tajwid') }}</textarea>
                @error('catatan_tajwid')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="catatan_umum" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Umum / Rekomendasi</label>
                <textarea name="catatan_umum" id="catatan_umum" rows="2" placeholder="Catatan perilaku, semangat belajar, dsb..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">{{ old('catatan_umum') }}</textarea>
                @error('catatan_umum')
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
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-3"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Bacaan Terakhir</h3>
            
            @if(empty($history))
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Pilih santri terlebih dahulu untuk menampilkan riwayat setoran membaca.</p>
                </div>
            @elseif($history->isEmpty())
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Belum ada riwayat membaca terdaftar untuk santri ini.</p>
                </div>
            @else
                <div class="space-y-4 mt-4 overflow-y-auto max-h-[500px] pr-1">
                    @foreach($history as $log)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2 relative group text-xs text-gray-600">
                            <!-- Delete button (visible on hover) -->
                            <form action="{{ route('admin.penilaian.baca.delete', $log->id) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block" onsubmit="return confirm('Hapus catatan penilaian ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700" title="Hapus Catatan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>

                            <div class="flex items-center justify-between">
                                <span class="font-bold text-emerald-800 uppercase">{{ $log->jenis_bacaan }} (Jilid/Juz: {{ $log->jilid_juz ?? '-' }})</span>
                                <span class="text-[9px] text-gray-400">{{ $log->tanggal->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Halaman/Ayat: {{ $log->halaman_ayat ?? '-' }}</span>
                                @if($log->kelancaran === 'lancar')
                                    <span class="text-emerald-700 font-bold bg-emerald-50 px-1.5 rounded">Lancar</span>
                                @elseif($log->kelancaran === 'cukup')
                                    <span class="text-amber-700 font-bold bg-amber-50 px-1.5 rounded">Cukup</span>
                                @else
                                    <span class="text-rose-700 font-bold bg-rose-50 px-1.5 rounded">Perlu Latih</span>
                                @endif
                            </div>
                            @if($log->keterangan_posisi)
                                <p class="text-[10px] text-gray-500 font-medium">{{ $log->keterangan_posisi }}</p>
                            @endif
                            @if($log->catatan_tajwid)
                                <p class="text-[10px] text-gray-400 italic">"{{ $log->catatan_tajwid }}"</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
