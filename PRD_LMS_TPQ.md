# PRD: Aplikasi LMS TPQ (Taman Pendidikan Al-Qur'an)

**Versi:** 1.0  
**Tanggal:** 20 Juni 2025  
**Status:** Draft Final  
**Platform:** Progressive Web App (PWA)

---

## Daftar Isi

1. [Project Overview](#1-project-overview)
2. [User Roles & Permissions](#2-user-roles--permissions)
3. [Alur Aplikasi (User Journey)](#3-alur-aplikasi-user-journey)
4. [Feature Specifications](#4-feature-specifications)
   - 4.1 Modul Autentikasi
   - 4.2 Modul Superadmin
   - 4.3 Modul Admin Panel (Pengurus)
   - 4.4 Portal Murid
5. [Technical Architecture](#5-technical-architecture)
6. [Database Schema (ERD)](#6-database-schema-erd)
7. [PWA & Offline Strategy](#7-pwa--offline-strategy)
8. [Content Strategy](#8-content-strategy)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [MVP Scope vs v2](#10-mvp-scope-vs-v2)
11. [Risiko & Catatan Teknis](#11-risiko--catatan-teknis)
12. [Appendix: Checklist Pre-Development](#12-appendix-checklist-pre-development)

---

## 1. Project Overview

### 1.1 Latar Belakang

TPQ (Taman Pendidikan Al-Qur'an) yang baru dirintis membutuhkan sistem manajemen pembelajaran digital yang dapat:

- Membantu pengurus mengelola data murid, absensi, dan penilaian secara efisien
- Menyediakan platform belajar digital yang dapat diakses murid dan orang tua dari rumah
- Menggantikan pencatatan manual yang rentan hilang dan sulit dilacak

Saat ini TPQ memiliki sekitar **20 murid** dan **6 orang pengurus**, dengan potensi berkembang menjadi multi-cabang di masa depan.

### 1.2 Tujuan Sistem

- Digitalisasi operasional TPQ: absensi, penilaian 4 domain, manajemen data murid
- Menyediakan konten belajar digital offline-capable (Al-Qur'an, Doa, Hadist, Cerita Kisah, Panduan Praktik)
- Memudahkan orang tua memantau perkembangan anak melalui akun murid
- Membangun fondasi sistem yang scalable untuk multi-cabang di masa depan

### 1.3 Target Pengguna

| Role | Deskripsi | Estimasi Jumlah |
|---|---|---|
| Superadmin | Tim IT / Developer | 1–2 orang |
| Admin (Pengurus) | Pengurus TPQ | 6 orang |
| Murid / Orang Tua | Akun dipakai bersama murid dan wali | ~20 murid |

### 1.4 Konteks Penggunaan

- **Pengurus** mengakses aplikasi dari **HP pribadi** masing-masing (menggunakan kuota internet sendiri)
- **Murid / Orang tua** mengakses dari **HP pribadi / HP orang tua** maupun **shared device milik TPQ**
- **TPQ tidak memiliki WiFi** — semua akses mengandalkan kuota seluler
- **Offline capability adalah kebutuhan inti** untuk portal murid

---

## 2. User Roles & Permissions

### 2.1 Hierarki Role

```
Superadmin
  └── Admin (Pengurus)
        └── Murid (termasuk orang tua yang memakai akun murid)
```

### 2.2 Matrix Permissions

| Fitur | Superadmin | Admin | Murid |
|---|:---:|:---:|:---:|
| **MANAJEMEN SISTEM** | | | |
| Manajemen akun Admin/Pengurus | ✅ | ❌ | ❌ |
| Pengaturan sistem (nama TPQ, logo) | ✅ | ❌ | ❌ |
| Lihat log sistem | ✅ | ❌ | ❌ |
| **MANAJEMEN DATA** | | | |
| Manajemen Murid (CRUD) | ✅ | ✅ | ❌ |
| Manajemen Level Murid | ✅ | ✅ | ❌ |
| Input Absensi | ✅ | ✅ | ❌ |
| Input Penilaian (4 domain) | ✅ | ✅ | ❌ |
| **MANAJEMEN KONTEN** | | | |
| Kelola Doa-doa | ✅ | ✅ | ❌ |
| Kelola Hadist | ✅ | ✅ | ❌ |
| Kelola Cerita Kisah (CRUD) | ✅ | ✅ | ❌ |
| Kelola Panduan Praktik (CRUD) | ✅ | ✅ | ❌ |
| **KOMUNIKASI** | | | |
| Kirim Pengumuman | ✅ | ✅ | ❌ |
| Lihat Pengumuman | ✅ | ✅ | ✅ |
| **LAPORAN** | | | |
| Lihat & Export Laporan | ✅ | ✅ | ❌ |
| **PORTAL MURID** | | | |
| Akses Konten Belajar | ✅ | ✅ | ✅ |
| Lihat Nilai & Progress Sendiri | — | — | ✅ |
| Lihat Riwayat Absensi Sendiri | — | — | ✅ |

### 2.3 Catatan Penting

- Akun **Murid** juga digunakan oleh orang tua untuk monitoring — tidak ada role terpisah untuk orang tua.
- Semua **Admin** memiliki akses penuh ke semua fitur manajemen — tidak ada sub-role teknis di antara pengurus.
- **Superadmin** memiliki semua akses Admin ditambah akses ke pengaturan sistem dan manajemen akun Admin.

---

## 3. Alur Aplikasi (User Journey)

### 3.1 Alur Superadmin

```
[Login] → Dashboard Superadmin
             │
             ├── Kelola Admin/Pengurus
             │     ├── Tambah Admin baru
             │     ├── Edit data Admin
             │     └── Nonaktifkan/Reset password Admin
             │
             ├── Pengaturan Sistem
             │     ├── Edit nama & logo TPQ
             │     └── Edit tahun ajaran aktif
             │
             └── Akses semua fitur Admin Panel (full access)
```

---

### 3.2 Alur Admin/Pengurus

#### Alur Harian (Saat atau Setelah Sesi TPQ)

```
[Login] → Dashboard Admin
             │
             ├── Cek Widget "Hari Ini"
             │     ├── Jumlah murid hadir/tidak hadir
             │     └── Reminder murid yang belum dinilai
             │
             ├── Input Absensi
             │     ├── Pilih Tanggal + Sesi (Pagi/Sore/Malam)
             │     ├── Centang status tiap murid (Hadir/Izin/Sakit/Alpha)
             │     ├── Isi catatan jika perlu
             │     └── Simpan (dengan konfirmasi)
             │
             └── Input Penilaian
                   ├── Pilih Domain (Baca / Hafalan / Tulis / Praktik)
                   ├── Pilih Murid
                   ├── Isi form penilaian sesuai domain
                   └── Simpan
```

#### Alur Manajemen (Periodik)

```
[Login] → Dashboard Admin
             │
             ├── Manajemen Murid
             │     ├── Tambah murid baru (input data lengkap + buat akun login)
             │     ├── Edit data murid
             │     └── Nonaktifkan murid (jika keluar/pindah)
             │
             ├── Manajemen Level
             │     ├── Lihat daftar murid per level
             │     └── Naikan/Turunkan level murid (dengan konfirmasi)
             │
             ├── Manajemen Konten
             │     ├── Tambah / Edit Cerita Kisah
             │     └── Tambah / Edit Panduan Praktik (teks + gambar per langkah)
             │
             ├── Kirim Pengumuman
             │     ├── Tulis judul + isi pengumuman
             │     ├── Pilih target (Semua / Per Level)
             │     ├── Set periode tampil
             │     └── Publish
             │
             └── Laporan
                   ├── Pilih murid dan rentang waktu
                   ├── Preview laporan
                   └── Export ke PDF / Excel
```

---

### 3.3 Alur Murid / Orang Tua

#### First-Time Use

```
[Buka URL Aplikasi di Browser]
      │
      ├── Login dengan username + password (diberikan oleh pengurus)
      │
      ├── [Opsional] Prompt "Tambah ke Layar Utama" (Install PWA)
      │
      └── Masuk ke Dashboard Murid
```

#### Alur Belajar (Konten)

```
[Dashboard] → Menu "Belajar"
                 │
                 ├── Al-Qur'an
                 │     ├── Pilih Surat dari daftar 114 surat
                 │     ├── Baca teks Arab + terjemahan per ayat
                 │     └── [Offline setelah pertama kali dimuat]
                 │
                 ├── Doa-Doa
                 │     ├── Browse/filter per kategori
                 │     ├── Lihat teks Arab + transliterasi + terjemahan
                 │     └── [Offline — tersedia selalu]
                 │
                 ├── Hadist
                 │     ├── Browse/search hadist
                 │     ├── Lihat teks Arab + terjemahan + sumber
                 │     └── [Offline — tersedia selalu]
                 │
                 ├── Cerita Kisah
                 │     ├── Pilih artikel dari daftar
                 │     └── Baca konten (teks + gambar)
                 │
                 └── Panduan Praktik
                       ├── Pilih topik (Wudhu / Sholat / dll)
                       ├── Ikuti langkah demi langkah
                       └── [Offline setelah pernah dibuka]
```

#### Alur Monitoring Perkembangan

```
[Dashboard] → Widget Nilai / Menu "Progress"
                 │
                 ├── Level Saat Ini
                 │     └── Visual bar: Pra-Iqra → Iqra 1 → ... → Al-Qur'an
                 │
                 ├── Penilaian Baca
                 │     └── Progress jilid/halaman + catatan tajwid terbaru
                 │
                 ├── Penilaian Hafalan
                 │     └── Checklist surat/hadist/doa yang sudah hafal
                 │
                 ├── Penilaian Tulis
                 │     └── Nilai & grade terbaru + riwayat
                 │
                 └── Penilaian Praktik
                       └── Checklist komponen per jenis praktik

[Dashboard] → Menu "Absensi"
                 └── Kalender kehadiran bulanan + rekap statistik

[Dashboard] → Menu "Pengumuman"
                 └── Daftar pengumuman aktif dari pengurus
```

---

## 4. Feature Specifications

---

### 4.1 Modul Autentikasi

#### 4.1.1 Login

**Deskripsi:** Halaman login tunggal untuk semua role. Sistem mendeteksi role dan melakukan redirect sesuai.

**Fields:**
| Field | Tipe | Keterangan |
|---|---|---|
| Username / Email | Text | Murid: username. Admin/Superadmin: email |
| Password | Password | |
| Remember Me | Checkbox | Session panjang (30 hari) |

**Behavior:**
- Redirect pasca login:
  - Superadmin → `/superadmin/dashboard`
  - Admin → `/admin/dashboard`
  - Murid → `/murid/dashboard`
- Error message: "Username atau password salah. Silakan coba lagi."
- Akun nonaktif: "Akun Anda tidak aktif. Hubungi pengurus."
- Tidak ada fitur "Lupa Password" mandiri — reset dilakukan oleh admin yang lebih tinggi

#### 4.1.2 Logout

- Tombol logout tersedia di navbar/sidebar semua halaman
- Session / token di-invalidate di server saat logout
- Redirect ke halaman login dengan pesan "Anda telah keluar."

#### 4.1.3 Reset Password (oleh Admin)

- Superadmin dapat reset password Admin lewat halaman detail Admin
- Admin dapat reset password Murid lewat halaman detail Murid
- Reset menghasilkan password sementara yang wajib diganti pada login berikutnya *(opsional — bisa disederhanakan ke password default yang langsung aktif)*

---

### 4.2 Modul Superadmin

#### 4.2.1 Dashboard Superadmin

**Tampilan Widget:**
- Jumlah Admin aktif
- Jumlah Murid aktif total
- Jumlah total konten yang dipublish (cerita + panduan)
- Aktivitas terakhir (log 10 aksi terakhir di sistem)
- Versi aplikasi

#### 4.2.2 Manajemen Admin/Pengurus

**Daftar Admin:**
- Tabel: Nama, Email, No. HP, Status Aktif, Tanggal Dibuat, Aksi

**Form Tambah/Edit Admin:**
| Field | Tipe | Wajib | Validasi |
|---|---|:---:|---|
| Nama | Text | ✅ | Max 100 char |
| Email | Email | ✅ | Unik, format email valid |
| No. HP | Text | ❌ | |
| Password | Password | ✅ (tambah) | Min 8 karakter |
| Status | Toggle | ✅ | Aktif / Nonaktif |

**Aksi:**
- Tambah Admin baru
- Edit data Admin
- Nonaktifkan Admin (soft delete — tidak bisa hapus permanen)
- Reset Password Admin → password baru diberikan langsung ke admin tersebut

#### 4.2.3 Pengaturan Sistem

| Setting | Tipe | Keterangan |
|---|---|---|
| Nama TPQ | Text | Ditampilkan di header & manifest PWA |
| Logo TPQ | Upload gambar | PNG/SVG, tampil di header |
| Deskripsi TPQ | Textarea | Opsional |
| Tahun Ajaran Aktif | Text | Misal: "2025/2026" |
| Sesi TPQ | Multi-input | Daftar sesi (default: Pagi, Sore, Malam) — bisa dikustomisasi |

---

### 4.3 Modul Admin Panel (Pengurus)

#### 4.3.1 Dashboard Admin

**Widget Hari Ini:**
- Total murid aktif
- Jumlah murid hadir hari ini (dari absensi sesi terakhir)
- Jumlah murid belum diinput absensinya hari ini
- Tombol CTA: "Input Absensi Sekarang"

**Widget Reminder Penilaian:**
- List murid yang belum mendapat penilaian domain apapun dalam 7 hari terakhir
- Tombol CTA: "Input Penilaian"

**Widget Pengumuman:**
- Pengumuman aktif yang paling baru (judul + tanggal)
- Tombol CTA: "Buat Pengumuman Baru"

**Widget Murid per Level:**
- Tabel/bar chart sederhana: jumlah murid per level (Pra-Iqra: X, Iqra 1: Y, ...)

---

#### 4.3.2 Manajemen Murid

##### Daftar Murid

- Tabel dengan kolom: Foto (avatar), Nama Lengkap, Nama Panggilan, Level Saat Ini, Status
- Filter: Level, Status (Aktif/Nonaktif)
- Search: Nama lengkap / Nama panggilan
- Paginasi: 20 murid per halaman
- Tombol aksi per baris: Detail, Edit, Nonaktifkan

##### Form Tambah / Edit Murid

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Foto | Upload | ❌ | Max 2MB, JPEG/PNG/WebP. Resize otomatis ke 300x300px |
| Nama Lengkap | Text | ✅ | Max 100 char |
| Nama Panggilan | Text | ✅ | Max 50 char |
| Tempat Lahir | Text | ❌ | |
| Tanggal Lahir | Date | ❌ | |
| Jenis Kelamin | Select (L/P) | ✅ | |
| Nama Orang Tua/Wali | Text | ❌ | |
| No. HP Orang Tua | Text | ❌ | |
| Alamat | Textarea | ❌ | |
| Tanggal Masuk TPQ | Date | ✅ | Default: hari ini |
| Level Awal | Select | ✅ | Pilih dari daftar level yang tersedia |
| **Akun Login** | | | |
| Username | Text | ✅ | Auto-generate dari nama panggilan (lowercase, tanpa spasi). Editable. Unik. |
| Password | Text | ✅ | Default password yang diberikan pengurus ke orang tua |

##### Halaman Detail Murid

Informasi yang ditampilkan:
- Semua data profil (foto, identitas, kontak)
- **Level Saat Ini** + riwayat perubahan level (tanggal, dari level apa, ke level apa, oleh siapa)
- **Rekap Absensi Bulan Ini:** Hadir X / Izin Y / Sakit Z / Alpha W dari total N sesi
- **Penilaian Terakhir per Domain:**
  - Baca: Jilid X Halaman Y — [Tanggal]
  - Hafalan: Terakhir hafal [Nama Surat] — [Tanggal]
  - Tulis: Nilai X ([Grade]) — [Tanggal]
  - Praktik: [Jenis Praktik] — [Tanggal]

Tombol aksi di halaman detail:
- Edit Data Murid
- Naikan Level → konfirmasi dialog
- Turunkan Level → konfirmasi dialog
- Reset Password
- Nonaktifkan Murid → konfirmasi dialog

---

#### 4.3.3 Manajemen Level

##### Tampilan Daftar Level

Jenjang level (urutan tetap, tidak bisa diubah di MVP):

```
Pra-Iqra → Iqra 1 → Iqra 2 → Iqra 3 → Iqra 4 → Iqra 5 → Iqra 6 → Al-Qur'an
  (1)         (2)      (3)      (4)      (5)      (6)      (7)        (8)
```

Untuk setiap level ditampilkan:
- Nama level
- Jumlah murid yang saat ini berada di level ini
- Tombol "Lihat Murid" → filter daftar murid otomatis ke level tersebut

##### Naikan / Turunkan Level Murid

Dilakukan dari **halaman detail murid**:

1. Klik tombol "Naikan Level" atau "Turunkan Level"
2. Dialog konfirmasi:
   > "Anda akan menaikkan [Nama Murid] dari **[Level Lama]** ke **[Level Baru]**. Apakah Anda yakin?"
3. Setelah konfirmasi:
   - `current_level_id` murid diupdate
   - Entri baru dibuat di tabel `user_level_histories`
   - Toast notification: "Level [Nama Murid] berhasil dinaikkan ke [Level Baru]"

**Batasan:**
- Tidak bisa naik dari level Al-Qur'an (level tertinggi)
- Tidak bisa turun dari Pra-Iqra (level terendah)
- Konfirmasi wajib sebelum perubahan dieksekusi

---

#### 4.3.4 Absensi

##### Input Absensi

- Pilih **Tanggal** (default: hari ini) dan **Sesi** (sesuai daftar sesi yang dikonfigurasi Superadmin)
- Sistem cek: apakah absensi untuk tanggal + sesi ini sudah ada? Jika ya, tampilkan data existing untuk diedit
- Daftar semua murid aktif dalam tabel:

| Nama Murid | Level | Hadir | Izin | Sakit | Alpha | Catatan |
|---|---|:---:|:---:|:---:|:---:|---|
| [Nama] | Iqra 3 | ○ | ○ | ○ | ● | |

- Default semua murid: **Alpha** (belum hadir)
- Admin mencentang sesuai kondisi aktual
- Kolom catatan per murid (opsional, contoh: "izin karena sakit demam")
- Tombol "Simpan Absensi" dengan konfirmasi sebelum simpan

##### Riwayat Absensi

- Filter: Murid, Bulan/Tahun, Sesi, Status
- Tabel: Tanggal, Sesi, Nama Murid, Status, Catatan, Diinput oleh
- Tombol edit per baris (untuk koreksi jika salah input)
- Hapus absensi (dengan konfirmasi)

##### Rekap Absensi

Per Murid per Periode:
- Total sesi dalam periode: N
- Hadir: X (X%)
- Izin: Y
- Sakit: Z
- Alpha: W
- Visual: progress bar atau donut chart sederhana

---

#### 4.3.5 Penilaian

##### Domain 1 — Penilaian Baca (Iqra / Al-Qur'an)

**Tujuan:** Melacak progress bacaan murid dan kualitas tajwid.

**Form Input:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Murid | Select | ✅ | Dropdown nama murid aktif |
| Tanggal | Date | ✅ | Default: hari ini |
| Jenis Bacaan | Select | ✅ | Iqra / Al-Qur'an / Tilawah |
| Jilid (jika Iqra) | Number | ✅ jika Iqra | 1–6 |
| Halaman (jika Iqra) | Number | ✅ jika Iqra | |
| Juz (jika Al-Qur'an) | Number | ✅ jika Al-Qur'an | 1–30 |
| Surat/Ayat (jika Al-Qur'an) | Text | ❌ | Misal: "Al-Baqarah ayat 50" |
| Kelancaran | Select | ✅ | Lancar / Cukup / Perlu Latihan |
| Catatan Tajwid | Textarea | ❌ | Misal: "Ghunnah di ayat 5 masih kurang" |
| Catatan Umum | Textarea | ❌ | |

**Riwayat Baca per Murid:**
- Tabel: Tanggal, Jenis, Jilid/Juz, Halaman/Ayat, Kelancaran, Catatan
- Grafik progress (opsional v2): halaman dari waktu ke waktu

---

##### Domain 2 — Penilaian Hafalan

**Tujuan:** Melacak hafalan surat, hadist, dan doa murid.

**Form Input:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Murid | Select | ✅ | |
| Tanggal | Date | ✅ | |
| Jenis Hafalan | Select | ✅ | Surat Pendek / Hadist / Doa |
| Item | Select / Text | ✅ | Pilih dari daftar surat/hadist/doa atau ketik manual |
| Status | Select | ✅ | Hafal Sempurna / Hafal dengan Sedikit Kesalahan / Perlu Diulang |
| Catatan | Textarea | ❌ | |

**Tampilan Riwayat Hafalan per Murid:**
- Checklist visual per kategori (Surat Pendek, Hadist, Doa)
- Item yang "Hafal Sempurna" atau "Hafal dengan Sedikit Kesalahan": ✅ hijau
- Item "Perlu Diulang": 🔄 kuning
- Item belum pernah dinilai: ○ abu-abu

---

##### Domain 3 — Penilaian Tulis

**Tujuan:** Menilai kemampuan menulis huruf Arab / kaligrafi murid.

**Form Input:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Murid | Select | ✅ | |
| Tanggal | Date | ✅ | |
| Materi Tulis | Text | ✅ | Contoh: "Huruf Alif-Ba-Ta", "Salinan Surat Al-Ikhlas" |
| Nilai | Number | ✅ | Skala 0–100 |
| Grade | Auto | — | Dihitung otomatis: A (90–100), B (75–89), C (60–74), D (< 60) |
| Catatan | Textarea | ❌ | |

**Riwayat Tulis per Murid:**
- Tabel: Tanggal, Materi, Nilai, Grade, Catatan

---

##### Domain 4 — Penilaian Praktik

**Tujuan:** Menilai kemampuan praktik ibadah murid menggunakan checklist komponen.

**Form Input:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Murid | Select | ✅ | |
| Tanggal | Date | ✅ | |
| Jenis Praktik | Select | ✅ | Wudhu / Sholat Fardhu / Sholat Sunnah / Tayamum / Membaca Doa |
| Checklist Komponen | Checklist | ✅ | List auto-muncul sesuai jenis praktik |
| Catatan | Textarea | ❌ | |

**Checklist Komponen per Jenis Praktik:**

*Wudhu:*
- [ ] Niat Wudhu
- [ ] Membaca Bismillah
- [ ] Membasuh Kedua Telapak Tangan
- [ ] Berkumur-kumur
- [ ] Membasuh/Menghirup Air ke Hidung
- [ ] Membasuh Muka
- [ ] Membasuh Tangan Kanan hingga Siku
- [ ] Membasuh Tangan Kiri hingga Siku
- [ ] Mengusap Kepala
- [ ] Mengusap Kedua Daun Telinga
- [ ] Membasuh Kaki Kanan hingga Mata Kaki
- [ ] Membasuh Kaki Kiri hingga Mata Kaki
- [ ] Tertib (Berurutan)
- [ ] Doa Setelah Wudhu

*Sholat Fardhu (per rakaat — disederhanakan):*
- [ ] Niat Sholat
- [ ] Takbiratul Ihram
- [ ] Doa Iftitah
- [ ] Membaca Al-Fatihah
- [ ] Membaca Surat/Ayat
- [ ] Ruku' dengan Benar + Membaca Tasbih Ruku
- [ ] I'tidal + Doa I'tidal
- [ ] Sujud dengan Benar + Membaca Tasbih Sujud
- [ ] Duduk antara Dua Sujud + Doa
- [ ] Tasyahud Awal (jika lebih dari 2 rakaat)
- [ ] Tasyahud Akhir + Shalawat
- [ ] Salam
- [ ] Tertib

*(Komponen untuk Sholat Sunnah dan Tayamum mengikuti pola serupa, disesuaikan dengan fikih)*

**Riwayat Praktik per Murid:**
- Tabel: Tanggal, Jenis Praktik, Jumlah komponen terpenuhi / total, Catatan
- Detail per penilaian: tampil checklist komponen lengkap

---

#### 4.3.6 Manajemen Konten

##### Al-Qur'an

- Data dari seeding database — **read-only** di panel admin
- Admin dapat melihat daftar 114 surat dan detail ayat
- Admin dapat men-**tag surat** sebagai rekomendasi untuk level tertentu (tagging level_target)
- Contoh: Surat Al-Fatihah, Al-Ikhlas, An-Nas ditag untuk level "Pra-Iqra" dan "Iqra 1"

##### Doa-Doa

- Data awal dari seeding (dataset publik yang sudah dikurasi)
- Admin dapat **Tambah Doa Baru:**

| Field | Tipe | Wajib |
|---|---|:---:|
| Judul Doa | Text | ✅ |
| Teks Arab | Textarea | ✅ |
| Transliterasi Latin | Textarea | ✅ |
| Terjemahan Indonesia | Textarea | ✅ |
| Kategori | Select | ✅ |
| Status | Toggle | ✅ |

- Kategori doa: Harian, Sebelum/Sesudah Makan, Sebelum Tidur, Belajar, Bepergian, Masuk/Keluar Rumah, Masuk/Keluar Masjid, Keselamatan, Lainnya
- Admin dapat **Edit** dan **Nonaktifkan** (soft delete) doa

##### Hadist

- Data awal dari seeding (dikurasi dari dataset publik)
- Admin dapat **Tambah Hadist Baru:**

| Field | Tipe | Wajib |
|---|---|:---:|
| Teks Arab | Textarea | ✅ |
| Terjemahan Indonesia | Textarea | ✅ |
| Sumber Kitab | Text | ✅ | Misal: HR. Bukhari |
| Perawi | Text | ❌ | |
| Kategori/Tag | Text | ❌ | |
| Status | Toggle | ✅ |

- Admin dapat **Edit** dan **Nonaktifkan** hadist

##### Cerita Kisah

Full CRUD oleh Admin.

**Form Tambah/Edit Cerita:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Judul | Text | ✅ | Max 255 char |
| Thumbnail | Upload | ❌ | JPEG/PNG/WebP, max 1MB |
| Kategori | Select | ✅ | Kisah Nabi / Kisah Sahabat / Kisah Islami Lainnya |
| Level Target | Select | ❌ | Null = semua level |
| Isi Konten | Rich Text Editor (TipTap) | ✅ | Mendukung: heading, bold, italic, paragraf, gambar, link |
| Status | Select | ✅ | Draft / Published |

**Daftar Cerita (Admin View):**
- Tabel: Thumbnail, Judul, Kategori, Level Target, Status, Tanggal Update, Aksi
- Filter: Kategori, Status, Level Target

##### Panduan Praktik

Full CRUD oleh Admin. Konten berbasis **langkah-langkah** (step-by-step).

**Form Tambah/Edit Panduan:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Judul | Text | ✅ | Contoh: "Cara Wudhu yang Benar" |
| Cover Image | Upload | ❌ | |
| Deskripsi Singkat | Textarea | ✅ | Summary 1-2 kalimat |
| Jenis Praktik | Select | ✅ | Wudhu / Sholat / Tayamum / dll |
| Level Target | Select | ❌ | Null = semua level |
| Status | Select | ✅ | Draft / Published |

**Manajemen Langkah:**
- Tambah langkah baru (nomor urut auto-increment)
- Setiap langkah:
  | Sub-field | Tipe | Wajib |
  |---|---|:---:|
  | Nomor Urut | Auto | — |
  | Judul Langkah | Text | ✅ |
  | Deskripsi | Textarea | ✅ |
  | Gambar | Upload | ❌ |
- Reorder langkah: tombol ↑ / ↓ untuk naik/turun urutan
- Hapus langkah (dengan konfirmasi)

---

#### 4.3.7 Pengumuman

**Form Buat Pengumuman:**

| Field | Tipe | Wajib | Keterangan |
|---|---|:---:|---|
| Judul | Text | ✅ | Max 255 char |
| Isi | Textarea | ✅ | Teks polos, tidak perlu rich text |
| Target Penerima | Select | ✅ | Semua Murid / Per Level Tertentu |
| Level Target | Select | Kondisional | Wajib jika target = Per Level |
| Tanggal Mulai | Date | ✅ | Default: hari ini |
| Tanggal Berakhir | Date | ❌ | Jika diisi, pengumuman auto-hidden setelah tanggal ini |
| Status | Select | ✅ | Draft / Published |

**Aturan Tampil di Portal Murid:**
Pengumuman akan muncul ke murid jika **semua kondisi** terpenuhi:
1. `status = 'published'`
2. `tanggal_mulai <= HARI_INI`
3. `tanggal_berakhir >= HARI_INI` ATAU `tanggal_berakhir IS NULL`
4. `target_semua = true` ATAU `level_target_id = current_level_id murid tersebut`

**Daftar Pengumuman (Admin):**
- Tabel: Judul, Target, Periode Tampil, Status, Aksi (Edit/Hapus)
- Filter: Status, Periode
- Badge visual: "Aktif" (hijau) / "Akan Datang" (biru) / "Berakhir" (abu)

---

#### 4.3.8 Laporan & Export

##### Laporan Per Murid

**Input:**
- Pilih Murid
- Pilih Rentang Waktu (dari tanggal — sampai tanggal, atau pilih bulan)

**Konten Laporan:**
1. **Data Identitas:** Foto, nama lengkap, nama panggilan, tanggal lahir, level saat ini
2. **Rekapitulasi Absensi:**
   - Total sesi dalam periode
   - Hadir X (X%), Izin Y, Sakit Z, Alpha W
3. **Riwayat Level:** Kapan naik/turun level, oleh siapa
4. **Penilaian Baca:** Progress terbaru (jilid/halaman), catatan tajwid terakhir
5. **Penilaian Hafalan:** Checklist surat/hadist/doa yang sudah hafal
6. **Penilaian Tulis:** Nilai & grade terbaru + riwayat nilai dalam periode
7. **Penilaian Praktik:** Rekap per jenis praktik, komponen yang sudah terpenuhi

**Export:**
- **PDF:** Format rapor yang rapi (menggunakan template barryvdh/dompdf)
- **Excel:** Satu sheet per domain, mudah difilter dan dianalisis

##### Laporan Keseluruhan / Rekap Kelas

**Konten:**
- Rekap absensi semua murid untuk periode tertentu (tabel: nama murid vs tanggal)
- Persentase kehadiran per murid
- Perbandingan nilai tulis antar murid dalam periode

**Export:**
- Excel: satu file multi-sheet

---

### 4.4 Portal Murid

#### 4.4.1 Dashboard Murid

**Layout:**
- Header: Greeting "Assalamu'alaikum, [Nama Panggilan]" + foto murid
- Widget Level: nama level saat ini + visual progress bar (posisi dalam jenjang)
- Widget Absensi: "Bulan ini: Hadir X dari Y sesi"
- Widget Nilai Ringkas: card kecil per domain (Baca / Hafalan / Tulis / Praktik) — status/nilai terakhir
- Pengumuman terbaru: judul + preview isi
- Menu navigasi bawah (bottom navigation): Beranda | Belajar | Nilai | Absensi | Pengumuman

---

#### 4.4.2 Konten Belajar — Al-Qur'an

**Daftar Surat (`/murid/quran`):**
- Grid atau list 114 surat
- Setiap item: nomor, nama Arab, nama Indonesia, jumlah ayat, tempat turun (Makkiyah/Madaniyah)
- Search bar: cari berdasarkan nama surat atau nomor
- Badge "Untukmu ⭐" pada surat yang ditag admin untuk level murid tersebut
- Tampilkan surat bertaglevel murid di bagian paling atas

**Detail Surat (`/murid/quran/{nomor_surat}`):**
- Header: Nama Surat Arab + Indonesia + info (jumlah ayat, tempat turun)
- Basmalah di awal (kecuali Surat At-Taubah)
- List ayat:
  - Teks Arab: font Uthmani/Amiri, ukuran besar (min 24px), right-to-left
  - Nomor ayat: dalam lingkaran kecil
  - Terjemahan: font regular, left-to-right, ukuran normal
- Pembagian halaman: 10 ayat per halaman (bisa dikonfigurasi)
- Tombol navigasi: Surat Sebelumnya / Surat Berikutnya
- **Offline:** surat yang pernah dibuka di-cache oleh Service Worker

---

#### 4.4.3 Konten Belajar — Doa-Doa

**Daftar Doa (`/murid/doa`):**
- Filter tab per kategori (Harian / Makan / Tidur / dll)
- Setiap item: nama doa + preview teks Arab (1 baris)
- Search doa

**Detail Doa:**
- Nama doa (judul)
- Teks Arab: font besar, right-to-left
- Transliterasi Latin (italic)
- Terjemahan Indonesia

**Offline:** Semua doa di-cache saat pertama load halaman doa (data kecil, bisa preload semua)

---

#### 4.4.4 Konten Belajar — Hadist

**Daftar Hadist (`/murid/hadist`):**
- Filter per kategori/tag
- Setiap item: preview teks terjemahan + sumber
- Search hadist

**Detail Hadist:**
- Teks Arab
- Terjemahan Indonesia
- Sumber: nama kitab + perawi

**Offline:** Semua hadist di-cache saat pertama load halaman hadist

---

#### 4.4.5 Konten Belajar — Cerita Kisah

**Daftar Cerita (`/murid/cerita`):**
- Grid card: thumbnail, judul, kategori, tanggal publish
- Filter: kategori (Kisah Nabi / Kisah Sahabat / Islami Lainnya)
- Badge "Untukmu ⭐" untuk konten yang level-targeted ke level murid

**Detail Cerita (`/murid/cerita/{id}`):**
- Thumbnail besar
- Judul
- Kategori
- Konten rich text (paragraf, gambar, heading)

---

#### 4.4.6 Konten Belajar — Panduan Praktik

**Daftar Panduan (`/murid/panduan`):**
- Grid card: cover image, judul, jenis praktik, jumlah langkah
- Filter: jenis praktik
- Badge "Untukmu ⭐" untuk konten yang level-targeted ke level murid

**Detail Panduan (`/murid/panduan/{id}`):**
- Cover image + judul + deskripsi
- Indikator langkah: "Langkah X dari Y"
- Setiap langkah: nomor, judul, gambar (jika ada), deskripsi teks
- Navigasi: Tombol ← Sebelumnya / Berikutnya →
- Tombol "Kembali ke Daftar"

**Offline:** Panduan yang pernah dibuka di-cache (teks + gambar)

---

#### 4.4.7 Nilai & Progress Murid

**Layout Tabs:** Baca | Hafalan | Tulis | Praktik

**Tab Baca:**
- Progress terkini: "Sedang di Iqra [X] Halaman [Y]"
- Kelancaran terakhir: badge (Lancar / Cukup / Perlu Latihan)
- Catatan tajwid terakhir
- Riwayat penilaian (tabel: tanggal, jenis, jilid/juz, halaman, kelancaran)

**Tab Hafalan:**
- Checklist visual per kategori:
  - **Surat Pendek:** ✅ Al-Fatihah ✅ Al-Ikhlas ○ Al-Falaq ...
  - **Hadist:** ✅ Hadist Niat ○ Hadist Kebersihan ...
  - **Doa:** ✅ Doa Makan ✅ Doa Tidur ...
- Keterangan warna: Hijau = Hafal Sempurna, Kuning = Perlu Diulang, Abu = Belum Dinilai
- Riwayat penilaian hafalan (tabel)

**Tab Tulis:**
- Nilai terakhir + Grade (ditampilkan besar)
- Materi terakhir yang dinilai
- Riwayat nilai (tabel: tanggal, materi, nilai, grade)

**Tab Praktik:**
- Checklist per jenis praktik yang sudah pernah dinilai:
  - Wudhu: X/Y komponen terpenuhi
  - Sholat Fardhu: X/Y komponen terpenuhi
  - dst.
- Detail per jenis: expand untuk lihat komponen mana yang sudah/belum

---

#### 4.4.8 Riwayat Absensi

**Tampilan:**
- Kalender bulanan dengan warna per hari:
  - Hijau: Hadir
  - Kuning: Izin/Sakit
  - Merah: Alpha
  - Abu: Tidak ada sesi
- Navigasi bulan: ← / →
- Rekap di bawah kalender: Hadir: X | Izin: Y | Sakit: Z | Alpha: W | Total Sesi: N
- Detail list: klik tanggal → modal/expand lihat detail sesi (pagi/sore/malam) + catatan

---

#### 4.4.9 Pengumuman Murid

- List pengumuman yang aktif dan relevan (diurutkan terbaru)
- Setiap item: judul, preview isi (50 kata), tanggal pengumuman
- Klik untuk baca full
- Tidak ada fitur read/unread di MVP (disederhanakan)

---

## 5. Technical Architecture

### 5.1 Tech Stack Final

| Layer | Teknologi | Versi | Keterangan |
|---|---|---|---|
| Backend Framework | Laravel | 11.x | Framework utama |
| Database | MySQL | 8.x | |
| Auth | Laravel Breeze | — | Login/register base |
| Role & Permission | Spatie Laravel Permission | ^6.x | Multi-role management |
| Admin UI | Livewire | 3.x | Server-rendered components |
| Admin UI JS | Alpine.js | 3.x | Interaktivitas ringan |
| Portal Murid UI | Laravel Blade + Alpine.js | — | Ringan, offline-friendly |
| PWA | Service Worker (manual) | — | Tidak pakai package, lebih kontrol |
| PWA Manifest | Web App Manifest (JSON) | — | Installable PWA |
| Rich Text Editor | TipTap | 2.x | Cerita Kisah & Panduan |
| Export PDF | barryvdh/laravel-dompdf | — | Laporan PDF |
| Export Excel | Maatwebsite/Laravel-Excel | ^3.x | Laporan Excel |
| Image Processing | Intervention Image | ^3.x | Resize + compress upload |
| CSS Framework | Tailwind CSS | 3.x | |
| Build Tool | Vite | — | Default Laravel 11 |
| Al-Qur'an Data | quran-json (GitHub) | — | Seed sekali ke DB |
| Doa Data | Dataset publik (JSON) | — | Kurasi → seed ke DB |
| Hadist Data | hadith.gading.dev API | — | Ambil → kurasi → seed ke DB |

### 5.2 Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                        Browser / PWA                            │
│                                                                 │
│  ┌─────────────────┐          ┌──────────────────────────────┐  │
│  │  Admin Panel    │          │      Portal Murid            │  │
│  │  /admin/*       │          │      /murid/*                │  │
│  │  /superadmin/*  │          │                              │  │
│  │                 │          │  [Service Worker]            │  │
│  │  Livewire 3 +   │          │  - Cache konten offline      │  │
│  │  Alpine.js      │          │  - Blade + Alpine.js         │  │
│  │                 │          │  - Offline fallback page     │  │
│  │  ❌ No offline  │          │  ✅ Offline-capable          │  │
│  └────────┬────────┘          └──────────────┬───────────────┘  │
└───────────┼───────────────────────────────────┼─────────────────┘
            │ HTTP Request                       │ HTTP (cached by SW)
            ▼                                   ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Laravel 11 Application                      │
│                                                                 │
│  ┌─────────────────┐  ┌───────────────────┐  ┌──────────────┐  │
│  │   Controllers   │  │    Livewire       │  │    Models    │  │
│  │  Superadmin/    │  │    Components     │  │              │  │
│  │  Admin/         │  │    (Admin Panel)  │  │   Eloquent   │  │
│  │  Murid/         │  └───────────────────┘  │   ORM        │  │
│  └─────────────────┘                         └──────┬───────┘  │
│                                                     │           │
│  ┌──────────┐  ┌──────────────┐  ┌──────────────┐  │           │
│  │ Exports  │  │  Spatie      │  │   Storage    │  │           │
│  │ PDF/XLSX │  │  Permission  │  │   (Images)   │  │           │
│  └──────────┘  └──────────────┘  └──────────────┘  │           │
└────────────────────────────────────────────────────┼───────────┘
                                                     │
                                    ┌────────────────▼──────────┐
                                    │         MySQL 8.x          │
                                    │                            │
                                    │  users, admins, absensis,  │
                                    │  penilaians, surahs,       │
                                    │  ayats, duas, hadiths,     │
                                    │  cerita_kisahs,            │
                                    │  panduan_praktiks, ...     │
                                    └────────────────────────────┘
```

### 5.3 Struktur Folder Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Superadmin/
│   │   │   ├── DashboardController.php
│   │   │   └── AdminController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── MuridController.php
│   │   │   ├── LevelController.php
│   │   │   ├── AbsensiController.php
│   │   │   ├── PenilaianBacaController.php
│   │   │   ├── PenilaianHafalanController.php
│   │   │   ├── PenilaianTulisController.php
│   │   │   ├── PenilaianPraktikController.php
│   │   │   ├── KontenDoaController.php
│   │   │   ├── KontenHadistController.php
│   │   │   ├── CeritaController.php
│   │   │   ├── PanduanPraktikController.php
│   │   │   ├── PengumumanController.php
│   │   │   └── LaporanController.php
│   │   ├── Murid/
│   │   │   ├── DashboardController.php
│   │   │   ├── QuranController.php
│   │   │   ├── DoaController.php
│   │   │   ├── HadistController.php
│   │   │   ├── CeritaController.php
│   │   │   ├── PanduanController.php
│   │   │   ├── NilaiController.php
│   │   │   ├── AbsensiController.php
│   │   │   └── PengumumanController.php
│   │   └── Auth/
│   │       └── LoginController.php
│   └── Middleware/
│       ├── EnsureSuperadmin.php
│       ├── EnsureAdmin.php
│       └── EnsureMurid.php
│
├── Livewire/
│   └── Admin/
│       ├── DashboardStats.php
│       ├── AbsensiInput.php
│       ├── PenilaianBacaForm.php
│       ├── PenilaianHafalanForm.php
│       ├── PenilaianTulisForm.php
│       ├── PenilaianPraktikForm.php
│       └── PengumumanForm.php
│
├── Models/
│   ├── User.php                    (Murid)
│   ├── Admin.php                   (Pengurus + Superadmin)
│   ├── Level.php
│   ├── UserLevelHistory.php
│   ├── Absensi.php
│   ├── PenilaianBaca.php
│   ├── PenilaianHafalan.php
│   ├── PenilaianTulis.php
│   ├── PenilaianPraktik.php
│   ├── PenilaianPraktikKomponen.php
│   ├── Surah.php
│   ├── Ayat.php
│   ├── Doa.php
│   ├── Hadist.php
│   ├── CeritaKisah.php
│   ├── PanduanPraktik.php
│   ├── LangkahPanduan.php
│   └── Pengumuman.php
│
├── Exports/
│   ├── LaporanMuridPdfExport.php
│   ├── LaporanMuridExcelExport.php
│   └── LaporanKeseluruhanExcelExport.php
│
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/
│   ├── 0001_create_admins_table.php
│   ├── 0002_create_levels_table.php
│   ├── 0003_create_users_table.php
│   ├── 0004_create_user_level_histories_table.php
│   ├── 0005_create_absensis_table.php
│   ├── 0006_create_penilaian_bacas_table.php
│   ├── 0007_create_penilaian_hafalans_table.php
│   ├── 0008_create_penilaian_tulises_table.php
│   ├── 0009_create_penilaian_praktiks_table.php
│   ├── 0010_create_penilaian_praktik_komponens_table.php
│   ├── 0011_create_surahs_table.php
│   ├── 0012_create_ayats_table.php
│   ├── 0013_create_duas_table.php
│   ├── 0014_create_hadiths_table.php
│   ├── 0015_create_cerita_kisahs_table.php
│   ├── 0016_create_panduan_praktiks_table.php
│   ├── 0017_create_langkah_panduans_table.php
│   └── 0018_create_pengumumans_table.php
│
└── seeders/
    ├── DatabaseSeeder.php
    ├── LevelSeeder.php             (8 level)
    ├── QuranSeeder.php             (114 surat + ~6348 ayat dari JSON)
    ├── DoaSeeder.php               (~50-100 doa terpilih)
    ├── HadistSeeder.php            (~50-100 hadist terpilih)
    └── DefaultAdminSeeder.php      (1 superadmin default)

public/
├── sw.js                           (Service Worker)
├── manifest.webmanifest
└── icons/
    ├── icon-192.png
    └── icon-512.png

resources/
├── views/
│   ├── layouts/
│   │   ├── admin.blade.php
│   │   ├── superadmin.blade.php
│   │   └── murid.blade.php
│   ├── superadmin/
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── murid/
│   │   ├── absensi/
│   │   ├── penilaian/
│   │   ├── konten/
│   │   ├── pengumuman/
│   │   └── laporan/
│   └── murid/
│       ├── dashboard.blade.php
│       ├── quran/
│       ├── doa/
│       ├── hadist/
│       ├── cerita/
│       ├── panduan/
│       ├── nilai/
│       ├── absensi/
│       └── pengumuman/
└── js/
    ├── app.js
    └── pwa/
        ├── register-sw.js
        └── cache-strategy.js

routes/
├── web.php
├── superadmin.php     (Group route superadmin)
├── admin.php          (Group route admin)
└── murid.php          (Group route murid)
```

### 5.4 Route Structure

```php
// routes/web.php
Route::get('/', fn() => redirect('/login'));
Route::get('/login', [LoginController::class, 'showForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// routes/superadmin.php
Route::prefix('superadmin')->middleware(['auth:admin', 'role:superadmin'])->group(function () {
    Route::get('/dashboard', ...);
    Route::resource('/admins', AdminController::class);
    Route::get('/settings', ...);
    Route::put('/settings', ...);
});

// routes/admin.php
Route::prefix('admin')->middleware(['auth:admin', 'role:admin|superadmin'])->group(function () {
    Route::get('/dashboard', ...);
    Route::resource('/murid', MuridController::class);
    Route::get('/level', [LevelController::class, 'index']);
    Route::post('/murid/{murid}/naik-level', [LevelController::class, 'naikLevel']);
    Route::post('/murid/{murid}/turun-level', [LevelController::class, 'turunLevel']);
    Route::resource('/absensi', AbsensiController::class);
    // ... penilaian, konten, pengumuman, laporan
});

// routes/murid.php
Route::prefix('murid')->middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', ...);
    Route::get('/quran', ...);
    Route::get('/quran/{surah}', ...);
    // ... doa, hadist, cerita, panduan, nilai, absensi, pengumuman
});
```

---

## 6. Database Schema (ERD)

### 6.1 Tabel Lengkap

#### `admins`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| nama | VARCHAR(100) | NOT NULL | |
| email | VARCHAR(100) | NOT NULL, UNIQUE | |
| password | VARCHAR(255) | NOT NULL | bcrypt |
| no_hp | VARCHAR(20) | NULLABLE | |
| role | ENUM('superadmin','admin') | NOT NULL, DEFAULT 'admin' | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| remember_token | VARCHAR(100) | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

#### `levels`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| nama | VARCHAR(50) | NOT NULL | Pra-Iqra, Iqra 1, ... Al-Qur'an |
| urutan | TINYINT | NOT NULL, UNIQUE | 1–8 |
| deskripsi | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Seed Data:**
| urutan | nama |
|---|---|
| 1 | Pra-Iqra |
| 2 | Iqra 1 |
| 3 | Iqra 2 |
| 4 | Iqra 3 |
| 5 | Iqra 4 |
| 6 | Iqra 5 |
| 7 | Iqra 6 |
| 8 | Al-Qur'an |

#### `users` (Murid)
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| nama_lengkap | VARCHAR(100) | NOT NULL | |
| nama_panggilan | VARCHAR(50) | NOT NULL | |
| username | VARCHAR(50) | NOT NULL, UNIQUE | Untuk login |
| password | VARCHAR(255) | NOT NULL | bcrypt |
| tempat_lahir | VARCHAR(100) | NULLABLE | |
| tanggal_lahir | DATE | NULLABLE | |
| jenis_kelamin | ENUM('L','P') | NOT NULL | |
| nama_orang_tua | VARCHAR(100) | NULLABLE | |
| no_hp_orang_tua | VARCHAR(20) | NULLABLE | |
| alamat | TEXT | NULLABLE | |
| foto | VARCHAR(255) | NULLABLE | Path relatif ke storage |
| tanggal_masuk | DATE | NOT NULL | |
| current_level_id | BIGINT UNSIGNED | FK → levels.id | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| remember_token | VARCHAR(100) | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

#### `user_level_histories`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| user_id | BIGINT UNSIGNED | FK → users.id, NOT NULL | |
| level_id | BIGINT UNSIGNED | FK → levels.id, NOT NULL | Level setelah perubahan |
| level_sebelumnya_id | BIGINT UNSIGNED | FK → levels.id, NULLABLE | Null jika level awal |
| admin_id | BIGINT UNSIGNED | FK → admins.id, NOT NULL | Yang melakukan perubahan |
| tipe | ENUM('awal','naik','turun') | NOT NULL | |
| catatan | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | Tanggal perubahan |

#### `absensis`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | |
| user_id | BIGINT UNSIGNED | FK → users.id, NOT NULL | |
| admin_id | BIGINT UNSIGNED | FK → admins.id, NOT NULL | |
| tanggal | DATE | NOT NULL | |
| sesi | VARCHAR(20) | NOT NULL | Nilai dari konfigurasi sesi |
| status | ENUM('hadir','izin','sakit','alpha') | NOT NULL | |
| catatan | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Unique Index:** `(user_id, tanggal, sesi)` — satu murid tidak bisa punya 2 entri absensi untuk sesi yang sama di hari yang sama.

#### `penilaian_bacas`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| tanggal | DATE | NOT NULL | |
| jenis_bacaan | ENUM('iqra','alquran','tilawah') | NOT NULL | |
| jilid_juz | TINYINT UNSIGNED | NULLABLE | Jilid (1-6) atau Juz (1-30) |
| halaman_ayat | SMALLINT UNSIGNED | NULLABLE | |
| keterangan_posisi | VARCHAR(100) | NULLABLE | Misal: "Al-Baqarah ayat 30" |
| kelancaran | ENUM('lancar','cukup','perlu_latihan') | NOT NULL | |
| catatan_tajwid | TEXT | NULLABLE | |
| catatan_umum | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |

#### `penilaian_hafalans`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| tanggal | DATE | NOT NULL | |
| jenis_hafalan | ENUM('surat','hadist','doa') | NOT NULL | |
| nama_item | VARCHAR(150) | NOT NULL | Nama surat/hadist/doa |
| status | ENUM('hafal_sempurna','hafal_dengan_kesalahan','perlu_diulang') | NOT NULL | |
| catatan | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |

#### `penilaian_tulises`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| tanggal | DATE | NOT NULL | |
| materi | VARCHAR(200) | NOT NULL | |
| nilai | TINYINT UNSIGNED | NOT NULL | 0–100 |
| grade | CHAR(1) | NOT NULL | A/B/C/D (auto-computed) |
| catatan | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |

#### `penilaian_praktiks`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| tanggal | DATE | NOT NULL | |
| jenis_praktik | ENUM('wudhu','sholat_fardhu','sholat_sunnah','tayamum','membaca_doa') | NOT NULL | |
| catatan | TEXT | NULLABLE | |
| created_at | TIMESTAMP | NULLABLE | |

#### `penilaian_praktik_komponens`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| penilaian_praktik_id | BIGINT UNSIGNED | FK → penilaian_praktiks.id | |
| nama_komponen | VARCHAR(100) | NOT NULL | |
| is_terpenuhi | BOOLEAN | NOT NULL, DEFAULT false | |

#### `surahs`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | SMALLINT UNSIGNED | PK | Nomor surat 1–114 |
| nama_arab | VARCHAR(100) | NOT NULL | |
| nama_latin | VARCHAR(100) | NOT NULL | |
| nama_indonesia | VARCHAR(100) | NOT NULL | |
| arti | VARCHAR(200) | NULLABLE | |
| tempat_turun | ENUM('makkah','madinah') | NOT NULL | |
| jumlah_ayat | SMALLINT | NOT NULL | |
| created_at | TIMESTAMP | NULLABLE | |

#### `ayats`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| surah_id | SMALLINT UNSIGNED | FK → surahs.id, INDEX | |
| nomor_ayat | SMALLINT UNSIGNED | NOT NULL | |
| teks_arab | TEXT | NOT NULL | |
| teks_latin | TEXT | NULLABLE | Transliterasi |
| terjemahan | TEXT | NOT NULL | |

**Index:** `(surah_id, nomor_ayat)` untuk query cepat

#### `duas` (Doa-doa)
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| judul | VARCHAR(200) | NOT NULL | |
| teks_arab | TEXT | NOT NULL | |
| transliterasi | TEXT | NOT NULL | |
| terjemahan | TEXT | NOT NULL | |
| kategori | VARCHAR(100) | NOT NULL | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| urutan | SMALLINT | NULLABLE | Untuk ordering dalam kategori |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

#### `hadiths`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| teks_arab | TEXT | NOT NULL | |
| terjemahan | TEXT | NOT NULL | |
| sumber_kitab | VARCHAR(100) | NOT NULL | HR. Bukhari, dll |
| perawi | VARCHAR(200) | NULLABLE | |
| kategori | VARCHAR(100) | NULLABLE | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

#### `cerita_kisahs`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| judul | VARCHAR(255) | NOT NULL | |
| thumbnail | VARCHAR(255) | NULLABLE | |
| konten | LONGTEXT | NOT NULL | HTML dari TipTap (disanitasi) |
| kategori | ENUM('kisah_nabi','kisah_sahabat','islami_lainnya') | NOT NULL | |
| level_target_id | BIGINT UNSIGNED | FK → levels.id, NULLABLE | Null = semua level |
| status | ENUM('draft','published') | NOT NULL, DEFAULT 'draft' | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

#### `panduan_praktiks`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| judul | VARCHAR(255) | NOT NULL | |
| cover_image | VARCHAR(255) | NULLABLE | |
| deskripsi | TEXT | NOT NULL | |
| jenis_praktik | VARCHAR(100) | NOT NULL | |
| level_target_id | BIGINT UNSIGNED | FK → levels.id, NULLABLE | |
| status | ENUM('draft','published') | NOT NULL, DEFAULT 'draft' | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

#### `langkah_panduans`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| panduan_praktik_id | BIGINT UNSIGNED | FK → panduan_praktiks.id | |
| nomor_urut | TINYINT UNSIGNED | NOT NULL | |
| judul_langkah | VARCHAR(255) | NOT NULL | |
| deskripsi | TEXT | NOT NULL | |
| gambar | VARCHAR(255) | NULLABLE | |

**Index:** `(panduan_praktik_id, nomor_urut)`

#### `pengumumans`
| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| admin_id | BIGINT UNSIGNED | FK → admins.id | |
| judul | VARCHAR(255) | NOT NULL | |
| isi | TEXT | NOT NULL | |
| target_semua | BOOLEAN | NOT NULL, DEFAULT true | |
| level_target_id | BIGINT UNSIGNED | FK → levels.id, NULLABLE | |
| tanggal_mulai | DATE | NOT NULL | |
| tanggal_berakhir | DATE | NULLABLE | |
| status | ENUM('draft','published') | NOT NULL, DEFAULT 'draft' | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

### 6.2 ERD Diagram (Tekstual)

```
admins ──────────────────────────────────────────────────────────────┐
  │ 1                                                                 │
  │                                                                   │
  ├── M absensis                                                      │
  ├── M penilaian_bacas                                               │
  ├── M penilaian_hafalans                                            │
  ├── M penilaian_tulises                                             │
  ├── M penilaian_praktiks                                            │
  ├── M user_level_histories (admin_id)                               │
  ├── M cerita_kisahs                                                 │
  ├── M panduan_praktiks                                              │
  └── M pengumumans                                                   │
                                                                      │
levels ─────────────────┐                                             │
  │ 1                   │                                             │
  ├── M users           │ (current_level_id)                          │
  ├── M user_level_histories (level_id, level_sebelumnya_id)          │
  ├── M cerita_kisahs   │ (level_target_id)                           │
  ├── M panduan_praktiks│ (level_target_id)                           │
  └── M pengumumans     │ (level_target_id)                           │
                        │                                             │
users ──────────────────┘                                             │
  │ 1                                                                 │
  ├── M absensis                                                      │
  ├── M penilaian_bacas                                               │
  ├── M penilaian_hafalans                                            │
  ├── M penilaian_tulises                                             │
  ├── M penilaian_praktiks ──── M penilaian_praktik_komponens         │
  └── M user_level_histories                                          │
                                                                      │
surahs ──── M ayats                                                   │
                                                                      │
panduan_praktiks ──── M langkah_panduans                              │
                                                                      │
[duas, hadiths, cerita_kisahs, pengumumans: standalone tables] ───────┘
```

---

## 7. PWA & Offline Strategy

### 7.1 Kategori Offline

| Konten | Strategi Cache | Deskripsi |
|---|---|---|
| CSS / JS / Font / Icon | Cache-first, permanent | Asset statik, versi via hash |
| Al-Qur'an (surat pernah dibuka) | Cache-first, TTL 30 hari | Lazy-cache per surat |
| Doa-doa (semua) | Cache-first saat pertama buka | Data kecil, preload saat buka halaman doa |
| Hadist (semua) | Cache-first saat pertama buka | Data kecil, preload saat buka halaman hadist |
| Cerita Kisah (yang pernah dibuka) | Cache-first, TTL 7 hari | Cache on demand |
| Panduan Praktik (yang pernah dibuka) | Cache-first, TTL 7 hari | Cache on demand + gambar |
| Dashboard Murid | Stale-while-revalidate | Tampil cache, update background |
| Nilai & Absensi | Stale-while-revalidate | Tampil cache lama, refresh background |
| Pengumuman | Network-first, fallback cache | Data dinamis, prioritas fresh |
| Offline Fallback Page | Cache-first, permanent | Halaman "Tidak Ada Koneksi" |

### 7.2 Implementasi Service Worker (`public/sw.js`)

```javascript
const CACHE_VERSION = 'v1.0.0';
const STATIC_CACHE = `static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `dynamic-${CACHE_VERSION}`;

// Asset yang di-precache saat SW install
const PRECACHE_ASSETS = [
  '/',
  '/murid/dashboard',
  '/offline',
  '/css/app.css',
  '/js/app.js',
  '/manifest.webmanifest',
  // font files, icons
];

// Install Event: precache static assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(cache => cache.addAll(PRECACHE_ASSETS))
      .then(() => self.skipWaiting())
  );
});

// Activate Event: cleanup old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(key => key !== STATIC_CACHE && key !== DYNAMIC_CACHE)
            .map(key => caches.delete(key))
      )
    ).then(() => self.clients.claim())
  );
});

// Fetch Event: routing strategy per URL pattern
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // Strategy 1: Static assets → Cache-first
  if (isStaticAsset(url)) {
    event.respondWith(cacheFirst(event.request));
    return;
  }

  // Strategy 2: Al-Qur'an, Doa, Hadist → Cache-first
  if (url.pathname.match(/^\/(murid\/quran|murid\/doa|murid\/hadist)/)) {
    event.respondWith(cacheFirst(event.request, DYNAMIC_CACHE));
    return;
  }

  // Strategy 3: Dashboard, Nilai, Absensi → Stale-while-revalidate
  if (url.pathname.match(/^\/(murid\/dashboard|murid\/nilai|murid\/absensi)/)) {
    event.respondWith(staleWhileRevalidate(event.request));
    return;
  }

  // Strategy 4: Pengumuman → Network-first
  if (url.pathname.startsWith('/murid/pengumuman')) {
    event.respondWith(networkFirst(event.request));
    return;
  }

  // Default: Network-first dengan fallback ke offline page
  event.respondWith(networkFirstWithOfflineFallback(event.request));
});
```

### 7.3 Web App Manifest (`public/manifest.webmanifest`)

```json
{
  "name": "TPQ [Nama TPQ]",
  "short_name": "TPQ",
  "description": "Aplikasi Belajar & Monitoring TPQ",
  "start_url": "/murid/dashboard",
  "display": "standalone",
  "orientation": "portrait",
  "background_color": "#FFFFFF",
  "theme_color": "#1B5E20",
  "lang": "id",
  "icons": [
    { "src": "/icons/icon-72.png",  "sizes": "72x72",   "type": "image/png" },
    { "src": "/icons/icon-96.png",  "sizes": "96x96",   "type": "image/png" },
    { "src": "/icons/icon-128.png", "sizes": "128x128", "type": "image/png" },
    { "src": "/icons/icon-144.png", "sizes": "144x144", "type": "image/png" },
    { "src": "/icons/icon-192.png", "sizes": "192x192", "type": "image/png", "purpose": "any maskable" },
    { "src": "/icons/icon-512.png", "sizes": "512x512", "type": "image/png", "purpose": "any maskable" }
  ],
  "screenshots": [],
  "categories": ["education"]
}
```

### 7.4 Registrasi Service Worker

```javascript
// resources/js/pwa/register-sw.js
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then(registration => {
        // Cek update SW
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // Tampilkan notifikasi: "Update tersedia! Muat ulang untuk mendapatkan versi terbaru."
              showUpdateBanner();
            }
          });
        });
      })
      .catch(err => console.error('SW registration failed:', err));
  });
}
```

### 7.5 Halaman Offline Fallback

Halaman `/offline` yang sederhana:
- Ikon offline (SVG)
- Teks: "Tidak ada koneksi internet"
- Keterangan: "Konten yang pernah Anda buka sebelumnya masih bisa dibaca."
- Tombol: "Coba Lagi" (reload)

---

## 8. Content Strategy

### 8.1 Al-Qur'an Data Seeding

**Sumber:** `https://github.com/risan/quran-json`

Format JSON yang tersedia:
```json
{
  "number": 1,
  "name": "الفاتحة",
  "transliteration": "Al-Fatihah",
  "translation": "Pembuka",
  "type": "meccan",
  "total_verses": 7,
  "verses": [
    {
      "number": 1,
      "text": "بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ",
      "transliteration": "Bismi Allāhi ...",
      "translation": "Dengan nama Allah Yang Maha Pengasih, Maha Penyayang."
    }
  ]
}
```

**Proses Seeding:**
```php
// database/seeders/QuranSeeder.php
class QuranSeeder extends Seeder {
    public function run() {
        $data = json_decode(file_get_contents(database_path('data/quran.json')), true);
        foreach ($data as $surahData) {
            $surah = Surah::create([...]);
            foreach ($surahData['verses'] as $verse) {
                Ayat::create([
                    'surah_id'    => $surah->id,
                    'nomor_ayat'  => $verse['number'],
                    'teks_arab'   => $verse['text'],
                    'teks_latin'  => $verse['transliteration'],
                    'terjemahan'  => $verse['translation'],
                ]);
            }
        }
    }
}
```

**Estimasi Data:** 114 surat, ~6.348 ayat. Storage di MySQL: ~5–10MB.

---

### 8.2 Doa-doa Seeding

**Sumber:** Dataset JSON publik + kurasi manual

**Doa yang Di-seed (Rekomendasi ~60 doa):**
- Doa Sehari-hari: sebelum makan, sesudah makan, sebelum tidur, bangun tidur, masuk kamar mandi, keluar kamar mandi
- Doa Belajar: sebelum belajar, sesudah belajar
- Doa Bepergian: keluar rumah, naik kendaraan
- Doa Tempat Ibadah: masuk masjid, keluar masjid, azan
- Doa Keselamatan dan Perlindungan
- Doa-doa Sholat: iftitah, qunut, sesudah sholat
- Doa Lainnya: doa orang tua, doa untuk negeri

**Format file seed:** `database/data/doa.json`

---

### 8.3 Hadist Seeding

**Sumber:** `https://api.hadith.gading.dev`

API menyediakan 9 kitab: Bukhari, Muslim, Abu Daud, Tirmidzi, Nasai, Ibnu Majah, Ahmad, Malik, Darimi.

**Proses kurasi:** Pilih hadist yang:
- Pendek (terjemahan < 200 kata)
- Relevan untuk anak (adab, kebersihan, kejujuran, menghormati orang tua, menuntut ilmu)
- Mudah dipahami

**Estimasi:** ~80 hadist terpilih

**Format file seed:** `database/data/hadist.json`

---

### 8.4 Content per Level

- Semua murid dapat mengakses semua konten
- Admin dapat men-tag Cerita dan Panduan Praktik dengan `level_target_id`
- Di portal murid, konten yang sesuai level diberi badge khusus dan ditampilkan di urutan atas
- Konten tanpa tag level (null) tampil untuk semua murid tanpa badge khusus

**Tagging Rekomendasi Default:**

| Level | Surat Al-Qur'an (tag rekomendasi) | Panduan Praktik |
|---|---|---|
| Pra-Iqra | Al-Fatihah, An-Nas, Al-Falaq, Al-Ikhlas | Cara Berwudhu (Pengenalan) |
| Iqra 1–2 | Surat Pendek Juz 30 | Cara Wudhu Lengkap |
| Iqra 3–4 | Juz 30 + Juz 29 | Cara Sholat Fardhu |
| Iqra 5–6 | Juz 30, 29, 28 | Sholat Fardhu (lanjutan) |
| Al-Qur'an | Juz Amma + pilihan | Semua panduan |

---

## 9. Non-Functional Requirements

### 9.1 Performa

| Requirement | Target | Catatan |
|---|---|---|
| First Contentful Paint (portal murid) | < 3 detik pada 3G | Minimal JS, Blade render |
| Time to Interactive | < 4 detik pada 3G | Alpine.js ringan |
| JS Bundle size (portal murid) | < 200KB | Tidak pakai SPA framework |
| Gambar upload (max) | 2MB input → compress ke ≤500KB | Gunakan Intervention Image |
| Gambar panduan (per langkah) | ≤ 300KB | Compress saat upload |
| Query database penilaian | < 100ms | Index pada user_id + tanggal |
| Export PDF laporan | < 5 detik | Dompdf untuk laporan sederhana |
| Seeding Al-Qur'an (satu kali) | < 2 menit | Batch insert |

### 9.2 Keamanan

| Area | Implementasi |
|---|---|
| Password | bcrypt (cost factor 12, default Laravel) |
| Session | HttpOnly cookie, SameSite=Strict |
| CSRF | Laravel CSRF token pada semua POST/PUT/DELETE |
| XSS | Sanitasi output TipTap dengan HTMLPurifier sebelum simpan ke DB |
| Upload file | Validasi: mime type (image/jpeg, image/png, image/webp), max size, store di non-public path |
| SQL Injection | Eloquent ORM + prepared statements (tidak ada raw query tanpa binding) |
| Role Guard | Middleware role check setiap route group |
| Akses antar murid | Query selalu di-scope ke user_id yang sedang login |
| Direktori upload | Simpan di `storage/app/private/`, serve via signed URL atau controller |

### 9.3 Skalabilitas

| Aspek | Desain Saat Ini | Path to Scale |
|---|---|---|
| Multi-cabang | Tidak ada `branch_id` di MVP | Tambah kolom `branch_id` ke users, absensis, pengumumans, dst. Relatif mudah karena schema terpisah |
| Volume murid | Desain untuk ~20–200 murid | Index yang tepat, pagination wajib di semua daftar |
| Storage | Local disk (images) | Migrasi ke S3/Cloudflare R2 via Laravel Filesystem (tinggal ganti disk config) |
| Database | Single MySQL | Read replica jika perlu, tapi tidak perlu di MVP |

### 9.4 Usability & Aksesibilitas

| Requirement | Detail |
|---|---|
| Mobile-first | Target: HP 360px–428px lebar, Android 7+ |
| Font Arab | Google Fonts: Amiri atau Scheherazade New. Ukuran min 22px untuk teks Arab |
| Kontras warna | WCAG AA minimum (kontras 4.5:1 untuk teks normal) |
| Touch target | Minimum 44x44px untuk tombol/link |
| Loading indicator | Spinner/skeleton pada semua aksi async |
| Error states | Pesan error yang jelas dan spesifik (bukan hanya "Terjadi kesalahan") |
| Empty states | Tampilan khusus jika data kosong (bukan tabel kosong yang membingungkan) |
| Tema warna | Hijau Islami: primary `#1B5E20` / `#2E7D32`, accent `#FFC107` |

### 9.5 Reliability

| Requirement | Detail |
|---|---|
| Backup database | Backup harian otomatis (konfigurasi di server/cron) |
| Soft delete | Murid dan Admin tidak dihapus permanen — hanya nonaktif |
| Audit trail | `user_level_histories` mencatat siapa yang ubah level kapan |
| Validasi server-side | SEMUA input divalidasi di server (tidak hanya di frontend) |

---

## 10. MVP Scope vs v2

### MVP — Phase 1 (Wajib Ada Sebelum Launch)

**Auth & User Management**
- [x] Login / Logout semua role
- [x] Reset password oleh admin yang lebih tinggi
- [x] Superadmin: CRUD Admin
- [x] Admin: CRUD Murid
- [x] Admin: Assign & naik/turun level murid

**Operasional Harian**
- [x] Admin: Input absensi per sesi
- [x] Admin: Riwayat & rekap absensi
- [x] Admin: Input penilaian 4 domain (Baca, Hafalan, Tulis, Praktik)
- [x] Admin: Riwayat penilaian per murid

**Konten Belajar**
- [x] Al-Qur'an: 114 surat + ayat (offline)
- [x] Doa-doa: ~60 doa terpilih (offline)
- [x] Hadist: ~80 hadist terpilih (offline)
- [x] Cerita Kisah: CRUD admin + tampil murid
- [x] Panduan Praktik: CRUD admin + tampil murid (teks + gambar)

**Komunikasi**
- [x] Admin: Buat & kirim pengumuman (per level / semua murid)
- [x] Murid: Lihat pengumuman aktif

**Laporan**
- [x] Laporan per murid: PDF + Excel
- [x] Rekap absensi keseluruhan: Excel

**Portal Murid**
- [x] Dashboard dengan ringkasan nilai, absensi, level, pengumuman
- [x] Akses semua konten belajar
- [x] Halaman nilai & progress (4 domain)
- [x] Kalender absensi

**PWA**
- [x] Installable (Add to Home Screen)
- [x] Service Worker: offline capability untuk konten statis
- [x] Offline fallback page
- [x] Update banner saat ada SW baru

**Sistem**
- [x] Superadmin: pengaturan nama & logo TPQ
- [x] Superadmin: konfigurasi sesi TPQ (pagi/sore/malam)

---

### v2 — Phase 2 (Post-Launch, Prioritas Berdasarkan Feedback)

**High Priority:**
- [ ] Video tutorial panduan praktik (ketika TPQ sudah ada WiFi/internet stabil)
- [ ] Push Notification: pengumuman, jadwal ujian hafalan, libur TPQ
- [ ] Grafik progress murid (chart perkembangan nilai dari waktu ke waktu)
- [ ] Jadwal Sesi TPQ (kalender dengan jadwal tetap)

**Medium Priority:**
- [ ] Multi-cabang: branch management, admin per cabang
- [ ] Sub-role Admin: pembatasan akses per divisi (Ketua, Bendahara, Ustadz)
- [ ] Ekspor kartu absensi fisik (format siap cetak, ditempel di papan)
- [ ] Fitur Tanya-Jawab: murid bisa kirim pertanyaan ke pengurus

**Low Priority / Eksperimental:**
- [ ] Gamifikasi: badge hafalan, streak kehadiran
- [ ] Audio tilawah per ayat (jika bandwidth tersedia)
- [ ] Murid bisa input self-check hafalan (dikonfirmasi ustadz)
- [ ] Integrasi WhatsApp: notifikasi otomatis ke orang tua
- [ ] Mobile App native (React Native) jika PWA dirasa kurang

---

## 11. Risiko & Catatan Teknis

| # | Risiko | Level | Dampak | Mitigasi |
|---|---|:---:|---|---|
| 1 | **Font Arab tidak render di device murid** | 🔴 Tinggi | Teks Al-Qur'an tidak terbaca | Load font Amiri dari Google Fonts. Test di device Android low-end. Sertakan font sebagai static asset di PWA precache. |
| 2 | **XSS via TipTap rich text output** | 🔴 Tinggi | Konten berbahaya bisa dieksekusi di browser murid | Sanitasi HTML di server menggunakan `mews/purify` atau `ezyang/htmlpurifier` sebelum simpan ke DB. Whitelist tag yang diizinkan. |
| 3 | **Service Worker update tidak terdeteksi murid** | 🟡 Sedang | Murid memakai versi lama aplikasi indefinitely | Implementasi update banner yang jelas. Gunakan `skipWaiting()` + `clients.claim()`. Cache versioning. |
| 4 | **Ukuran cache Service Worker terlalu besar** | 🟡 Sedang | Storage HP murid penuh, SW bisa dievict browser | Gunakan lazy caching (on-demand, bukan precache semua). Set TTL dan bersihkan cache lama di SW activate. |
| 5 | **Checklist komponen praktik tidak konsisten antar penilaian** | 🟡 Sedang | Admin input komponen berbeda untuk praktik yang sama, data tidak bisa dibandingkan | Buat konfigurasi komponen default per jenis praktik yang di-load otomatis saat admin pilih jenis praktik. Admin bisa tambahkan custom checklist item di atasnya. |
| 6 | **Collision data absensi (input ganda)** | 🟡 Sedang | Dua admin input absensi sesi yang sama untuk murid yang sama | Database unique constraint pada `(user_id, tanggal, sesi)`. Jika sudah ada, sistem menampilkan form edit (bukan insert baru). |
| 7 | **Client minta penambahan fitur di tengah development** | 🟡 Sedang | Scope creep, delay delivery | Sepakati scope MVP ini secara tertulis. Fitur tambahan masuk antrian v2. |
| 8 | **Gambar panduan praktik memperlambat loading** | 🟡 Sedang | Panduan lambat dibuka, terutama tanpa WiFi | Compress agresif saat upload (max 300KB per gambar). Gunakan `loading="lazy"` di img tag. Cache gambar via Service Worker setelah pertama load. |
| 9 | **Semua admin bisa akses semua data (satu role)** | 🟢 Rendah | Admin yang tidak berwenang bisa edit data domain lain | Diterima sebagai trade-off untuk simplicity di MVP. Mitigasi: audit trail di user_level_histories. Sub-role masuk v2. |
| 10 | **Hosting tidak support MySQL InnoDB atau storage besar** | 🟢 Rendah | Tabel ayats (~6K rows) bisa lambat jika MyISAM | Pastikan hosting menggunakan InnoDB. Tambahkan index pada kolom yang sering di-query. |

---

## 12. Appendix: Checklist Pre-Development

Sebelum mulai development, pastikan semua item ini sudah terkonfirmasi:

### Konfirmasi dari Client

- [ ] **Nama resmi TPQ** (untuk header aplikasi, PWA manifest, laporan)
- [ ] **Logo TPQ** dalam format PNG/SVG (min 512x512px)
- [ ] **Daftar sesi** yang digunakan (konfirmasi: Pagi / Sore / Malam, atau nama berbeda?)
- [ ] **Checklist komponen** Wudhu, Sholat Fardhu, Sholat Sunnah, Tayamum — dikonfirmasi dengan ustadz/ustadzah (karena ini akan di-hardcode sebagai default)
- [ ] **Daftar doa** yang wajib ada di aplikasi (atau delegasikan kurasi ke developer)
- [ ] **Apakah ada surat wajib hafal per level?** (untuk tagging rekomendasi)
- [ ] **Domain aplikasi** sudah ada atau akan dibuat (misal: tpq-namamasjid.my.id)

### Konfirmasi Infrastruktur

- [ ] **Hosting / VPS** sudah disiapkan (min PHP 8.2, MySQL 8.x, 1GB RAM)
- [ ] **SSL Certificate** aktif (HTTPS wajib untuk PWA + Service Worker)
- [ ] **Domain** sudah aktif dan pointing ke server
- [ ] **Kapasitas storage** hosting cukup (estimasi: 10MB DB + gambar upload murid/konten)

### Setup Development

- [ ] Repository Git sudah dibuat (GitHub/GitLab)
- [ ] Environment `.env` sudah dikonfigurasi (DB, APP_KEY, APP_URL)
- [ ] Dataset Al-Qur'an JSON sudah diunduh (`quran-json` dari GitHub)
- [ ] Dataset Doa sudah dikurasi dan siap dalam format JSON
- [ ] Dataset Hadist sudah dikurasi dan siap dalam format JSON
- [ ] Google Fonts Amiri sudah di-include di layout murid

### Pre-Launch Testing

- [ ] Test login semua 3 role
- [ ] Test input absensi dan validasi duplicate
- [ ] Test penilaian 4 domain
- [ ] Test naik/turun level dengan history
- [ ] Test export PDF dan Excel
- [ ] Test PWA install di HP Android (Add to Home Screen)
- [ ] Test offline: buka surat Al-Qur'an saat internet mati
- [ ] Test offline: buka doa/hadist saat internet mati
- [ ] Test di device low-end (Android 7, RAM 2GB)
- [ ] Test font Arab render dengan benar

---

*Dokumen ini dibuat berdasarkan hasil diskusi discovery session dan merepresentasikan scope yang telah disepakati. Perubahan scope setelah dokumen ini disetujui harus melalui proses change request.*

---

**Versi:** 1.0 | **Status:** Draft Final | **Dibuat:** Juni 2025
