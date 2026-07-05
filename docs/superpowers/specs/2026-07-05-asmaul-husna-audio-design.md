# Spesifikasi Desain: Fitur Audio Asmaul Husna

## 1. Latar Belakang & Tujuan
Fitur Asmaul Husna saat ini di Portal Murid hanya menampilkan teks nama-nama Allah dalam format Arab, transliterasi Latin, arti, dan deskripsi singkat. Untuk mempermudah santri dalam menghafal dan mempelajari pelafalan yang benar (makhorijul huruf), fitur ini akan ditingkatkan dengan penambahan audio interaktif:
1.  **Pemutar Audio Murottal Asmaul Husna Utuh**: Memutar keseluruhan 99 nama secara berurutan menggunakan lantunan nasyid Hijjaz.
2.  **Pemutar Audio Per Nama**: Memutar pelafalan spesifik dari satu nama yang dipilih oleh murid secara cepat dan mandiri.

---

## 2. Rancangan Tampilan UI/UX
Tampilan halaman [index.blade.php](file:///c:/laragon/www/porto-apps/lms-tpq/resources/views/murid/asmaul-husna/index.blade.php) akan diperbarui dengan menyematkan elemen-elemen berikut:

### A. Kartu Pemutar Utama (Top Audio Player Card)
*   Diletakkan di bagian atas daftar Asmaul Husna, tepat di bawah informasi pencarian.
*   **Visual**: Menggunakan kontainer bergradien hijau zamrud gelap (`bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-5 shadow-md flex flex-col space-y-3 relative overflow-hidden`).
*   **Elemen Kendali**:
    *   *Play/Pause Button*: Tombol bulat besar dengan ikon play/pause dinamis.
    *   *Timeline Progress Bar*: Input range HTML5 yang disinkronkan dengan pemutaran audio, menampilkan waktu berjalan (`MM:SS`) dan total waktu.
    *   *Playback Speed Button*: Tombol kelipatan kecepatan putar (`1.0x`, `1.25x`, `1.5x`) untuk mempermudah menyimak pelan.
    *   *Pulsing/Spinning Icon*: Ornamen bintang/kubah islami yang berputar lembut saat audio dimainkan.

### B. Tombol Putar Per Nama (Individual Card Audio Button)
*   Setiap baris kartu nama Allah akan memiliki tombol putar di bagian kiri (menggantikan atau mendampingi nomor urutan).
*   Nomor urutan akan diubah menjadi tombol interaktif bulat (`w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100 flex items-center justify-center font-bold text-xs shrink-0`).
*   Saat ditekan, nomor urutan berubah menjadi ikon putar (`fa-play`), ikon jeda (`fa-pause`), atau ikon spinner berputar (`fa-spinner fa-spin`) ketika audio sedang memuat/diputar.

---

## 3. Logika State & Perilaku Audio (Alpine.js)
Semua fungsi audio dikelola langsung di sisi client menggunakan state reactive Alpine.js:

### A. Detail Sumber Audio (CDN)
1.  **Murottal Lengkap (Hijjaz)**:
    *   URL: `https://archive.org/download/KoleksiNasyidPilihanBacaquran.tk/Hijjaz-asmaulHusna.mp3`
2.  **Audio Per Nama**:
    *   URL: `https://www.islamicity.org/mediaassets/MP3/other/covers/99-names-of-Allah/${padId}.mp3?v06092021`
    *   `padId` merupakan angka urutan yang dikonversi ke format 3-digit berawalan nol (contoh: 1 -> `001`, 99 -> `099`).

### B. Aturan Interaksi Audio
1.  **Pencegahan Overlapping**:
    *   Saat pemutar utama dinyalakan (`toggleFull()`), jika ada audio individu yang sedang berputar, sistem akan secara otomatis menjeda audio individu tersebut (`stopIndividual()`).
    *   Saat tombol audio per nama diklik (`playIndividual(urutan)`), jika pemutar utama sedang berputar, pemutar utama akan dijeda (`pauseFull()`).
2.  **Penyelamatan Memori**:
    *   Semua instance Audio baru akan dibersihkan atau diganti dengan benar saat berpindah atau menghentikan audio untuk menghindari penumpukan memori di peramban.

---

## 4. Rencana Pengujian
*   **Uji Fungsionalitas**:
    *   Memastikan audio murottal penuh dapat diputar, dijeda, dan dipindah posisinya (*seekable timeline*).
    *   Memastikan tombol per nama memutar berkas MP3 yang tepat sesuai dengan urutan nama Allah yang diklik.
    *   Memastikan kecepatan putar (speed rate) benar-benar memperlambat atau mempercepat pemutaran.
    *   Memastikan tidak ada dua audio yang berbunyi secara bersamaan (saling mematikan jika yang lain dinyalakan).
