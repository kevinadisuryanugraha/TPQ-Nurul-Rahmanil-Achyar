# Full Surah Audio Player — Design Spec

**Date:** 2026-07-02  
**Status:** Approved  
**Scope:** Portal Murid — Halaman Baca Surah (`/murid/quran/{id}`)

---

## Ringkasan

Menambahkan panel audio player per-surah di halaman baca Al-Qur'an murid, dengan pilihan 5 qari/imam populer dan fitur auto-highlight + auto-scroll ayat yang sedang dibacakan. Player baru ini hidup berdampingan dengan tombol play per-ayat yang sudah ada, dengan mekanisme mutex agar tidak saling menimpa.

---

## Tujuan

- Santri bisa mendengar bacaan surah secara utuh tanpa harus klik per-ayat satu per satu.
- Santri bisa memilih gaya bacaan (tartil, mujawwad, dll) sesuai kebutuhan belajar.
- Auto-highlight membantu santri mengikuti bacaan sambil melihat teks Arab (seperti karaoke).
- Pilihan qari tersimpan persisten (localStorage) sehingga tidak perlu dipilih ulang setiap buka halaman.

---

## Sumber Data & API

### Audio Per-Surah

| Qari | Kode Edisi | URL Stream |
|---|---|---|
| Mishary Rashid Al-Afasy | `ar.alafasy` | `https://cdn.islamic.network/quran/audio-surah/128/ar.alafasy/{surahId}.mp3` |
| Abdul Rahman Al-Sudais | `ar.abdurrahmaansudais` | `https://cdn.islamic.network/quran/audio-surah/128/ar.abdurrahmaansudais/{surahId}.mp3` |
| Saad Al-Ghamdi | `ar.saoodshuraym` | `https://cdn.islamic.network/quran/audio-surah/128/ar.saoodshuraym/{surahId}.mp3` |
| Mahmoud Khalil Al-Husary | `ar.husary` | `https://cdn.islamic.network/quran/audio-surah/128/ar.husary/{surahId}.mp3` |
| Abu Bakr Al-Shatri | `ar.abushuraym` | `https://cdn.islamic.network/quran/audio-surah/128/ar.abushuraym/{surahId}.mp3` |

Semua URL menggunakan bitrate 128kbps dari CDN `cdn.islamic.network` (layanan publik gratis dari Islamic Network).

### Timestamps Per-Ayat (untuk Auto-Highlight)

**Endpoint:** `https://api.alquran.cloud/v1/surah/{surahId}/ar.alafasy`

Response mengandung field `ayahs[].audio` (URL audio per-ayat). Karena API ini tidak menyediakan timestamp *dalam* file audio surah, kita menggunakan pendekatan **kumulatif durasi**:

1. Fetch daftar audio per-ayat dari Al-Quran Cloud API saat halaman dibuka.
2. Pre-load setiap `<audio>` element per-ayat (tanpa memutarnya) untuk mendapatkan `duration`.
3. Bangun array `ayahTimestamps = [{no, startSec, endSec}]` secara kumulatif.
4. Gunakan event `timeupdate` pada audio surah untuk mencocokkan `currentTime` ke entri yang tepat.

**Fallback:** Jika fetch API gagal (timeout/offline), set `highlightEnabled = false`. Player tetap berjalan normal tanpa highlight — tidak ada pesan error yang ditampilkan ke user.

> **Catatan implementasi:** Pre-loading durasi per-ayat bisa memakan waktu 2–5 detik tergantung koneksi. Progress indicator kecil ("Memuat data highlight...") ditampilkan selama proses ini.

---

## Desain UI

### Posisi

Panel ditempatkan **di antara header surah (banner hijau) dan daftar ayat**, sebelum `<!-- Verses List -->`.

### Anatomi Panel

