@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 sm:py-24 text-center">
    
    <!-- Outer Card -->
    <div class="bg-white rounded-3xl border border-emerald-50/80 shadow-md p-8 sm:p-12 relative overflow-hidden">
        <!-- Brand accent line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-800 to-amber-500"></div>

        <!-- Success Animation Icon -->
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6 text-3xl">
            <i class="fa-solid fa-circle-check animate-bounce"></i>
        </div>

        @if($isDuplicate)
            <!-- Duplicate submission warning badge -->
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800 flex items-start space-x-3 text-left">
                <i class="fa-solid fa-circle-exclamation text-amber-500 text-lg mt-0.5 flex-shrink-0"></i>
                <div>
                    <span class="font-bold block">Pemberitahuan Pendaftaran</span>
                    <span class="block mt-0.5 leading-relaxed font-light">Sepertinya Anda baru saja melakukan pendaftaran dalam 24 jam terakhir. Jangan khawatir, tim kami sudah menerima data Anda dan akan segera menghubungi Anda.</span>
                </div>
            </div>
        @endif

        <h1 class="text-xl sm:text-2xl font-extrabold text-emerald-950 mb-4">Pendaftaran Berhasil Terkirim!</h1>
        
        <p class="text-xs text-gray-500 leading-relaxed font-light mb-8 max-w-md mx-auto">
            Terima kasih, data calon santri <strong class="font-bold text-emerald-900">{{ $nama }}</strong> sudah berhasil terekam dalam sistem kami. Tim pengurus akan memproses data Anda dalam 1x24 jam.
        </p>

        <!-- CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ $waLink }}" target="_blank"
               class="w-full sm:w-auto px-6 py-3.5 bg-emerald-800 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-sm transition active:scale-95 flex items-center justify-center">
                <i class="fa-brands fa-whatsapp text-base mr-2"></i> Hubungi WhatsApp Kami
            </a>
            
            <a href="{{ route('landing') }}"
               class="w-full sm:w-auto px-6 py-3.5 bg-stone-100 hover:bg-stone-200 text-emerald-950 text-xs font-bold rounded-2xl transition flex items-center justify-center">
                Kembali Ke Beranda
            </a>
        </div>

        <p class="text-[10px] text-gray-400 mt-8 font-light">
            Klik tombol WhatsApp di atas jika Anda ingin mempercepat proses konfirmasi pendaftaran.
        </p>
    </div>
</div>
@endsection
