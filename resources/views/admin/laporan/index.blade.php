@extends('layouts.admin')

@section('title', 'Laporan & Rapor')
@section('page_title', 'Laporan & Rekapitulasi')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Card 1: Rekap Excel per Kelas -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center text-xl mb-5 shadow-sm">
                <i class="fa-solid fa-file-excel"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Rekapitulasi Kelas (Excel)</h3>
            <p class="text-sm text-gray-500 mb-6">
                Unduh file spreadsheet yang berisi data lengkap nilai (Al-Qur'an/Iqra, hafalan, tulisan, dan praktik Fiqh) serta rekap absensi santri dalam satu kelas tertentu.
            </p>
        </div>

        <form action="{{ route('admin.laporan.export-excel-kelas') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Pilih Kelas / Level</label>
                <select name="level_id" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl->id }}">{{ $lvl->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" 
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl transition duration-150 flex items-center justify-center space-x-2 text-sm shadow-sm">
                <i class="fa-solid fa-download"></i>
                <span>Unduh Rekap Kelas</span>
            </button>
        </form>
    </div>

    <!-- Card 2: Laporan Lainnya & Rapor Individu -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xl mb-5 shadow-sm">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Rapor & Data Santri</h3>
            <p class="text-sm text-gray-500 mb-6">
                Hasilkan kartu hasil belajar (rapor PDF) per santri secara individu atau unduh daftar kontak seluruh santri yang aktif di aplikasi.
            </p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('admin.laporan.murid') }}" 
                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 rounded-xl transition duration-150 flex items-center justify-center space-x-2 text-sm shadow-sm">
                <i class="fa-solid fa-id-card"></i>
                <span>Cetak Rapor Santri (PDF)</span>
            </a>
            
            <a href="{{ route('admin.laporan.export-excel-murid') }}" 
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl transition duration-150 flex items-center justify-center space-x-2 text-sm border border-gray-200">
                <i class="fa-solid fa-users"></i>
                <span>Ekspor Semua Kontak Santri (Excel)</span>
            </a>
        </div>
    </div>
</div>
@endsection
