@extends('layouts.admin')

@section('title', 'Profil Santri')
@section('page_title', 'Profil & Evaluasi Santri')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'overview', showReset: false }">
    
    <!-- Top Profile Header Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <!-- Profile Info Left -->
            <div class="flex items-center space-x-4">
                @if($student->foto)
                    <img src="{{ $student->foto }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-emerald-100 shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-2xl border border-emerald-100">
                        {{ strtoupper(substr($student->nama_panggilan, 0, 2)) }}
                    </div>
                @endif
                <div class="space-y-1">
                    <div class="flex items-center space-x-2 flex-wrap">
                        <h2 class="text-xl font-bold text-gray-800 leading-tight">{{ $student->nama_lengkap }}</h2>
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold rounded-full border border-emerald-100 uppercase">{{ $student->currentLevel->nama }}</span>
                        @if(!$student->is_active)
                            <span class="px-2 py-0.5 bg-rose-50 text-rose-800 text-[10px] font-bold rounded-full border border-rose-100 uppercase">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">Panggilan: <strong>{{ $student->nama_panggilan }}</strong> &bull; Username: <code class="bg-gray-50 px-1.5 py-0.5 rounded font-mono text-[10px]">{{ $student->username }}</code></p>
                </div>
            </div>

            <!-- Level Promotion Buttons Right -->
            <div class="flex items-center space-x-2 flex-wrap">
                <!-- Edit button -->
                <a href="{{ route('admin.murid.edit', $student->id) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 hover:border-gray-300 rounded-xl text-xs font-bold text-gray-700 transition flex items-center space-x-1">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Edit Profil</span>
                </a>

                <!-- Naik Level Form -->
                @if($nextLevel)
                    <form action="{{ route('admin.murid.naik-level', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MENAIKKAN tingkat level {{ $student->nama_panggilan }} dari {{ $student->currentLevel->nama }} ke {{ $nextLevel->nama }}?');">
                        @csrf
                        <input type="hidden" name="catatan" value="Dinaikkan oleh {{ auth()->guard('admin')->user()->nama }}">
                        <button type="submit" class="px-3 py-2 bg-emerald-800 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center space-x-1">
                            <i class="fa-solid fa-arrow-up"></i>
                            <span>Naik ke {{ $nextLevel->nama }}</span>
                        </button>
                    </form>
                @endif

                <!-- Turun Level Form -->
                @if($prevLevel)
                    <form action="{{ route('admin.murid.turun-level', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MENURUNKAN tingkat level {{ $student->nama_panggilan }} dari {{ $student->currentLevel->nama }} ke {{ $prevLevel->nama }}?');">
                        @csrf
                        <input type="hidden" name="catatan" value="Diturunkan oleh {{ auth()->guard('admin')->user()->nama }}">
                        <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 rounded-xl text-xs font-bold transition flex items-center space-x-1">
                            <i class="fa-solid fa-arrow-down"></i>
                            <span>Turun ke {{ $prevLevel->nama }}</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs Layout -->
    <div class="flex border-b border-gray-200 bg-white px-4 rounded-xl shadow-sm border border-gray-100 overflow-x-auto whitespace-nowrap">
        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-emerald-800 text-emerald-800 border-b-2 font-bold' : 'text-gray-500 hover:text-gray-800'" class="px-4 py-3 text-sm transition">Ringkasan Profil</button>
        <button @click="activeTab = 'nilai'" :class="activeTab === 'nilai' ? 'border-emerald-800 text-emerald-800 border-b-2 font-bold' : 'text-gray-500 hover:text-gray-800'" class="px-4 py-3 text-sm transition">Nilai Terakhir (4 Domain)</button>
        <button @click="activeTab = 'level'" :class="activeTab === 'level' ? 'border-emerald-800 text-emerald-800 border-b-2 font-bold' : 'text-gray-500 hover:text-gray-800'" class="px-4 py-3 text-sm transition">Riwayat Level</button>
    </div>

    <!-- Tab Contents -->
    <div>
        <!-- TAB 1: OVERVIEW -->
        <div x-show="activeTab === 'overview'" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Left Info Panel (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Biodata Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-2"><i class="fa-solid fa-address-card mr-1.5"></i> Informasi Detail Biodata</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div>
                            <span class="text-gray-400 block text-xs">Tempat & Tanggal Lahir</span>
                            <span class="text-gray-800 font-medium">{{ $student->tempat_lahir ?? '-' }}, {{ $student->tanggal_lahir ? $student->tanggal_lahir->format('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-xs">Jenis Kelamin</span>
                            <span class="text-gray-800 font-medium">{{ $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-xs">Wali / Orang Tua</span>
                            <span class="text-gray-800 font-medium">{{ $student->nama_orang_tua ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-xs">No. HP Orang Tua</span>
                            <span class="text-gray-800 font-medium">{{ $student->no_hp_orang_tua ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-xs">Tanggal Masuk TPQ</span>
                            <span class="text-gray-800 font-medium">{{ $student->tanggal_masuk->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-xs">Alamat Tinggal</span>
                            <span class="text-gray-800 font-medium">{{ $student->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Absensi Month Stats -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-2"><i class="fa-solid fa-calendar-check mr-1.5"></i> Kehadiran Bulan Ini</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                            <span class="text-2xl font-black text-emerald-800">{{ $absensiStats['hadir'] }}</span>
                            <span class="text-[10px] text-gray-500 block">Hadir</span>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="text-2xl font-black text-blue-800">{{ $absensiStats['izin'] }}</span>
                            <span class="text-[10px] text-gray-500 block">Izin</span>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                            <span class="text-2xl font-black text-amber-800">{{ $absensiStats['sakit'] }}</span>
                            <span class="text-[10px] text-gray-500 block">Sakit</span>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-xl border border-rose-100">
                            <span class="text-2xl font-black text-rose-800">{{ $absensiStats['alpha'] }}</span>
                            <span class="text-[10px] text-gray-500 block">Tanpa Keterangan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Actions Panel (1/3 width) -->
            <div class="space-y-6">
                <!-- Reset Password Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center justify-between">
                        <span>Akses Akun Wali</span>
                        <i class="fa-solid fa-key text-amber-500"></i>
                    </h3>
                    <button @click="showReset = !showReset" class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-emerald-950 font-bold rounded-xl text-xs shadow-sm transition">
                        Reset Password Wali
                    </button>

                    <div x-show="showReset" class="pt-4 border-t border-gray-100 space-y-4" x-transition>
                        <form action="{{ route('admin.murid.reset-password', $student->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="password" class="block text-xs font-semibold text-gray-500 mb-1">Password Baru</label>
                                <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-transparent transition text-xs">
                            </div>
                            <button type="submit" class="w-full py-2 bg-emerald-800 hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition">
                                Konfirmasi Reset
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: GRADES / PENILAIAN -->
        <div x-show="activeTab === 'nilai'" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Domain 1: Baca -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider"><i class="fa-solid fa-book-open-reader mr-1"></i> Bacaan (Quran/Iqra)</h3>
                    <a href="{{ route('admin.penilaian.baca', ['user_id' => $student->id]) }}" class="text-xs text-emerald-800 font-semibold hover:underline">+ Tambah</a>
                </div>
                @if($latestBaca)
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jenis & Posisi</span>
                            <strong class="text-gray-800 font-bold uppercase">{{ $latestBaca->jenis_bacaan }} (Jilid/Juz: {{ $latestBaca->jilid_juz ?? '-' }}, Halaman: {{ $latestBaca->halaman_ayat ?? '-' }})</strong>
                        </div>
                        @if($latestBaca->keterangan_posisi)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Posisi</span>
                                <strong class="text-gray-800">{{ $latestBaca->keterangan_posisi }}</strong>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kelancaran</span>
                            @if($latestBaca->kelancaran === 'lancar')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 font-bold rounded text-xs">Lancar</span>
                            @elseif($latestBaca->kelancaran === 'cukup')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 font-bold rounded text-xs">Cukup</span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-800 font-bold rounded text-xs">Perlu Latihan</span>
                            @endif
                        </div>
                        @if($latestBaca->catatan_tajwid)
                            <div class="bg-gray-50 p-2.5 rounded text-xs text-gray-500">
                                <strong>Tajwid:</strong> "{{ $latestBaca->catatan_tajwid }}"
                            </div>
                        @endif
                        <span class="text-[10px] text-gray-400 block pt-1">Dinilai pada: {{ $latestBaca->tanggal->format('d M Y') }} oleh {{ $latestBaca->admin->nama }}</span>
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada catatan penilaian membaca.</p>
                @endif
            </div>

            <!-- Domain 2: Hafalan -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider"><i class="fa-solid fa-brain mr-1"></i> Hafalan (Surah/Doa/Hadits)</h3>
                    <a href="{{ route('admin.penilaian.hafalan', ['user_id' => $student->id]) }}" class="text-xs text-emerald-800 font-semibold hover:underline">+ Tambah</a>
                </div>
                @if($latestHafalan)
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jenis Hafalan</span>
                            <strong class="text-gray-800 font-bold uppercase">{{ $latestHafalan->jenis_hafalan }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nama Item</span>
                            <strong class="text-gray-800">{{ $latestHafalan->nama_item }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status</span>
                            @if($latestHafalan->status === 'hafal_sempurna')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 font-bold rounded text-xs">Sempurna</span>
                            @elseif($latestHafalan->status === 'hafal_dengan_kesalahan')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 font-bold rounded text-xs">Hafal (Sedikit Koreksi)</span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-800 font-bold rounded text-xs">Perlu Ulang</span>
                            @endif
                        </div>
                        <span class="text-[10px] text-gray-400 block pt-1">Dinilai pada: {{ $latestHafalan->tanggal->format('d M Y') }} oleh {{ $latestHafalan->admin->nama }}</span>
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada catatan penilaian hafalan.</p>
                @endif
            </div>

            <!-- Domain 3: Tulis -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider"><i class="fa-solid fa-pen-nib mr-1"></i> Menulis Arab</h3>
                    <a href="{{ route('admin.penilaian.tulis', ['user_id' => $student->id]) }}" class="text-xs text-emerald-800 font-semibold hover:underline">+ Tambah</a>
                </div>
                @if($latestTulis)
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Materi Tulis</span>
                            <strong class="text-gray-800">{{ $latestTulis->materi }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Skor Angka</span>
                            <strong class="text-gray-800 font-bold text-lg">{{ $latestTulis->nilai }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Grade Otomatis</span>
                            <span class="px-3 py-1 bg-emerald-800 text-white font-extrabold rounded-lg text-xs">{{ $latestTulis->grade }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400 block pt-1">Dinilai pada: {{ $latestTulis->tanggal->format('d M Y') }} oleh {{ $latestTulis->admin->nama }}</span>
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada catatan penilaian menulis.</p>
                @endif
            </div>

            <!-- Domain 4: Praktik -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider"><i class="fa-solid fa-compress mr-1"></i> Praktik Ibadah</h3>
                    <a href="{{ route('admin.penilaian.praktik', ['user_id' => $student->id]) }}" class="text-xs text-emerald-800 font-semibold hover:underline">+ Tambah</a>
                </div>
                @if($latestPraktik)
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jenis Ibadah</span>
                            <strong class="text-gray-800 font-bold uppercase">{{ str_replace('_', ' ', $latestPraktik->jenis_praktik) }}</strong>
                        </div>
                        <div>
                            <span class="text-gray-400 block mb-2">Checklist Komponen Terpenuhi:</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                @foreach($latestPraktik->komponenChecklist as $comp)
                                    <div class="flex items-center space-x-1.5">
                                        @if($comp->is_terpenuhi)
                                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                            <span class="text-gray-800">{{ $comp->nama_komponen }}</span>
                                        @else
                                            <i class="fa-regular fa-circle text-gray-300"></i>
                                            <span class="text-gray-400">{{ $comp->nama_komponen }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400 block pt-1">Dinilai pada: {{ $latestPraktik->tanggal->format('d M Y') }} oleh {{ $latestPraktik->admin->nama }}</span>
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada catatan penilaian praktik.</p>
                @endif
            </div>
        </div>

        <!-- TAB 3: LEVEL HISTORIES -->
        <div x-show="activeTab === 'level'" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-white">
                <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider">Histori Mutasi & Perkembangan Tingkat</h3>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($levelHistories as $history)
                    <div class="p-4 flex items-start justify-between hover:bg-gray-50 transition text-sm">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                @if($history->tipe === 'awal')
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[10px] font-bold rounded-full border border-blue-100">Registrasi Awal</span>
                                @elseif($history->tipe === 'naik')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold rounded-full border border-emerald-100">Kenaikan Level</span>
                                @else
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-800 text-[10px] font-bold rounded-full border border-rose-100">Penurunan Level</span>
                                @endif
                                <strong class="text-gray-800">{{ $history->level->nama }}</strong>
                                @if($history->levelSebelumnya)
                                    <span class="text-gray-400 text-xs">(dari {{ $history->levelSebelumnya->nama }})</span>
                                @endif
                            </div>
                            @if($history->catatan)
                                <p class="text-xs text-gray-500 italic">"{{ $history->catatan }}"</p>
                            @endif
                        </div>
                        <div class="text-right text-xs text-gray-400">
                            <p class="font-medium text-gray-700 leading-none mb-1">Oleh: {{ $history->admin->nama }}</p>
                            <span>{{ $history->created_at }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
