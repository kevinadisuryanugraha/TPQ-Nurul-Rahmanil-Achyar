@extends('layouts.murid')

@section('title', $surah->nama_latin)

@section('content')
<div class="px-5 py-6 space-y-6" x-data="{
    arabicSize: 'text-2xl',

    /* ── Per-Ayat kecil (tombol ▶ per kartu) ── */
    playingId: null,
    audioObject: null,

    /* ── Single Continuous Surah Player ── */
    qari: localStorage.getItem('tpq_qari_v3') || '7',
    qariList: [
        { value: '7',  label: 'Mishary Rashid Al-Afasy' },
        { value: '3',  label: 'Abdul Rahman Al-Sudais' },
        { value: '13', label: 'Saad Al-Ghamdi' },
        { value: '6',  label: 'Mahmoud Khalil Al-Husary' },
        { value: '4',  label: 'Abu Bakr Al-Shatri' },
    ],

    surahAudio: null,       /* Audio object for the full surah */
    audioUrl: '',           /* Single MP3 URL */
    timestamps: [],         /* Timing segments for each verse */
    playlistLoading: false,
    isPlayingSurah: false,
    activeAyah: null,
    currentTimeSec: 0,
    durationSec: 0,
    loopSurah: false,
    globalSource: null,

    /* ─────────────── Helpers ─────────────── */

    fmtTime(sec) {
        if (!sec || isNaN(sec)) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },

    progressPercent() {
        if (!this.durationSec) return 0;
        return (this.currentTimeSec / this.durationSec) * 100;
    },

    getContinuousAudioUrl(qari, surahId) {
        const padId = String(surahId).padStart(3, '0');
        switch (qari) {
            case '7': // Mishary Rashid Al-Afasy
                return `https://download.quranicaudio.com/quran/mishaari_raashid_al_3afaasee/${padId}.mp3`;
            case '3': // Abdul Rahman Al-Sudais
                return `https://download.quranicaudio.com/quran/abdurrahmaan_as-sudays/${padId}.mp3`;
            case '13': // Saad Al-Ghamdi
                return `https://download.quranicaudio.com/quran/sa3d_al-ghaamidi/complete//${padId}.mp3`;
            case '6': // Mahmoud Khalil Al-Husary
                return `https://download.quranicaudio.com/quran/mahmood_khaleel_al-husaree/${padId}.mp3`;
            case '4': // Abu Bakr Al-Shatri
                return `https://download.quranicaudio.com/quran/abu_bakr_ash-shaatree/${padId}.mp3`;
            default:
                return '';
        }
    },

    adjustTimestampsForBismillah(surahId) {
        if (this.timestamps.length === 0) return;
        
        let offsetMs = 0;
        if (surahId !== 1 && surahId !== 9) {
            const qariOffsets = {
                '7': 6090,  // Mishary Rashid Al-Afasy (6.09s)
                '3': 3080,  // Abdul Rahman Al-Sudais (3.08s)
                '4': 6493,  // Abu Bakr Al-Shatri (6.49s)
                '13': 0,    // Saad Al-Ghamdi (Sudah terintegrasi di API)
                '6': 0,     // Mahmoud Khalil Al-Husary (Sudah terintegrasi di API)
            };
            offsetMs = qariOffsets[this.qari] || 0;
        }

        if (offsetMs > 0) {
            this.timestamps = this.timestamps.map((t, idx) => {
                return {
                    verse_key: t.verse_key,
                    timestamp_from: idx === 0 ? 0 : t.timestamp_from + offsetMs,
                    timestamp_to: t.timestamp_to + offsetMs,
                    duration: t.duration
                };
            });
        }
    },

    /* ─────────────── Setup Player ─────────────── */

    async buildPlaylist(surahId) {
        this.playlistLoading = true;
        this.timestamps = [];
        this.audioUrl = '';
        try {
            const res = await fetch(`https://api.quran.com/api/v4/chapter_recitations/${this.qari}/${surahId}?segments=true`);
            if (!res.ok) throw new Error();
            const data = await res.json();
            
            /* Gunakan URL berkas asli continuous jika terdefinisi, jika tidak fallback ke API */
            this.audioUrl = this.getContinuousAudioUrl(this.qari, surahId) || data.audio_file.audio_url;
            this.timestamps = data.audio_file.timestamps || [];
            
            /* Terapkan pergeseran Bismillah secara instan jika Qari memerlukan */
            this.adjustTimestampsForBismillah(surahId);
            
            /* Inisialisasi Audio Object */
            this.surahAudio = new Audio(this.audioUrl);
            this.surahAudio.loop = this.loopSurah;
            
            this.surahAudio.addEventListener('loadedmetadata', () => {
                if (!this.surahAudio) return;
                this.durationSec = this.surahAudio.duration;
            });
            
            this.surahAudio.addEventListener('timeupdate', () => {
                if (!this.surahAudio) return;
                this.currentTimeSec = this.surahAudio.currentTime;
                this.updateHighlight();
            });
            
            this.surahAudio.addEventListener('ended', () => {
                if (!this.surahAudio) return;
                if (!this.loopSurah) {
                    this.isPlayingSurah = false;
                    this.activeAyah = null;
                    this.currentTimeSec = 0;
                }
            });
        } catch (e) {
            console.error('Gagal memuat audio surah:', e);
        } finally {
            this.playlistLoading = false;
        }
    },

    destroySurahAudio() {
        if (this.surahAudio) {
            this.surahAudio.pause();
            this.surahAudio = null;
        }
        this.isPlayingSurah = false;
        this.activeAyah = null;
        this.currentTimeSec = 0;
        this.durationSec = 0;
    },

    /* ─────────────── Playback Control ─────────────── */

    playSurah() {
        if (!this.surahAudio) return;
        this.globalSource = 'surah';
        
        /* Hentikan pemutaran per ayat */
        if (this.audioObject) {
            this.audioObject.pause();
            this.playingId = null;
            this.audioObject = null;
        }

        this.surahAudio.play().catch(() => {
            this.isPlayingSurah = false;
        });
        this.isPlayingSurah = true;
    },

    pauseSurah() {
        if (this.surahAudio) this.surahAudio.pause();
        this.isPlayingSurah = false;
    },

    seekTo(ratio) {
        if (!this.surahAudio || !this.durationSec) return;
        this.surahAudio.currentTime = ratio * this.durationSec;
    },

    seekToAyah(ayahNo) {
        if (!this.surahAudio || this.timestamps.length === 0) return;
        const target = this.timestamps.find(t => {
            const parts = t.verse_key.split(':');
            return parseInt(parts[1]) === ayahNo;
        });
        if (target) {
            this.surahAudio.currentTime = target.timestamp_from / 1000;
            this.activeAyah = ayahNo;
        }
    },

    prevAyah() {
        if (!this.surahAudio || this.timestamps.length === 0) return;
        const curAyah = this.activeAyah || 1;
        const prev = Math.max(1, curAyah - 1);
        this.seekToAyah(prev);
    },

    nextAyah() {
        if (!this.surahAudio || this.timestamps.length === 0) return;
        const curAyah = this.activeAyah || 1;
        const next = Math.min(this.timestamps.length, curAyah + 1);
        this.seekToAyah(next);
    },

    async changeQari(surahId) {
        localStorage.setItem('tpq_qari_v3', this.qari);
        const wasPlaying = this.isPlayingSurah;
        this.destroySurahAudio();
        await this.buildPlaylist(surahId);
        if (wasPlaying) {
            this.$nextTick(() => this.playSurah());
        }
    },

    updateHighlight() {
        if (this.timestamps.length === 0) return;
        const ms = this.currentTimeSec * 1000;
        const found = this.timestamps.find(t => ms >= t.timestamp_from && ms < t.timestamp_to);
        if (found) {
            const parts = found.verse_key.split(':');
            const ayahNo = parseInt(parts[1]);
            if (ayahNo !== this.activeAyah) {
                this.activeAyah = ayahNo;
                this.$nextTick(() => {
                    const el = document.getElementById(`ayah-${ayahNo}`);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        }
    },

    /* ─────────────── Per-Ayat kecil ─────────────── */

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
}" x-init="buildPlaylist({{ $surah->id }})">

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
                    <p class="arabic-text text-xl text-amber-100">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
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
            <span x-show="playlistLoading" class="text-[9px] text-emerald-600 font-medium animate-pulse">
                <i class="fa-solid fa-circle-notch fa-spin mr-1"></i>Memuat...
            </span>
            <span x-show="!playlistLoading && activeAyah" class="text-[9px] font-semibold text-emerald-700">
                Ayat <span x-text="activeAyah"></span> / {{ $surah->jumlah_ayat }}
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

        <!-- Progress Bar (surah-based) -->
        <div class="space-y-1">
            <!-- Track klikable: klik loncat ke posisi audio secara presisi -->
            <div
                class="w-full h-2.5 rounded-full cursor-pointer relative"
                style="background-color: #e5e7eb;"
                @click="seekTo($event.offsetX / $el.offsetWidth)"
            >
                <div
                    class="h-full rounded-full transition-all duration-300"
                    style="background-color: #059669;"
                    :style="'width: ' + progressPercent() + '%'"
                ></div>
            </div>
            <div class="flex justify-between text-[9px] text-gray-400 font-mono">
                <span x-text="fmtTime(currentTimeSec)">0:00</span>
                <span x-text="fmtTime(durationSec)">0:00</span>
            </div>
        </div>

        <!-- Kontrol -->
        <div class="flex items-center justify-center space-x-4">
            <button
                type="button"
                @click="prevAyah()"
                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-700 transition text-xs"
                title="Ayat Sebelumnya"
            >
                <i class="fa-solid fa-backward-step"></i>
            </button>

            <button
                type="button"
                @click="isPlayingSurah ? pauseSurah() : playSurah()"
                :disabled="playlistLoading"
                class="w-11 h-11 rounded-full bg-emerald-700 text-white flex items-center justify-center hover:bg-emerald-800 transition shadow-md text-sm disabled:opacity-50"
            >
                <i class="fa-solid" :class="isPlayingSurah ? 'fa-pause' : 'fa-play'"></i>
            </button>

            <button
                type="button"
                @click="nextAyah()"
                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-700 transition text-xs"
                title="Ayat Berikutnya"
            >
                <i class="fa-solid fa-forward-step"></i>
            </button>
        </div>

        <!-- Loop toggle -->
        <div class="flex items-center justify-between pt-1 border-t border-gray-50">
            <button
                type="button"
                @click="loopSurah = !loopSurah; if(surahAudio) surahAudio.loop = loopSurah"
                :class="loopSurah ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                class="flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[9px] font-semibold transition"
            >
                <i class="fa-solid fa-repeat text-[9px]"></i>
                <span>Ulangi</span>
            </button>
            <span class="text-[9px] text-gray-400 italic">Highlight otomatis per ayat</span>
        </div>
    </div>

    <!-- Verses List -->
    <div class="space-y-4 transition-all duration-300" :class="surahAudio !== null ? 'pb-24' : ''">
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
                        <!-- Nomor ayat: tap untuk loncat ke ayat ini saat surah main -->
                        <button
                            type="button"
                            @click="isPlayingSurah ? seekToAyah({{ $ayat->nomor_ayat }}) : null"
                            class="w-6 h-6 rounded-full text-[10px] font-bold flex items-center justify-center shrink-0 transition"
                            :class="activeAyah === {{ $ayat->nomor_ayat }}
                                ? 'bg-emerald-600 text-white ring-2 ring-emerald-300'
                                : 'bg-emerald-50 text-emerald-800'"
                            :title="isPlayingSurah ? 'Loncat ke ayat {{ $ayat->nomor_ayat }}' : ''"
                        >
                            {{ $ayat->nomor_ayat }}
                        </button>

                        <!-- Murottal Audio Player Trigger (per-ayat) -->
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
    <!-- Floating Mini-Player (Spotify-style) -->
    <div
        x-show="surahAudio !== null"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-[74px] left-1/2 -translate-x-1/2 w-full max-w-[440px] px-4 z-40"
        style="max-width: 440px;"
    >
        <div class="bg-white/95 backdrop-blur-md border border-gray-150 rounded-2xl shadow-xl p-3 relative overflow-hidden flex items-center justify-between animate-fade-in">
            <!-- Progress Bar Tipis di Atas Card -->
            <div
                class="absolute top-0 left-0 right-0 h-1 cursor-pointer"
                style="background-color: #f3f4f6;"
                @click="seekTo($event.offsetX / $el.offsetWidth)"
            >
                <div
                    class="h-full transition-all duration-300"
                    style="background-color: #059669;"
                    :style="'width: ' + progressPercent() + '%'"
                ></div>
            </div>

            <!-- Detail Lagu / Ayat -->
            <div class="flex flex-col space-y-0.5 min-w-0 flex-1 pr-3">
                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Sedang Diputar</span>
                <h4 class="text-xs font-extrabold text-gray-900 truncate">
                    {{ $surah->nama_latin }}
                </h4>
                <p class="text-[9px] text-gray-500 font-medium">
                    Qari: <span x-text="qariList.find(q => q.value === qari)?.label.split(' ').pop() || ''"></span> &bull; Ayat <span x-text="activeAyah"></span>
                </p>
            </div>

            <!-- Kontrol Audio Cepat -->
            <div class="flex items-center space-x-2 shrink-0">
                <!-- Prev -->
                <button
                    type="button"
                    @click="prevAyah()"
                    class="w-7 h-7 rounded-full bg-gray-55 text-gray-655 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-700 transition text-[10px]"
                >
                    <i class="fa-solid fa-backward-step"></i>
                </button>

                <!-- Play / Pause -->
                <button
                    type="button"
                    @click="isPlayingSurah ? pauseSurah() : playSurah()"
                    class="w-9 h-9 rounded-full bg-emerald-700 text-white flex items-center justify-center hover:bg-emerald-800 transition shadow-sm text-xs"
                >
                    <i class="fa-solid" :class="isPlayingSurah ? 'fa-pause' : 'fa-play'"></i>
                </button>

                <!-- Next -->
                <button
                    type="button"
                    @click="nextAyah()"
                    class="w-7 h-7 rounded-full bg-gray-55 text-gray-655 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-700 transition text-[10px]"
                >
                    <i class="fa-solid fa-forward-step"></i>
                </button>

                <!-- Close / Stop Playlist -->
                <button
                    type="button"
                    @click="destroySurahAudio()"
                    class="w-6 h-6 rounded-full text-gray-455 hover:text-gray-655 transition flex items-center justify-center text-[10px] ml-1"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
