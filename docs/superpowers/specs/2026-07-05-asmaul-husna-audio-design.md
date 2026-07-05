# Spesifikasi Desain: Fitur Audio & Grid Card Asmaul Husna

## 1. Latar Belakang & Tujuan
Fitur Asmaul Husna di Portal Murid dirancang ulang dengan visual berbasis kartu grid (Grid Card) yang elegan menyerupai kubah masjid/kaligrafi klasik. Fitur ini juga menyertakan penanda visual (highlight) aktif saat pemutaran murottal penuh (Hijjaz) maupun pemutaran audio pelafalan nama per nama secara mandiri.

---

## 2. Rancangan Tampilan UI/UX
Tampilan halaman [index.blade.php](file:///c:/laragon/www/porto-apps/lms-tpq/resources/views/murid/asmaul-husna/index.blade.php) dirombak dari format daftar vertikal accordion menjadi grid:

### A. Kisi Kartu (Grid Layout)
*   Menggunakan tata letak grid responsif 2-kolom (`grid grid-cols-2 gap-4`).
*   Setiap kartu memiliki pembungkus dengan transisi bayangan lembut dan respons terhadap hover (`hover:shadow-md hover:scale-[1.01] transition duration-300`).

### B. Anatomi Kartu Nama Allah
1.  **Dinding Kubah (Dome Arch)**:
    *   Wadah bagian atas yang melengkung sempurna di atas dan lurus di bawah (`border-t border-x border-amber-200/50 rounded-t-full pt-6 pb-4 bg-gray-50/50 relative`).
    *   Berisi teks Arab besar (`arabic-text text-2xl font-bold text-emerald-950`).
2.  **Badge Urutan & Tombol Putar Terapung**:
    *   Badge Nomor (`urutan`): Melayang di pojok kanan atas kartu.
    *   Tombol Putar Individu (`playIndividual(urutan)`): Melayang di pojok kiri atas kartu. Menggunakan warna emerald lembut yang berubah menjadi amber cerah berdenyut (pulse) jika aktif memutar audio nama tersebut.
3.  **Detail Nama & Arti (Bawah Kartu)**:
    *   Dibatasi oleh garis pembatas horizontal tipis.
    *   Menampilkan nama Latin transliterasi tebal (`text-xs font-black text-gray-900`) dan terjemahan (`text-[9px] text-gray-500 line-clamp-1`).

### C. Modal Laci Bawah (Bottom Sheet Detail Modal)
*   Mengklik kartu di area mana saja (selain tombol play) akan meluncurkan Bottom Sheet Drawer dari bawah layar.
*   **Isi Drawer**:
    *   Teks Arab besar (`text-4xl`).
    *   Transliterasi Latin (`text-lg font-bold`).
    *   Arti dan penjelasan/deskripsi mendalam (`text-xs text-gray-600 leading-relaxed`).
    *   Tombol putar suara langsung di dalam drawer.

---

## 3. Logika State & Perilaku Audio (Alpine.js)

### A. Highlight Dinamis Linear Murottal Penuh
Kemajuan pemutaran audio murottal penuh disinkronkan ke index 99 Nama Allah menggunakan estimasi waktu berjalan:
*   `introDuration = 12` detik (intro nasyid Hijjaz).
*   Jika `currentTime >= 12`:
    *   `pct = (currentTime - 12) / (duration - 12)`
    *   `activeIndex = Math.floor(pct * 99) + 1`
    *   `activeIndex` dikunci di antara `1` dan `99`.
*   Kartu dengan urutan sama dengan `activeIndex` akan otomatis menyala dengan border hijau emerald tebal (`border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/10`).

### B. Getter Penentu Highlight Aktif
```javascript
get activeHighlightId() {
    if (this.individualPlaying) {
        return this.playingIndividualId;
    }
    if (this.fullPlaying && this.fullDuration > 20) {
        const intro = 12; // intro Hijjaz
        if (this.fullCurrentTime < intro) return null;
        const pct = (this.fullCurrentTime - intro) / (this.fullDuration - intro);
        const index = Math.floor(pct * 99) + 1;
        return Math.min(99, Math.max(1, index));
    }
    return null;
}
```

---

## 4. Rencana Pengujian
*   Verifikasi visual bahwa 99 nama tampil rapi dalam format grid 2-kolom.
*   Verifikasi highlight berpindah secara merata saat murottal penuh diputar.
*   Verifikasi detail drawer meluncur naik dengan benar saat kartu diklik, dan tombol di dalam drawer berfungsi memutar audio dengan benar.
