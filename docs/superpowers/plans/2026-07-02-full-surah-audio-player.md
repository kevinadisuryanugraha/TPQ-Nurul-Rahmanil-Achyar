# Full Surah Audio Player Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan panel audio player per-surah dengan 5 pilihan qari dan fitur auto-highlight + auto-scroll ayat yang sedang dibacakan di halaman `/murid/quran/{id}`.

**Architecture:** Semua logika diimplementasikan sepenuhnya client-side di satu file Blade (`show.blade.php`) menggunakan Alpine.js. State player (qari, progress, highlight) disimpan di Alpine.js reactive data. Timestamps per-ayat dibangun secara kumulatif dari API `api.alquran.cloud`. Audio surah di-stream dari `cdn.islamic.network`. Tidak ada perubahan backend.

**Tech Stack:** Alpine.js (sudah terpasang), HTML5 Audio API, Fetch API, localStorage, `api.alquran.cloud`, `cdn.islamic.network`.

## Global Constraints

- Bahasa UI: 100% Bahasa Indonesia — semua label, teks, placeholder.
- Alpine.js sudah diload via CDN di `layouts/murid.blade.php` — tidak perlu import tambahan.
- Font Awesome 6.4.0 tersedia via CDN — gunakan icon `fa-solid fa-*`.
- File yang boleh diubah: **hanya** `resources/views/murid/quran/show.blade.php`.
- Tidak ada migration, route, controller, atau file PHP baru.
- Tidak ada npm package baru.

---

## Peta File

| File | Status | Keterangan |
|---|---|---|
| `resources/views/murid/quran/show.blade.php` | **MODIFY** | Satu-satunya file yang diubah — tambah Alpine.js state, panel UI, dan binding highlight |

---

## Task 1: Perluas Alpine.js State + Inisialisasi Player

**Files:**
- Modify: `resources/views/murid/quran/show.blade.php:6-44`

**Interfaces:**
- Produces: State `qari`, `surahAudio`, `isPlayingSurah`, `currentTimeSec`, `durationSec`, `ayahTimestamps`, `activeAyah`, `highlightEnabled`, `globalSource`, `loopSurah` — digunakan oleh Task 2 dan Task 3.
- Produces: Methods `initSurahPlayer()`, `changeQari()`, `playSurah()`, `pauseSurah()`, `onTimeUpdate()`, `seekTo(ratio)`, `prevAyah()`, `nextAyah()`, `fmtTime(sec)`.

- [ ] **Step 1: Ganti blok `x-data` di baris 6-44 dengan versi yang diperluas**

Ganti konten lama dari `<div class="px-5 py-6 space-y-6" x-data="{` sampai `}">` (baris 6-44) dengan:

```blade
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

    /* ── Mutex ── */
    globalSource: null,

    /* ── Qari Options ── */
    qariList: [
        { value: 'ar.alafasy',             label: 'Mishary Rashid Al-Afasy' },
        { value: 'ar.abdurrahmaansudais',   label: 'Abdul Rahman Al-Sudais' },
        { value: 'ar.saoodshuraym',         label: 'Saad Al-Ghamdi' },
        { value: 'ar.husary',               label: 'Mahmoud Khalil Al-Husary' },
        { value: 'ar.abushuraym',           label: 'Abu Bakr Al-Shatri' },
    ],

    /* ─────────────────────────────────────── */

    fmtTime(sec) {
        if (!sec || isNaN(sec)) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },

    initSurahPlayer(surahId) {
        this.destroySurahAudio();
        const edition = this.qari;
        const url = `https://cdn.islamic.network/quran/audio-surah/128/${edition}/${surahId}.mp3`;
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
        const t = this.currentTimeSec;
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
        const wasPlaying = this.isPlayingSurah;
        this.destroySurahAudio();
        this.initSurahPlayer(surahId);
        if (wasPlaying) this.$nextTick(() => this.playSurah());
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

        this.playingId  = targetId;
        this.audioObject = new Audio(audioUrl);
        this.audioObject.play().catch(() => { this.playingId = null; });
        this.audioObject.addEventListener('ended', () => {
            this.playingId = null;
            this.audioObject = null;
        });
    }
}" x-init="initSurahPlayer({{ $surah->id }})">
```

- [ ] **Step 2: Verifikasi halaman tidak error**

Buka `http://127.0.0.1:8000/murid/quran/1` di browser. Halaman harus muncul tanpa error JavaScript di console. Panel player belum terlihat (belum dibuat di Task 2). Per-ayat play button tetap berfungsi.

- [ ] **Step 3: Commit**

```powershell
git add resources/views/murid/quran/show.blade.php
git commit -m "feat(quran): expand Alpine.js state and methods for full surah audio player"
```

---

## Task 2: Tambah Panel UI Surah Player

**Files:**
- Modify: `resources/views/murid/quran/show.blade.php` — di antara Surah Intro Card (baris ~76) dan Verses List (baris ~78).

**Interfaces:**
- Consumes: Semua state dan method dari Task 1.
- Produces: Panel UI player yang sudah berfungsi penuh.

