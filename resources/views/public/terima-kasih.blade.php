@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="bg-stone-50 pattern-islamic-light min-h-screen flex items-center">
<div class="max-w-2xl mx-auto px-4 py-16 sm:py-24 w-full">

    {{-- Main Card --}}
    <div class="bg-white rounded-3xl border border-emerald-50/80 shadow-xl overflow-hidden">

        {{-- Brand accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-emerald-800 via-emerald-700 to-amber-500"></div>

        <div class="p-8 sm:p-12 text-center">

            {{-- Success Icon Animation --}}
            <div class="flex justify-center mb-8">
                <div class="relative">
                    {{-- Pulse ring --}}
                    <div class="animate-check-pulse w-24 h-24 rounded-full bg-emerald-50 flex items-center justify-center">
                        {{-- Scale-in check icon --}}
                        <div class="animate-scale-in w-16 h-16 rounded-full bg-emerald-800 text-white flex items-center justify-center shadow-lg shadow-emerald-900/30">
                            <i class="fa-solid fa-check text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Duplicate warning --}}
            @if($isDuplicate)
                <div class="mb-7 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-sm text-amber-800 flex items-start gap-3 text-left shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-amber-500 text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="font-bold block mb-1">Pemberitahuan Pendaftaran</span>
                        <span class="leading-relaxed text-amber-700">Sepertinya Anda baru saja melakukan pendaftaran dalam 24 jam terakhir. Jangan khawatir, tim kami sudah menerima data Anda dan akan segera menghubungi Anda.</span>
                    </div>
                </div>
            @endif

            {{-- Success message --}}
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 mb-3 tracking-tight">
                    Pendaftaran Berhasil Terkirim!
                </h1>
                <p class="text-base text-gray-500 leading-relaxed max-w-md mx-auto">
                    Terima kasih, data calon santri
                    <strong class="font-extrabold text-emerald-900">{{ $nama }}</strong>
                    sudah berhasil terekam dalam sistem kami.
                </p>
            </div>

            {{-- Next Steps --}}
            <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-6 mb-8 text-left">
                <h2 class="text-sm font-bold text-emerald-950 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-emerald-700"></i>
                    Langkah Selanjutnya
                </h2>
                <ol class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-800 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <div>
                            <span class="text-sm font-semibold text-gray-700 block">Tim akan menghubungi Anda</span>
                            <span class="text-xs text-gray-400">Pengurus akan menghubungi via WhatsApp dalam 1×24 jam kerja.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-800 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <div>
                            <span class="text-sm font-semibold text-gray-700 block">Verifikasi data &amp; orientasi</span>
                            <span class="text-xs text-gray-400">Jadwal orientasi dan penempatan level akan dikonfirmasi bersama.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-amber-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        <div>
                            <span class="text-sm font-semibold text-gray-700 block">Santri siap belajar</span>
                            <span class="text-xs text-gray-400">Akun portal santri dibuat dan perjalanan belajar Al-Qur'an dimulai.</span>
                        </div>
                    </li>
                </ol>
            </div>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ $waLink }}" target="_blank"
                   class="w-full sm:w-auto flex items-center justify-center gap-2 px-7 py-4 bg-emerald-800 hover:bg-emerald-700 text-white text-sm font-bold rounded-2xl shadow-md shadow-emerald-900/20 transition-all duration-200 active:scale-95 hover:-translate-y-0.5 cursor-pointer">
                    <i class="fa-brands fa-whatsapp text-base text-emerald-400"></i>
                    Hubungi WhatsApp Kami
                </a>
                <a href="{{ route('landing') }}"
                   class="w-full sm:w-auto flex items-center justify-center gap-2 px-7 py-4 bg-stone-100 hover:bg-stone-200 text-emerald-950 text-sm font-bold rounded-2xl transition-all duration-200 cursor-pointer">
                    <i class="fa-solid fa-house text-xs text-gray-500"></i>
                    Kembali ke Beranda
                </a>
            </div>

            {{-- Helper note --}}
            <p class="text-xs text-gray-400 mt-7 leading-relaxed">
                Klik tombol WhatsApp di atas jika Anda ingin mempercepat proses konfirmasi pendaftaran.
            </p>

        </div>
    </div>

</div>
</div>
@endsection
