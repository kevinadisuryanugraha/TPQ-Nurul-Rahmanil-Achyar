@extends('layouts.admin')

@section('title', 'Penilaian Praktik')
@section('page_title', 'Evaluasi Praktik Ibadah (Wudhu / Sholat / Tayamum)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" 
    x-data="{ 
        user_id: '{{ $selectedUserId ?? '' }}',
        jenis: '{{ old('jenis_praktik', 'wudhu') }}',
        checklists: @js($checklists)
    }">
    
    <!-- Left form (2/3 width) -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Catat Penilaian Praktik</h3>
                <p class="text-xs text-gray-500 mt-1">Pilih jenis praktik ibadah, lalu beri tanda centang untuk komponen langkah-langkah yang terpenuhi dengan benar.</p>
            </div>
            <a href="{{ route('admin.penilaian.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.penilaian.praktik.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Santri Dropdown -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Santri <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required x-model="user_id"
                    @change="window.location.href = '{{ route('admin.penilaian.praktik') }}?user_id=' + user_id"
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Date Picker -->
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Evaluasi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
                    @error('tanggal')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Praktik -->
                <div>
                    <label for="jenis_praktik" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Praktik Ibadah <span class="text-rose-500">*</span></label>
                    <select name="jenis_praktik" id="jenis_praktik" required x-model="jenis"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                        <option value="wudhu">Wudhu</option>
                        <option value="sholat_fardhu">Sholat Fardhu</option>
                        <option value="sholat_sunnah">Sholat Sunnah</option>
                        <option value="tayamum">Tayamum</option>
                        <option value="membaca_doa">Membaca Doa</option>
                    </select>
                    @error('jenis_praktik')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dynamic Checklist Components -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Checklist Komponen Gerakan & Bacaan <span class="text-rose-500">*</span></label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto pr-2 border border-gray-100 rounded-2xl p-4 bg-gray-50/20">
                    <template x-for="item in checklists[jenis]" :key="item">
                        <label class="flex items-center py-2 px-3 border border-gray-200 rounded-xl hover:bg-emerald-50/10 hover:border-emerald-500 transition cursor-pointer select-none">
                            <!-- Hidden input to submit 0 if checkbox is unchecked -->
                            <input type="hidden" :name="'komponen['+item+']'" value="0">
                            <!-- Checkbox input -->
                            <input type="checkbox" :name="'komponen['+item+']'" value="1" checked
                                class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mr-3">
                            <span class="text-xs font-semibold text-gray-700" x-text="item"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Evaluasi / Koreksi Ustadz</label>
                <textarea name="catatan" id="catatan" rows="3" placeholder="Tulis catatan gerakan yang masih keliru, dsb..."
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
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-3"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Riwayat Ujian Terakhir</h3>
            
            @if(empty($history))
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Pilih santri terlebih dahulu untuk menampilkan riwayat ujian praktik.</p>
                </div>
            @elseif($history->isEmpty())
                <div class="p-8 text-center text-gray-400 border border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-4">
                    <p class="text-xs">Belum ada riwayat ujian praktik terdaftar untuk santri ini.</p>
                </div>
            @else
                <div class="space-y-4 mt-4 overflow-y-auto max-h-[500px] pr-1">
                    @foreach($history as $log)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2 relative group text-xs text-gray-600">
                            <!-- Delete button (visible on hover) -->
                            <form action="{{ route('admin.penilaian.praktik.delete', $log->id) }}" method="POST" class="absolute top-2 right-2 hidden group-hover:block" onsubmit="return confirm('Hapus catatan penilaian ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700" title="Hapus Catatan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>

                            <div class="flex items-center justify-between">
                                <span class="font-bold text-indigo-800 uppercase">{{ str_replace('_', ' ', $log->jenis_praktik) }}</span>
                                <span class="text-[9px] text-gray-400">{{ $log->tanggal->format('d M Y') }}</span>
                            </div>
                            
                            <!-- Display score breakdown -->
                            <div>
                                <span class="text-[10px] text-gray-400">Komponen Terpenuhi:</span>
                                <div class="font-bold text-gray-800 text-sm">
                                    {{ $log->komponenChecklist->where('is_terpenuhi', true)->count() }} / {{ $log->komponenChecklist->count() }} komponen
                                </div>
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