- [ ] **Step 1: Sisipkan markup panel player setelah Surah Intro Card**

Temukan baris komentar `<!-- Verses List -->` di file. Sisipkan blok berikut **tepat sebelum** `<!-- Verses List -->`:

```blade
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

        <!-- Baris Bawah: Loop + Keterangan Highlight -->
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

            <span class="text-[9px] text-gray-400">
                <span x-show="highlightEnabled" class="text-emerald-600 font-medium">
                    <i class="fa-solid fa-circle-check mr-1"></i>Highlight aktif
                </span>
                <span x-show="!highlightEnabled && !highlightLoading" class="text-gray-400">
                    Highlight tidak tersedia
                </span>
            </span>
        </div>
    </div>
```

- [ ] **Step 2: Verifikasi panel muncul di browser**

Buka `http://127.0.0.1:8000/murid/quran/1`. Panel "Dengarkan Surah" harus muncul di antara banner Al-Fatihah dan daftar ayat. Coba klik Play — audio Al-Fatihah (Al-Afasy) harus mulai terputar.

- [ ] **Step 3: Commit**

```powershell
git add resources/views/murid/quran/show.blade.php
git commit -m "feat(quran): add full surah player UI panel with qari selector and controls"
```

---

## Task 3: Tambah Auto-Highlight & ID per Ayat

**Files:**
- Modify: `resources/views/murid/quran/show.blade.php` — bagian `<!-- Verses List -->`.

**Interfaces:**
- Consumes: `activeAyah` (number | null) dari Task 1. `highlightEnabled` (boolean).
- Produces: Setiap kartu ayat punya `id="ayah-{nomor}"` dan binding CSS untuk highlight.

- [ ] **Step 1: Update markup kartu ayat untuk mendukung highlight**

Temukan baris:
```blade
        @foreach($surah->ayats as $ayat)
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs space-y-4">
```

Ganti dengan:
```blade
        @foreach($surah->ayats as $ayat)
            <div
                id="ayah-{{ $ayat->nomor_ayat }}"
                class="bg-white rounded-2xl p-5 border shadow-xs space-y-4 transition-all duration-300"
                :class="activeAyah === {{ $ayat->nomor_ayat }}
                    ? 'border-emerald-400 bg-emerald-50 border-l-4'
                    : 'border-gray-100'"
            >
```

- [ ] **Step 2: Verifikasi auto-highlight di browser**

1. Buka `http://127.0.0.1:8000/murid/quran/1`.
2. Tunggu spinner "Memuat highlight..." selesai (status berubah jadi "Highlight aktif").
3. Tekan Play.
4. Ayat 1 harus langsung ter-highlight (background emerald + border kiri).
5. Setelah ayat 1 selesai, highlight harus pindah ke ayat 2.

- [ ] **Step 3: Verifikasi mutex — per-ayat vs per-surah**

1. Tekan Play pada player surah (surah mulai).
2. Klik tombol ▶ kecil di salah satu ayat.
3. Surah harus pause, highlight hilang, audio per-ayat mulai.
4. Kembali tekan Play di player surah → audio per-ayat stop, surah mulai kembali.

- [ ] **Step 4: Commit**

```powershell
git add resources/views/murid/quran/show.blade.php
git commit -m "feat(quran): add per-ayat id, auto-highlight, and auto-scroll binding"
```

---

## Task 4: Uji Surah Panjang & Edge Cases

**Files:**
- Tidak ada perubahan kode — ini task verifikasi murni.

- [ ] **Step 1: Uji Al-Baqarah (surah 2, 286 ayat)**

Buka `http://127.0.0.1:8000/murid/quran/2`. Spinner "Memuat highlight..." muncul. Tunggu. Setelah selesai, coba play. Pastikan tidak ada crash atau memory leak.

- [ ] **Step 2: Uji Al-Ikhlas (surah 112, 4 ayat)**

Buka `http://127.0.0.1:8000/murid/quran/112`. Uji fitur Loop Surah — aktifkan, putar sampai akhir. Surah harus otomatis mulai dari awal lagi.

- [ ] **Step 3: Uji pergantian Qari saat audio sedang main**

1. Play surah.
2. Ganti qari ke "Abdul Rahman Al-Sudais".
3. Audio lama harus stop, audio baru load, dan jika sebelumnya sedang main, audio baru langsung play.

- [ ] **Step 4: Uji persistensi pilihan Qari**

1. Pilih qari "Saad Al-Ghamdi".
2. Reload halaman.
3. Dropdown harus masih terpilih "Saad Al-Ghamdi" (dari localStorage).

- [ ] **Step 5: Uji fallback offline highlight**

1. Buka DevTools → Network → set "Offline".
2. Refresh halaman surah.
3. Player tetap muncul. Status harus menampilkan "Highlight tidak tersedia" — tidak ada error.

- [ ] **Step 6: Commit final dan push**

```powershell
git add resources/views/murid/quran/show.blade.php
git commit -m "feat: full surah audio player with qari selection, auto-highlight, and auto-scroll"
git push origin main
```
