@extends('layouts.murid')

@section('title', $surah->nama_latin)

@section('content')
<div class="px-5 py-6 space-y-6" x-data="{
    arabicSize: 'text-2xl',

    /* ── Per-Ayat kecil (tombol ▶ per kartu) ── */
    playingId: null,
    audioObject: null,

    /* ── Playlist Sequential Per-Surah ── */
    qari: localStorage.getItem('tpq_qari') || 'ar.alafasy',
    qariList: [
        { value: 'ar.alafasy',            label: 'Mishary Rashid Al-Afasy' },
        { value: 'ar.abdurrahmaansudais', label: 'Abdul Rahman Al-Sudais' },
        { value: 'ar.saoodshuraym',       label: 'Saad Al-Ghamdi' },
        { value: 'ar.husary',             label: 'Mahmoud Khalil Al-Husary' },
        { value: 'ar.abushuraym',         label: 'Abu Bakr Al-Shatri' },
    ],

    playlist: [],           /* [{no, url}] per-ayat audio URLs */
    playlistIndex: -1,      /* index ayat yang sedang diputar */
    playlistAudio: null,    /* Audio object yang aktif */
    playlistLoading: false,
    isPlayingSurah: false,
    activeAyah: null,
    currentTimeSec: 0,
    currentAyahDur: 0,
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
        if (this.playlist.length === 0) return 0;
        const base = this.playlistIndex / this.playlist.length * 100;
        const within = this.currentAyahDur > 0
            ? (this.currentTimeSec / this.currentAyahDur) * (1 / this.playlist.length) * 100
            : 0;
        return Math.min(100, base + within);
    },

    /* ─────────────── Playlist Setup ─────────────── */

    async buildPlaylist(surahId) {
        this.playlistLoading = true;
        this.playlist = [];
        try {
            const res = await fetch(`https://api.alquran.cloud/v1/surah/${surahId}/${this.qari}`);
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.playlist = data.data.ayahs.map(a => ({ no: a.numberInSurah, url: a.audio }));
        } catch {
            /* fallback ke alafasy jika qari tidak tersedia di API */
            try {
                const res = await fetch(`https://api.alquran.cloud/v1/surah/${surahId}/ar.alafasy`);
                const data = await res.json();
                this.playlist = data.data.ayahs.map(a => ({ no: a.numberInSurah, url: a.audio }));
            } catch { /* tetap kosong, tombol play tidak akan berfungsi */ }
        } finally {
            this.playlistLoading = false;
        }
    },

    /* ─────────────── Playback Control ─────────────── */

    playFromIndex(index) {
        /* hentikan audio yang sedang main */
        if (this.playlistAudio) {
            this.playlistAudio.pause();
            this.playlistAudio.src = '';
            this.playlistAudio = null;
        }

        if (index < 0 || index >= this.playlist.length) {
            this.isPlayingSurah = false;
            this.activeAyah = null;
            this.playlistIndex = -1;
            this.currentTimeSec = 0;
            this.currentAyahDur = 0;
            return;
        }

        this.playlistIndex = index;
        const entry = this.playlist[index];
        this.activeAyah = entry.no;
        this.currentTimeSec = 0;
        this.currentAyahDur = 0;

        const audio = new Audio(entry.url);
        this.playlistAudio = audio;

        audio.addEventListener('loadedmetadata', () => {
            this.currentAyahDur = audio.duration;
        });

        audio.addEventListener('timeupdate', () => {
            this.currentTimeSec = audio.currentTime;
        });

        audio.addEventListener('ended', () => {
            const next = index + 1;
            if (next < this.playlist.length) {
                this.playFromIndex(next);
            } else if (this.loopSurah) {
                this.playFromIndex(0);
            } else {
                this.isPlayingSurah = false;
                this.activeAyah = null;
                this.playlistIndex = -1;
                this.currentTimeSec = 0;
                this.currentAyahDur = 0;
            }
        });

        audio.play().catch(() => {
            this.isPlayingSurah = false;
        });
        this.isPlayingSurah = true;

        /* scroll ke ayat aktif */
        this.$nextTick(() => {
            const el = document.getElementById(`ayah-${entry.no}`);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    },

    playSurah() {
        /* mutex: hentikan per-ayat kecil */
        this.globalSource = 'surah';
        if (this.audioObject) {
            this.audioObject.pause();
            this.playingId = null;
            this.audioObject = null;
        }

        if (this.playlistAudio && !this.isPlayingSurah) {
            /* resume dari pause */
            this.playlistAudio.play().catch(() => {});
            this.isPlayingSurah = true;
        } else {
            /* mulai dari awal atau lanjut dari index terakhir */
            const startIdx = (this.playlistIndex >= 0 && this.playlistIndex < this.playlist.length)
                ? this.playlistIndex : 0;
            this.playFromIndex(startIdx);
        }
    },

    pauseSurah() {
        if (this.playlistAudio) this.playlistAudio.pause();
        this.isPlayingSurah = false;
    },

    prevAyah() {
        if (this.playlist.length === 0) return;
        const target = Math.max(0, this.playlistIndex <= 0 ? 0 : this.playlistIndex - 1);
        this.playFromIndex(target);
    },

    nextAyah() {
        if (this.playlist.length === 0) return;
        const target = Math.min(this.playlist.length - 1, this.playlistIndex + 1);
        this.playFromIndex(target);
    },

    async changeQari(surahId) {
        localStorage.setItem('tpq_qari', this.qari);
        const wasPlaying = this.isPlayingSurah;
        this.pauseSurah();
        if (this.playlistAudio) { this.playlistAudio.src = ''; this.playlistAudio = null; }
        this.playlistIndex = -1;
        this.activeAyah = null;
        await this.buildPlaylist(surahId);
        if (wasPlaying) this.playFromIndex(0);
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

        <!-- Progress Bar (ayah-based + waktu ayat aktif) -->
        <div class="space-y-1">
            <!-- Track klikable: klik loncat ke ayat berdasarkan posisi -->
            <div
                class="w-full h-2.5 rounded-full cursor-pointer relative"
                style="background-color: #e5e7eb;"
                @click="playFromIndex(Math.floor($event.offsetX / $el.offsetWidth * playlist.length))"
            >
                <div
                    class="h-full rounded-full transition-all duration-300"
                    style="background-color: #059669;"
                    :style="'width: ' + progressPercent() + '%'"
                ></div>
            </div>
            <div class="flex justify-between text-[9px] text-gray-400 font-mono">
                <span x-text="fmtTime(currentTimeSec)">0:00</span>
                <span x-text="fmtTime(currentAyahDur)">0:00</span>
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
                :disabled="playlistLoading || playlist.length === 0"
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
                @click="loopSurah = !loopSurah"
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
                        <!-- Nomor ayat: tap untuk loncat ke ayat ini saat surah main -->
                        <button
                            type="button"
                            @click="isPlayingSurah ? playFromIndex({{ $ayat->nomor_ayat - 1 }}) : null"
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
</div>
@endsection
