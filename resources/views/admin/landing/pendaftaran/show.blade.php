@extends('layouts.admin')

@section('title', 'Detail Pendaftaran PSB')
@section('page_title', 'PSB - Detail Pendaftaran')

@section('content')
<div class="max-w-4xl space-y-6">

    <!-- Header navigation and quick stats -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('admin.landing.pendaftaran.index') }}" class="text-xs font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar PSB
        </a>
        
        <div>
            @if($registration->status === 'baru')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-150">
                    Status: Baru
                </span>
            @elseif($registration->status === 'dihubungi')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Status: Dihubungi
                </span>
            @elseif($registration->status === 'diterima')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-250">
                    Status: Diterima
                </span>
            @elseif($registration->status === 'ditolak')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-stone-100 text-stone-600 border border-stone-250">
                    Status: Ditolak
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Panel: Data Pendaftar -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-sm font-bold text-gray-800"><i class="fa-solid fa-id-card text-emerald-800 mr-1.5"></i> Biodata Calon Santri</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-xs">
                    <div>
                        <span class="text-gray-400 block font-semibold">Nama Lengkap</span>
                        <span class="font-bold text-emerald-950 text-sm mt-0.5 block">{{ $registration->nama_lengkap }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Jenis Kelamin</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $registration->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Tempat, Tanggal Lahir</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">
                            {{ $registration->tempat_lahir ?: '-' }}, {{ $registration->tanggal_lahir->format('d F Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Usia</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $registration->tanggal_lahir->diff(now())->y }} Tahun</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-sm font-bold text-gray-800"><i class="fa-solid fa-users-rectangle text-emerald-800 mr-1.5"></i> Hubungan Orang Tua & Kontak</h3>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-400 block font-semibold">Nama Orang Tua / Wali</span>
                            <span class="font-bold text-gray-800 mt-0.5 block">{{ $registration->nama_orang_tua }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block font-semibold">No. WhatsApp</span>
                            @php
                                $phone = preg_replace('/[^0-9]/', '', $registration->no_wa);
                                if (str_starts_with($phone, '0')) {
                                    $phone = '62' . substr($phone, 1);
                                }
                                $waUrl = "https://wa.me/{$phone}";
                            @endphp
                            <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center text-emerald-700 hover:text-emerald-900 font-bold hover:underline mt-0.5">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-sm mr-1.5"></i> {{ $registration->no_wa }}
                            </a>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Alamat Rumah Lengkap</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block leading-relaxed">{{ $registration->alamat }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="text-sm font-bold text-gray-800"><i class="fa-solid fa-book-quran text-emerald-800 mr-1.5"></i> Riwayat Belajar Mengaji & Catatan</h3>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-400 block font-semibold">Pernah Belajar Sebelumnya?</span>
                            <span class="font-semibold text-gray-800 mt-0.5 block">
                                @if($registration->pernah_mengaji)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">Sudah Pernah</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-stone-50 text-stone-600 border border-stone-100">Belum Pernah</span>
                                @endif
                            </span>
                        </div>
                        @if($registration->pernah_mengaji)
                            <div>
                                <span class="text-gray-400 block font-semibold">Level Mengaji Terakhir</span>
                                <span class="font-semibold text-emerald-950 mt-0.5 block">{{ $registration->level_mengaji_sebelumnya ?: '-' }}</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Catatan / Permintaan Orang Tua</span>
                        <span class="font-semibold text-gray-700 mt-0.5 block leading-relaxed">{{ $registration->catatan_tambahan ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Review & Actions -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Terima & Buat Akun Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider"><i class="fa-solid fa-user-plus mr-1"></i> Aksi Penerimaan</h4>
                
                @if($registration->status === 'diterima')
                    <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center space-y-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-3xl"></i>
                        <p class="text-[11px] text-emerald-900 font-semibold leading-relaxed">
                            Pendaftar sudah diterima dan akun santri telah aktif dalam sistem.
                        </p>
                        @if($registration->user_id)
                            <a href="{{ route('admin.murid.show', $registration->user_id) }}" class="inline-block px-4 py-2 bg-emerald-800 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg transition shadow-sm">
                                <i class="fa-solid fa-user mr-1"></i> Buka Profil Santri
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-[11px] text-gray-500 leading-relaxed font-light">
                        Jika wali santri sudah melakukan pembayaran/konfirmasi administrasi, klik tombol di bawah untuk membuat akun santri secara otomatis.
                    </p>
                    <a href="{{ route('admin.landing.pendaftaran.terima', $registration->id) }}"
                       class="w-full py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-extrabold rounded-xl shadow-md transition active:scale-[0.98] text-xs text-center block">
                        Terima & Buat Akun Murid <i class="fa-solid fa-angle-right ml-1"></i>
                    </a>
                @endif
            </div>

            <!-- Status Form -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-4"><i class="fa-solid fa-pen-nib mr-1"></i> Catatan Internal & Status</h4>
                
                <form action="{{ route('admin.landing.pendaftaran.update-status', $registration->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="block text-[10px] font-bold text-gray-700 mb-1.5 uppercase">Ubah Status</label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs bg-white">
                            <option value="baru" {{ old('status', $registration->status) === 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="dihubungi" {{ old('status', $registration->status) === 'dihubungi' ? 'selected' : '' }}>Dihubungi</option>
                            <option value="diterima" {{ old('status', $registration->status) === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ old('status', $registration->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status')
                            <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan_internal" class="block text-[10px] font-bold text-gray-700 mb-1.5 uppercase">Catatan Internal Admin</label>
                        <textarea name="catatan_internal" id="catatan_internal" rows="4" placeholder="Tuliskan perkembangan komunikasi di sini..."
                                  class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('catatan_internal', $registration->catatan_internal) }}</textarea>
                        <span class="text-[9px] text-gray-400 block mt-1"><i class="fa-solid fa-circle-info"></i> Catatan ini hanya terlihat oleh Ustadz/Admin saja.</span>
                        @error('catatan_internal')
                            <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2.5 bg-stone-800 hover:bg-stone-700 text-white font-bold rounded-xl text-xs transition active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection
