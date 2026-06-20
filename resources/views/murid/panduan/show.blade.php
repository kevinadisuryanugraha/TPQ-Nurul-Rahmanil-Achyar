@extends('layouts.murid')

@section('title', $panduan->judul)

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ 
    activeStep: 0,
    totalSteps: {{ $panduan->langkahs->count() }},
    next() {
        if (this.activeStep < this.totalSteps - 1) this.activeStep++;
    },
    prev() {
        if (this.activeStep > 0) this.activeStep--;
    }
}">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('murid.panduan.index') }}" class="text-xs font-bold text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Panduan</span>
        </a>
        
        <span class="text-[9px] bg-rose-50 text-rose-800 border border-rose-100 font-bold px-2 py-0.5 rounded uppercase">
            {{ $panduan->jenis_praktik }}
        </span>
    </div>

    <!-- Active Step Slider Box -->
    @if($panduan->langkahs->count() > 0)
        <div class="bg-white rounded-3xl overflow-hidden border border-gray-150 shadow-xs flex flex-col justify-between min-h-[400px]">
            
            <!-- Progress Line -->
            <div class="w-full bg-gray-100 h-1.5 shrink-0">
                <div class="bg-emerald-600 h-1.5 transition-all duration-300"
                    :style="'width: ' + (((activeStep + 1) / totalSteps) * 100) + '%'"></div>
            </div>

            <!-- Steps Slider container -->
            <div class="p-5 flex-1 flex flex-col justify-center">
                @foreach($panduan->langkahs as $index => $langkah)
                    <div x-show="activeStep === {{ $index }}" x-transition.opacity.duration.300ms class="space-y-5">
                        <!-- Step Image Illustration -->
                        <div class="w-full h-48 rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center">
                            @if($langkah->gambar)
                                <img src="{{ $langkah->gambar }}" alt="Langkah {{ $langkah->nomor_urut }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center text-gray-300">
                                    <i class="fa-solid fa-compass text-5xl text-rose-200 mb-2"></i>
                                    <p class="text-[9px] font-semibold uppercase tracking-wider text-gray-400">Ilustrasi Belum Tersedia</p>
                                </div>
                            @endif
                        </div>

                        <!-- Step Info -->
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[10px] flex items-center justify-center border border-emerald-200 shadow-xs shrink-0">
                                    {{ $langkah->nomor_urut }}
                                </span>
                                <h3 class="font-extrabold text-xs text-gray-900 leading-snug">{{ $langkah->judul_langkah }}</h3>
                            </div>
                            <p class="text-[10px] text-gray-500 leading-relaxed font-semibold">
                                {{ $langkah->deskripsi }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Navigation Bar inside Slider -->
            <div class="p-5 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between shrink-0">
                <button @click="prev()" :disabled="activeStep === 0" 
                    :class="activeStep === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-emerald-800 hover:text-emerald-950 font-bold'"
                    class="text-xs transition flex items-center space-x-1 py-1">
                    <i class="fa-solid fa-angle-left"></i>
                    <span>Sebelumnya</span>
                </button>

                <span class="text-[9px] text-gray-500 font-bold">
                    Langkah <span x-text="activeStep + 1" class="text-emerald-700 font-extrabold"></span> dari <span x-text="totalSteps" class="font-extrabold"></span>
                </span>

                <!-- Next button -->
                <button @click="next()" x-show="activeStep < totalSteps - 1"
                    class="text-xs text-emerald-800 hover:text-emerald-950 font-bold transition flex items-center space-x-1 py-1">
                    <span>Berikutnya</span>
                    <i class="fa-solid fa-angle-right"></i>
                </button>
                
                <!-- Complete state -->
                <a href="{{ route('murid.panduan.index') }}" x-show="activeStep === totalSteps - 1"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-1.5 rounded-xl text-[10px] transition shadow-sm inline-flex items-center space-x-1">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Selesai Belajar</span>
                </a>
            </div>

        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-8 text-center text-gray-400">
            <i class="fa-solid fa-compass text-3xl text-gray-300 mb-2"></i>
            <p class="text-xs">Langkah panduan belum diunggah.</p>
        </div>
    @endif
</div>
@endsection
