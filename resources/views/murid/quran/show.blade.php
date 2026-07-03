@extends('layouts.murid')

@section('title', $surah->nama_latin)

@section('content')
<div class="px-5 py-6 space-y-6" x-data="{
    arabicSize: 'text-2xl',

    /* ── Per-Ayat (sudah ada) ── */
    playingId: null,
    audioObject: null,

    /* ── Per-Surah ── */
    qari: localStorage.getItem('tpq_qari') || 'ar.alafasy',
    surahAudio: null,
    isPlayingSurah: false,
    currentTimeSec: 0,
    durationSec: 0,
    loopSurah: false,

    /* ── Highlight ── */
    ayahTimestamps: [],
    activeAyah: null,
    highlightEnabled: false,
    highlightLoading: false,

    /* offset (detik) untuk kompensasi Ta'awwudz per qari */
    highlightOffset: parseFloat(localStorage.getItem('tpq_offset') || '0'),
    qariOffsets: {
        'ar.alafasy':           0,
        'ar.abdurrahmaansudais': 4.5,
        'ar.saoodshuraym':      4.0,
        'ar.husary':            5.5,
        'ar.abushuraym':        4.0,
    },

    /* ── Mutex ── */
    globalSource: null,

    /* ── Qari Options ── */
    qariList: [
        { value: 'ar.alafasy',           label: 'Mishary Rashid Al-Afasy' },
        { value: 'ar.abdurrahmaansudais', label: 'Abdul Rahman Al-Sudais' },
        { value: 'ar.saoodshuraym',      label: 'Saad Al-Ghamdi' },
        { value: 'ar.husary',            label: 'Mahmoud Khalil Al-Husary' },
        { value: 'ar.abushuraym',        label: 'Abu Bakr Al-Shatri' },
    ],

    fmtTime(sec) {
        if (!sec || isNaN(sec)) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },

    initSurahPlayer(surahId) {
        this.destroySurahAudio();
        const url = `https://cdn.islamic.network/quran/audio-surah/128/${this.qari}/${surahId}.mp3`;
        this.surahAudio = new Audio(url);
        this.surahAudio.loop = this.loopSurah;

        this.surahAudio.addEventListener('timeupdate', () => this.onTimeUpdate());
        this.surahAudio.addEventListener('loadedmetadata', () => {
            this.durationSec = this.surahAudio.duration;
        });
        this.surahAudio.addEventListener('ended', () => {
            if (!this.loopSurah) {
                this.isPlayingSurah = false;
                this.activeAyah = null;
            }
        });

        this.loadAyahTimestamps(surahId);
    },

    destroySurahAudio() {
        if (this.surahAudio) {
            this.surahAudio.pause();
            this.surahAudio = null;
        }
        this.isPlayingSurah = false;
        this.currentTimeSec = 0;
        this.durationSec = 0;
        this.activeAyah = null;
    },

    async loadAyahTimestamps(surahId) {
        this.highlightLoading = true;
        this.highlightEnabled = false;
        this.ayahTimestamps = [];
        try {
            const res = await fetch(`https://api.alquran.cloud/v1/surah/${surahId}/ar.alafasy`);
            if (!res.ok) throw new Error('Gagal fetch');
            const data = await res.json();
            const ayahs = data.data.ayahs;

            const timestamps = [];
            let cursor = 0;
            for (const ayah of ayahs) {
                const audio = new Audio(ayah.audio);
                const dur = await new Promise(resolve => {
                    audio.addEventListener('loadedmetadata', () => resolve(audio.duration), { once: true });
                    audio.addEventListener('error', () => resolve(3), { once: true });
                    audio.load();
                });
                timestamps.push({ no: ayah.numberInSurah, startSec: cursor, endSec: cursor + dur });
                cursor += dur;
            }
            this.ayahTimestamps = timestamps;
            this.highlightEnabled = true;
        } catch (e) {
            this.highlightEnabled = false;
        } finally {
            this.highlightLoading = false;
        }
    },

    playSurah() {
        if (!this.surahAudio) return;
        this.globalSource = 'surah';
        if (this.audioObject) {
            this.audioObject.pause();
            this.playingId = null;
            this.audioObject = null;
        }
        this.surahAudio.play().catch(() => { this.isPlayingSurah = false; });
        this.isPlayingSurah = true;
    },

    pauseSurah() {
        if (this.surahAudio) this.surahAudio.pause();
        this.isPlayingSurah = false;
    },

    onTimeUpdate() {
        if (!this.surahAudio) return;
        this.currentTimeSec = this.surahAudio.currentTime;
        if (!this.highlightEnabled || this.ayahTimestamps.length === 0) return;
        /* kurangi offset Ta'awwudz agar highlight sinkron */
        const t = Math.max(0, this.currentTimeSec - this.highlightOffset);
        const found = this.ayahTimestamps.find(a => t >= a.startSec && t < a.endSec);
        if (found && found.no !== this.activeAyah) {
            this.activeAyah = found.no;
            this.$nextTick(() => {
                const el = document.getElementById(`ayah-${found.no}`);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        }
    },

    seekTo(ratio) {
        if (!this.surahAudio || !this.durationSec) return;
        this.surahAudio.currentTime = ratio * this.durationSec;
    },

    prevAyah() {
        if (!this.surahAudio || this.ayahTimestamps.length === 0) return;
        const t = this.currentTimeSec;
        const prev = [...this.ayahTimestamps].reverse().find(a => a.startSec < t - 1);
        if (prev) this.surahAudio.currentTime = prev.startSec;
        else this.surahAudio.currentTime = 0;
    },

    nextAyah() {
        if (!this.surahAudio || this.ayahTimestamps.length === 0) return;
        const t = this.currentTimeSec;
        const next = this.ayahTimestamps.find(a => a.startSec > t);
        if (next) this.surahAudio.currentTime = next.startSec;
    },

    changeQari(surahId) {
        localStorage.setItem('tpq_qari', this.qari);
        /* reset offset ke default qari yang dipilih */
        this.highlightOffset = this.qariOffsets[this.qari] ?? 0;
        localStorage.setItem('tpq_offset', this.highlightOffset);
        const wasPlaying = this.isPlayingSurah;
        this.destroySurahAudio();
        this.initSurahPlayer(surahId);
        if (wasPlaying) this.$nextTick(() => this.playSurah());
    },

    adjustOffset(delta) {
        this.highlightOffset = Math.max(0, Math.min(15, parseFloat((this.highlightOffset + delta).toFixed(1))));
        localStorage.setItem('tpq_offset', this.highlightOffset);
    },

    toggleAudio(surahId, ayahNo) {
        const targetId = `${surahId}_${ayahNo}`;

        if (this.playingId === targetId) {
            if (this.audioObject) this.audioObject.pause();
            this.playingId = null;
            return;
        }

        if (this.audioObject) {
            this.audioObject.pause();
            this.audioObject = null;
        }

        /* mutex: pause surah jika sedang main */
        if (this.isPlayingSurah) {
            this.pauseSurah();
            this.activeAyah = null;
        }
        this.globalSource = 'ayah';

        const surahStr = String(surahId).padStart(3, '0');
        const ayahStr  = String(ayahNo).padStart(3, '0');
        const audioUrl = `https://everyayah.com/data/Alafasy_128kbps/${surahStr}${ayahStr}.mp3`;

        this.playingId   = targetId;
        this.audioObject = new Audio(audioUrl);
        this.audioObject.play().catch(() => { this.playingId = null; });
        this.audioObject.addEventListener('ended', () => {
            this.playingId = null;
            this.audioObject = null;
        });
    }
}" x-init="initSurahPlayer({{ $surah->id }})">
    <!-- Navigation Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('murid.quran.index') }}" class="text-xs font-bold text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Surah</span>
        </a>

        <!-- Font size togglers -->
        <div class="flex items-center space-x-1.5 bg-white border border-gray-200 px-2 py-1 rounded-xl shadow-xs shrink-0">
            <span class="text-[9px] text-gray-400 font-bold pr-1">Ukuran:</span>
            <button @click="arabicSize = 'text-xl'" :class="arabicSize === 'text-xl' ? 'bg-emerald-700 text-white' : 'text-gray-500'" class="w-6 h-6 rounded-lg text-xs font-bold transition">A-</button>
            <button @click="arabicSize = 'text-2xl'" :class="arabicSize === 'text-2xl' ? 'bg-emerald-700 text-white' : 'text-gray-500'" class="w-6 h-6 rounded-lg text-xs font-bold transition">A</button>
            <button @click="arabicSize = 'text-3xl'" :class="arabicSize === 'text-3xl' ? 'bg-emerald-700 text-white' : 'text-gray-500'" class="w-6 h-6 rounded-lg text-xs font-bold transition">A+</button>
        </div>
    </div>

    <!-- Surah Intro Card -->
    <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-6 text-center shadow-md relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-24 h-24 bg-amber-400 opacity-15 rounded-full blur-lg"></div>
        <div class="relative z-10 space-y-2">
            <h2 class="font-extrabold text-lg text-amber-300">{{ $surah->nama_latin }}</h2>
            <p class="text-[10px] text-emerald-200 uppercase tracking-widest font-semibold">
                {{ $surah->arti }} &bull; {{ $surah->tempat_turun == 'Mekah' ? 'Makkiyyah' : 'Madaniyyah' }} &bull; {{ $surah->jumlah_ayat }} Ayat
            </p>
            
            @if($surah->id != 1 && $surah->id != 9)
                <div class="pt-4 border-t border-emerald-800 mt-4">
                    <p class="arabic-text text-xl text-amber-100">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Full Surah Audio Player Panel -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-4 space-y-3">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-headphones"></i>
                </div>
                <span class="text-xs font-bold text-gray-800">Dengarkan Surah</span>
            </div>
            <span x-show="highlightLoading" class="text-[9px] text-emerald-600 font-medium animate-pulse">
                <i class="fa-solid fa-circle-notch fa-spin mr-1"></i>Memuat highlight...
            </span>
        </div>

        <!-- Pilih Qari -->
        <div>
            <label class="text-[9px] text-gray-400 font-semibold uppercase tracking-wider block mb-1">Pilih Qari</label>
            <select
                x-model="qari"
                @change="changeQari({{ $surah->id }})"
                class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400"
            >
                <template x-for="q in qariList" :key="q.value">
                    <option :value="q.value" x-text="q.label"></option>
                </template>
            </select>
        </div>

        <!-- Progress Bar -->
        <div class="space-y-1">
            <div
                class="w-full h-2 bg-gray-100 rounded-full cursor-pointer relative overflow-hidden"
                @click="seekTo($event.offsetX / $el.offsetWidth)"
            >
                <div
                    class="h-full bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-full transition-all duration-200"
                    :style="'width: ' + (durationSec > 0 ? (currentTimeSec / durationSec * 100) : 0) + '%'"
                ></div>
            </div>
            <div class="flex justify-between text-[9px] text-gray-400 font-mono">
                <span x-text="fmtTime(currentTimeSec)">0:00</span>
                <span x-text="fmtTime(durationSec)">0:00</span>
            </div>
        </div>

        <!-- Kontrol -->
        <div class="flex items-center justify-center space-x-4">
            <!-- Prev Ayat -->
            <button
                type="button"
                @click="prevAyah()"
                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-700 transition text-xs"
                title="Ayat Sebelumnya"
            >
                <i class="fa-solid fa-backward-step"></i>
            </button>

            <!-- Play / Pause -->
            <button
                type="button"
                @click="isPlayingSurah ? pauseSurah() : playSurah()"
                class="w-11 h-11 rounded-full bg-emerald-700 text-white flex items-center justify-center hover:bg-emerald-800 transition shadow-md text-sm"
            >
                <i class="fa-solid" :class="isPlayingSurah ? 'fa-pause' : 'fa-play'"></i>
            </button>

            <!-- Next Ayat -->
            <button
                type="button"
                @click="nextAyah()"
                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-700 transition text-xs"
                title="Ayat Berikutnya"
            >
                <i class="fa-solid fa-forward-step"></i>
            </button>
        </div>

        <!-- Baris Bawah: Loop + Sinkronisasi Offset -->
        <div class="flex items-center justify-between pt-1 border-t border-gray-50">
            <button
                type="button"
                @click="loopSurah = !loopSurah; if (surahAudio) surahAudio.loop = loopSurah"
                :class="loopSurah ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                class="flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[9px] font-semibold transition"
            >
                <i class="fa-solid fa-repeat text-[9px]"></i>
                <span>Ulangi</span>
            </button>

            <!-- Kontrol sinkronisasi offset highlight -->
            <div x-show="highlightEnabled" class="flex items-center space-x-1">
                <span class="text-[9px] text-gray-400 mr-1">Sinkron:</span>
                <button
                    type="button"
                    @click="adjustOffset(-0.5)"
                    class="w-5 h-5 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-[9px] hover:bg-red-50 hover:text-red-500 transition font-bold"
                    title="Geser highlight lebih awal"
                >−</button>
                <span class="text-[9px] font-mono text-emerald-700 min-w-[28px] text-center" x-text="highlightOffset.toFixed(1) + 's'">0.0s</span>
                <button
                    type="button"
                    @click="adjustOffset(0.5)"
                    class="w-5 h-5 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-[9px] hover:bg-emerald-50 hover:text-emerald-600 transition font-bold"
                    title="Geser highlight lebih lambat"
                >+</button>
            </div>

            <span x-show="!highlightEnabled && !highlightLoading" class="text-[9px] text-gray-400">
                Highlight tidak tersedia
            </span>
        </div>
    </div>

    <!-- Verses List -->
    <div class="space-y-4">
        @foreach($surah->ayats as $ayat)
            <div
                id="ayah-{{ $ayat->nomor_ayat }}"
                class="bg-white rounded-2xl p-5 border shadow-xs space-y-4 transition-all duration-300"
                :class="activeAyah === {{ $ayat->nomor_ayat }}
                    ? 'border-emerald-400 bg-emerald-50 border-l-4'
                    : 'border-gray-100'"
            >
                <!-- Verse Top bar -->
                <div class="flex items-center justify-between border-b border-gray-50 pb-2.5">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold flex items-center justify-center shrink-0">
                            {{ $ayat->nomor_ayat }}
                        </div>
                        
                        <!-- Murottal Audio Player Trigger -->
                        <button type="button" 
                                @click="toggleAudio({{ $surah->id }}, {{ $ayat->nomor_ayat }})"
                                class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-850 flex items-center justify-center hover:bg-emerald-100 transition shrink-0 select-none">
                            <i class="fa-solid text-[9px] pointer-events-none" 
                               :class="playingId === '{{ $surah->id }}_{{ $ayat->nomor_ayat }}' ? 'fa-pause text-amber-500' : 'fa-play'"></i>
                        </button>
                    </div>

                    <div class="flex space-x-2">
                        <span class="text-[9px] text-gray-400 font-semibold uppercase tracking-wider">Surah {{ $surah->id }}:{{ $ayat->nomor_ayat }}</span>
                    </div>
                </div>

                <!-- Verse Arabic text -->
                <div class="text-right">
                    <span dir="rtl" class="arabic-text font-bold text-emerald-950 leading-loose block" :class="arabicSize">
                        {{ $ayat->teks_arab }}
                    </span>
                </div>

                <!-- Verse Transliterasi (Latin) -->
                @if($ayat->teks_latin)
                    <p class="text-[10px] text-emerald-700 italic leading-relaxed">
                        {{ $ayat->teks_latin }}
                    </p>
                @endif

                <!-- Verse Translation -->
                <p class="text-[11px] text-gray-600 leading-relaxed font-medium">
                    {{ $ayat->terjemahan }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