```
┌─────────────────────────────────────────────────────────────┐
│  🎧  Dengarkan Surah                      [loading spinner] │
│                                                              │
│  Pilih Qari:  ┌──────────────────────────────────┐         │
│               │ Mishary Rashid Al-Afasy         ▾ │         │
│               └──────────────────────────────────┘         │
│                                                              │
│          ⏮ Ayat Prev   ▶ Play / ❚❚ Pause   ⏭ Ayat Next    │
│                                                              │
│  ▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░  2:34 / 5:12          │
│                                                              │
│  🔁 Ulangi                                   🔇 Volume      │
└─────────────────────────────────────────────────────────────┘
```

**Elemen kontrol:**
- **Dropdown Qari** — pilih salah satu dari 5 imam; nilai disimpan ke `localStorage('tpq_qari')`.
- **Progress bar** — klikable untuk seek, menampilkan `currentTime / duration` dalam format `m:ss`.
- **Play/Pause** — toggle utama; ikon berubah dinamis.
- **⏮ / ⏭** — loncat ke awal ayat sebelumnya / ayat berikutnya berdasarkan `ayahTimestamps`.
- **🔁 Ulangi** — toggle loop surah (`audio.loop = true/false`).
- **Volume slider** — collapse/expand kecil.

### Perilaku Auto-Highlight

- Ayat aktif: `background emerald-50`, `border-left: 4px solid #059669`, transisi `0.3s`.
- Auto-scroll smooth ke ayat aktif menggunakan `element.scrollIntoView({ behavior: 'smooth', block: 'center' })`.
- Highlight dihapus (`activeAyah = null`) saat audio pause atau selesai.

### Mutex Audio (Per-Surah vs Per-Ayat)

- Alpine.js menyimpan satu state `globalSource: null | 'surah' | 'ayah'`.
- Klik Play pada player surah → set `globalSource = 'surah'` → pause audio per-ayat jika sedang main.
- Klik play per-ayat → set `globalSource = 'ayah'` → pause audio surah jika sedang main → `activeAyah = null`.
- Keduanya subscribe ke perubahan `globalSource` melalui Alpine.js reactivity.

---

## Perubahan File

### [MODIFY] `resources/views/murid/quran/show.blade.php`

Satu-satunya file yang diubah. Semua logika Alpine.js ditambahkan ke `x-data` container yang sudah ada. Tidak ada migration, route, atau controller baru.

**Perubahan:**
1. Perluas `x-data` dengan state baru: `qari`, `surahAudio`, `isPlayingSurah`, `currentTimeSec`, `durationSec`, `ayahTimestamps`, `activeAyah`, `highlightEnabled`, `globalSource`, `loopSurah`.
2. Tambah method: `initSurahPlayer()`, `loadAyahTimestamps()`, `playSurah()`, `pauseSurah()`, `seekTo(pos)`, `prevAyah()`, `nextAyah()`, `onTimeUpdate()`, `changeQari()`.
3. Tambah markup panel player setelah header surah.
4. Update markup per-ayat: tambah `:class` binding untuk highlight dan update `@click` untuk mutex.

---

## Tidak Ada Backend Baru

- Tidak ada migration database.
- Tidak ada route baru.
- Tidak ada controller baru.
- Semua fetch ke CDN dan API eksternal dilakukan client-side (JavaScript/Alpine.js).

---

## Rencana Verifikasi

### Manual
1. Buka `/murid/quran/1` (Al-Fatihah) — panel player muncul di antara header dan daftar ayat.
2. Pilih qari berbeda → audio berganti, pilihan tersimpan setelah reload halaman.
3. Tekan Play → audio surah mulai, ayat 1 di-highlight.
4. Saat audio sampai ayat 2, highlight pindah otomatis + auto-scroll.
5. Klik tombol play per-ayat → surah pause, highlight hilang.
6. Klik ⏭ Next Ayat → audio loncat ke timestamp ayat berikutnya.
7. Nonaktifkan jaringan (DevTools offline) → player tidak crash, highlight nonaktif gracefully.

### Surah yang Diuji
- Al-Fatihah (1) — 7 ayat, pendek, untuk uji dasar.
- Al-Baqarah (2) — 286 ayat, stres test timestamp loading.
- Al-Ikhlas (112) — 4 ayat, sangat pendek, untuk uji edge case.
