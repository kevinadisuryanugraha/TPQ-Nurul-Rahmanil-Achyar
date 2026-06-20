@extends('layouts.murid')

@section('title', 'Nilaiku')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ activeTab: 'baca' }">
    <!-- Header Title -->
    <div class="flex items-center space-x-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-star"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Hasil Belajarku</h2>
            <p class="text-[10px] text-gray-500">Pantau seluruh nilai dan rekam perkembangan belajarmu</p>
        </div>
    </div>

    <!-- Tab buttons -->
    <div class="flex items-center space-x-1.5 bg-gray-150 p-1 rounded-2xl shrink-0">
        <button @click="activeTab = 'baca'" :class="activeTab === 'baca' ? 'bg-white text-emerald-800 font-bold shadow-xs' : 'text-gray-500'"
            class="flex-1 py-2 text-[10px] rounded-xl transition text-center focus:outline-none">
            Baca
        </button>
        <button @click="activeTab = 'hafalan'" :class="activeTab === 'hafalan' ? 'bg-white text-emerald-800 font-bold shadow-xs' : 'text-gray-500'"
            class="flex-1 py-2 text-[10px] rounded-xl transition text-center focus:outline-none">
            Hafalan
        </button>
        <button @click="activeTab = 'tulis'" :class="activeTab === 'tulis' ? 'bg-white text-emerald-800 font-bold shadow-xs' : 'text-gray-500'"
            class="flex-1 py-2 text-[10px] rounded-xl transition text-center focus:outline-none">
            Tulis
        </button>
        <button @click="activeTab = 'praktik'" :class="activeTab === 'praktik' ? 'bg-white text-emerald-800 font-bold shadow-xs' : 'text-gray-500'"
            class="flex-1 py-2 text-[10px] rounded-xl transition text-center focus:outline-none">
            Ibadah
        </button>
    </div>

    <!-- TAB BACA -->
    <div x-show="activeTab === 'baca'" class="space-y-3">
        @forelse($bacas as $baca)
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 font-extrabold flex items-center justify-center border border-emerald-100 shadow-xs text-sm shrink-0">
                    {{ $baca->nilai }}
                </div>
                <div class="flex-1 min-w-0 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-[11px] text-gray-900 truncate">
                            {{ $baca->surah_bacaan ? 'Al-Qur\'an: ' . $baca->surah_bacaan : 'Buku Iqra' }}
                        </h4>
                        <span class="text-[8px] text-gray-400 shrink-0 font-semibold">{{ $baca->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    
                    @if($baca->ayat_bacaan || $baca->jilid_halaman)
                        <p class="text-[9px] text-emerald-800 font-bold">
                            @if($baca->ayat_bacaan) Ayat: {{ $baca->ayat_bacaan }} &bull; @endif
                            @if($baca->jilid_halaman) Jilid/Hal: {{ $baca->jilid_halaman }} @endif
                        </p>
                    @endif

                    <p class="text-[10px] text-gray-500 leading-relaxed italic">
                        <strong>Catatan:</strong> "{{ $baca->catatan ?? 'Belum ada catatan' }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                <i class="fa-solid fa-book-quran text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Belum ada penilaian bacaan.</p>
            </div>
        @endforelse
    </div>

    <!-- TAB HAFALAN -->
    <div x-show="activeTab === 'hafalan'" class="space-y-3">
        @forelse($hafalans as $hafalan)
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 font-extrabold flex items-center justify-center border border-amber-100 shadow-xs text-sm shrink-0">
                    {{ $hafalan->nilai }}
                </div>
                <div class="flex-1 min-w-0 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-[11px] text-gray-900 truncate">
                            {{ $hafalan->surah_hafalan ?? $hafalan->hadist_hafalan ?? $hafalan->doa_hafalan ?? 'Hafalan' }}
                        </h4>
                        <span class="text-[8px] text-gray-400 shrink-0 font-semibold">{{ $hafalan->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    
                    <span class="text-[8px] bg-amber-50 border border-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                        {{ $hafalan->tipe_materi }}
                    </span>

                    <p class="text-[10px] text-gray-500 leading-relaxed italic">
                        <strong>Catatan:</strong> "{{ $hafalan->catatan ?? 'Belum ada catatan' }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                <i class="fa-solid fa-hands-praying text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Belum ada penilaian hafalan.</p>
            </div>
        @endforelse
    </div>

    <!-- TAB TULIS -->
    <div x-show="activeTab === 'tulis'" class="space-y-3">
        @forelse($tulises as $tulis)
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 font-extrabold flex items-center justify-center border border-blue-100 shadow-xs text-sm shrink-0">
                    {{ $tulis->nilai }}
                </div>
                <div class="flex-1 min-w-0 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-extrabold text-[11px] text-gray-900">
                            Predikat: 
                            <strong class="text-blue-700 font-extrabold">
                                @if($tulis->nilai >= 85) A
                                @elseif($tulis->nilai >= 75) B
                                @elseif($tulis->nilai >= 60) C
                                @else D
                                @endif
                            </strong>
                        </h4>
                        <span class="text-[8px] text-gray-400 shrink-0 font-semibold">{{ $tulis->created_at->translatedFormat('d M Y') }}</span>
                    </div>

                    <p class="text-[10px] text-gray-500 leading-relaxed italic">
                        <strong>Catatan:</strong> "{{ $tulis->catatan ?? 'Belum ada catatan' }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                <i class="fa-solid fa-pen-to-square text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Belum ada penilaian menulis.</p>
            </div>
        @endforelse
    </div>

    <!-- TAB PRACTICAL IBADAH -->
    <div x-show="activeTab === 'praktik'" class="space-y-3" x-data="{ openDetailId: null }">
        @forelse($praktiks as $prk)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs">
                <!-- Header -->
                <button @click="openDetailId = (openDetailId === {{ $prk->id }} ? null : {{ $prk->id }})"
                    class="w-full p-4 flex items-start gap-4 text-left focus:outline-none">
                    
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 font-extrabold flex items-center justify-center border border-rose-100 shadow-xs text-sm shrink-0">
                        {{ $prk->nilai }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="font-extrabold text-[11px] text-gray-900 truncate leading-snug">{{ $prk->judul_praktik }}</h4>
                            <span class="text-[8px] text-gray-400 font-semibold shrink-0">{{ $prk->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <span class="text-[8px] text-rose-700 font-bold block mt-0.5">Ketuk detail untuk checklist</span>
                    </div>
                </button>

                <!-- Detailed checklists -->
                <div x-show="openDetailId === {{ $prk->id }}" x-collapse
                    class="bg-rose-50/10 p-4 border-t border-gray-50 space-y-3">
                    
                    <div>
                        <span class="text-[9px] font-bold text-gray-400 block mb-1">Checklist Gerakan / Rukun:</span>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($prk->komponenChecklist as $comp)
                                <div class="flex items-center space-x-1.5">
                                    @if($comp->status_lulus)
                                        <i class="fa-solid fa-circle-check text-green-600 text-[10px]"></i>
                                        <span class="text-[9px] text-gray-700 font-semibold">{{ $comp->nama_komponen }}</span>
                                    @else
                                        <i class="fa-solid fa-circle-xmark text-gray-300 text-[10px]"></i>
                                        <span class="text-[9px] text-gray-400 line-through">{{ $comp->nama_komponen }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-[10px] text-gray-500 italic leading-relaxed pt-2 border-t border-gray-50">
                        <strong>Catatan:</strong> "{{ $prk->catatan ?? 'Belum ada catatan' }}"
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                <i class="fa-solid fa-compass text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Belum ada penilaian praktik ibadah.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
