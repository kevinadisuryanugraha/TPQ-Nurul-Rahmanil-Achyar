@extends('layouts.admin')

@section('title', 'Penilaian Hafalan')
@section('page_title', 'Evaluasi Hafalan Santri')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" 
    x-data="{ 
        user_id: '{{ $selectedUserId ?? '' }}',
        jenis: '{{ old('jenis_hafalan', 'surat') }}',
        surahs: @js($surahs),
        duas: @js($duas),
        hadiths: @js($hadiths)
    }">
    
    <!-- Left form (2/3 width) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Catat Setoran Hafalan</h3>
                <p class="text-xs text-gray-500 mt-1">Pilih jenis setoran (Surah Pendek, Hadits, atau Doa Harian) dan evaluasi tingkat hafalan.</p>
            </div>
            <a href="{{ route('admin.penilaian.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.penilaian.hafalan.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Santri Dropdown -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Santri <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required x-model="user_id"
                    @change="window.location.href = '{{ route('admin.penilaian.hafalan') }}?user_id=' + user_id"
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
                <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Setoran <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                @error('tanggal')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Jenis Hafalan -->
                <div>
                    <label for="jenis_hafalan" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Setoran <span class="text-rose-500">*</span></label>
                    <select name="jenis_hafalan" id="jenis_hafalan" required x-model="jenis"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                        <option value="surat">Surah Pendek</option>
                        <option value="doa">Doa Harian</option>
                        <option value="hadist">Hadits Pilihan</option>
                    </select>
                    @error('jenis_hafalan')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Item (Dynamic Select populated via Alpine.js) -->
                <div>
                    <label for="nama_item" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Materi Setoran <span class="text-rose-500">*</span></label>
                    <select name="nama_item" id="nama_item" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                        
                        <!-- Show Surahs if jenis === 'surat' -->
                        <template x-if="jenis === 'surat'">
                            <optgroup label="Daftar Surah">
                                <template x-for="surah in surahs" :key="surah.id">
                                    <option :value="'Surah ' + surah.nama_latin" x-text="surah.id + '. ' + surah.nama_latin + ' (' + surah.nama_arab + ')'"></option>
                                </template>
                            </optgroup>
                        </template>

                        <!-- Show Duas if jenis === 'doa' -->
                        <template x-if="jenis === 'doa'">
                            <optgroup label="Daftar Doa">
                                <template x-for="doa in duas" :key="doa.id">
                                    <option :value="doa.judul" x-text="doa.judul"></option>
                                </template>
                            </optgroup>
                        </template>

                        <!-- Show Hadiths if jenis === 'hadist' -->
                        <template x-if="jenis === 'hadist'">
                            <optgroup label="Daftar Hadits">
                                <template x-for="hadith in hadiths" :key="hadith.id">
                                    <option :value="'Hadits ' + (hadith.kategori || '') + ' (' + hadith.sumber_kitab + ')'" 
                                        x-text="(hadith.kategori || 'Hadits') + ' (' + hadith.sumber_kitab + ')'"></option>
                                </template>
                            </optgroup>
                        </template>
                    </select>
                    @error('nama_item')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Kelulusan Hafalan -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Evaluasi Hasil Hafalan <span class="text-rose-500">*</span></label>
                <select name="status" id="status" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                    <option value="hafal_sempurna">Hafal Sempurna (Lancar & Fasih)</option>
                    <option value="hafal_dengan_kesalahan">Hafal dengan Sedikit Kesalahan (Ada Terbata/Lupa Ringan)</option>
                    <option value="perlu_diulang">Perlu Diulang (Belum Lancar)</option>
                </select>
                @error('status')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Khusus Ustadz / Ustadzah</label>
                <textarea name="catatan" id="catatan" rows="3" placeholder="Catatan lafaz yang salah, hukum mad yang keliru, dsb..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-sm">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
                <button type="submit" :disabled="!user_id"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 disabled:opacity-50 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                    Simpan Hafalan
                </button>
            </div>
        </form>
    </div>

    <!-- Right history panel (1/3 width) -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-6">
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-3"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Hafalan Terakhir</h3>
            
            @if(empty($history))
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Pilih santri terlebih dahulu untuk menampilkan riwayat setoran hafalan.</p>
                </div>
            @elseif($history->isEmpty())
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Belum ada riwayat setoran hafalan terdaftar untuk santri ini.</p>
                </div>
            @else
                <div class="space-y-4 mt-4 overflow-y-auto max-h-[500px] pr-1">
                    @foreach($history as $log)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2 relative group text-xs text-gray-600">
                            <!-- Delete button (visible on hover) -->
                            <form action="{{ route('admin.penilaian.hafalan.delete', $log->id) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block" onsubmit="return confirm('Hapus catatan penilaian ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700" title="Hapus Catatan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>

                            <div class="flex items-center justify-between">
                                <span class="font-bold text-emerald-800 uppercase">{{ $log->jenis_hafalan }}</span>
                                <span class="text-[9px] text-gray-400">{{ $log->tanggal->format('d M Y') }}</span>
                            </div>
                            <div class="font-bold text-gray-800">{{ $log->nama_item }}</div>
                            <div class="flex justify-between">
                                <span>Status:</span>
                                @if($log->status === 'hafal_sempurna')
                                    <span class="text-emerald-700 font-bold bg-emerald-50 px-1.5 rounded">Sempurna</span>
                                @elseif($log->status === 'hafal_dengan_kesalahan')
                                    <span class="text-amber-700 font-bold bg-amber-50 px-1.5 rounded">Sedikit Salah</span>
                                @else
                                    <span class="text-rose-700 font-bold bg-rose-50 px-1.5 rounded">Ulang</span>
                                @endif
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
