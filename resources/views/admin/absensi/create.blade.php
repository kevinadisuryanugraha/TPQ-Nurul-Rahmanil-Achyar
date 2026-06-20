@extends('layouts.admin')

@section('title', 'Pencatatan Kehadiran')
@section('page_title', 'Catat Absensi Sesi Kelas')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Pilih tanggal dan sesi pembelajaran, sistem otomatis mendeteksi apakah data absensi sudah pernah diinput sebelumnya.</p>
        <a href="{{ route('admin.absensi.index') }}" class="text-sm font-semibold text-emerald-800 hover:text-emerald-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <!-- Livewire Batch Input Component -->
    @livewire('admin.absensi-input')
</div>
@endsection
