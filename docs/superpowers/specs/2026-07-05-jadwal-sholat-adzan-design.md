# Desain Fitur: Jadwal Shalat & Pemutaran Adzan Realtime

Dokumen ini menjelaskan rancangan arsitektur, antarmuka pengguna, dan alur pemutaran Adzan otomatis berdasarkan koordinat GPS/lokasi murid yang ter-cache pada LocalStorage.

## 1. Spesifikasi Teknis & API Lokasi

*   **API Pengambil Jadwal**: AlAdhan API bulanan (`https://api.aladhan.com/v1/calendar`).
*   **Parameter Lokasi**: Koordinat Latitude dan Longitude dari HTML5 Geolocation API (`navigator.geolocation.getCurrentPosition`).
*   **Metode Perhitungan**: Method `20` (Kementerian Agama RI / Kemenag).
*   **Sistem Caching**: LocalStorage browser dengan key `tpq_sholat_data`. Data di-cache dalam bentuk jadwal bulanan untuk menghemat kuota API dan memastikan kompatibilitas offline PWA.

### Fallback Lokasi Manual:
Jika izin lokasi GPS ditolak oleh murid, sistem menyediakan dropdown berisi 10 kota besar di Indonesia:
*   DKI Jakarta (Default)
*   Surabaya
*   Bandung
*   Medan
*   Makassar
*   Semarang
*   Palembang
*   Yogyakarta
*   Balikpapan
*   Aceh

---

## 2. Pemutaran Suara Adzan & Pilihan Muadzin

Sistem menyediakan opsi pemutaran audio Adzan secara penuh (3-4 menit) dengan opsi pilihan Muadzin berikut:
1.  **Adzan Makkah (Masjidil Haram)**: `https://archive.org/download/AdhanMakkah/Adhan%20Makkah.mp3`
2.  **Adzan Madinah (Masjid Nabawi)**: `https://archive.org/download/adhan-madinah/adhan-madinah.mp3`
3.  **Adzan Masjid Al-Aqsa**: `https://archive.org/download/adhan-al-aqsa/adhan-al-aqsa.mp3`
4.  **Adzan Khas Turki**: `https://archive.org/download/adhan-turkey/adhan-turkey.mp3`

### Logika Penanganan Autoplay Browser:
Browser modern memblokir pemutaran suara otomatis (`Audio.play()`) kecuali ada interaksi pengguna terlebih dahulu di halaman tersebut.
*   **Bypass Interaksi**: Ketika halaman murid pertama kali dimuat, setiap klik/sentuhan apa pun di halaman akan mengaktifkan izin pemutaran audio.
*   **Banner Alert Fallback**: Jika Adzan terpicu namun diblokir oleh browser, sistem memunculkan banner mengambang dengan teks bersinar: *"Waktunya Shalat [Nama Shalat]! Ketuk di sini untuk mengumandangkan Adzan"*. Mengetuk banner ini akan memutar audio secara penuh.
*   **Tombol Hentikan**: Selama Adzan berbunyi, tombol speaker akan berubah menjadi tombol stop berwarna merah *"Hentikan Adzan"*.

---

## 3. Komponen Antarmuka Pengguna (UI)

Widget ini akan diletakkan di **Dashboard Portal Murid (`resources/views/murid/dashboard.blade.php`)**:
*   **Header Widget**: Menampilkan nama lokasi terdeteksi (misal: *"Kabupaten Bekasi (GPS)"*) dengan tombol ubah lokasi manual ✏️.
*   **Countdown Shalat**: Teks besar dinamis: *"Dzuhur dalam 42 menit 10 detik"* atau *"Waktunya Shalat Ashar!"*.
*   **Grid Waktu Shalat**: Baris waktu untuk Subuh, Terbit (Shuruq), Dzuhur, Ashar, Maghrib, Isya dengan indikator warna emerald menyala pada waktu shalat berikutnya.
*   **Toolbar Pengaturan**:
    *   Dropdown pemilihan Muadzin.
    *   Togle Aktifkan Adzan Otomatis (Mute/Unmute).
