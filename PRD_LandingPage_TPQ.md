# PRD: Landing Page TPQ (Taman Pendidikan Al-Qur'an)

**Versi:** 1.0
**Tanggal:** 20 Juni 2025
**Status:** Draft Final
**Platform:** Web (Public-facing, bagian dari Laravel project LMS)

---

## Daftar Isi

1. [Project Overview](#1-project-overview)
2. [Sitemap & Struktur Halaman](#2-sitemap--struktur-halaman)
3. [Feature Specifications — Halaman Publik](#3-feature-specifications--halaman-publik)
4. [Feature Specifications — Admin Panel (CMS Landing Page)](#4-feature-specifications--admin-panel-cms-landing-page)
5. [Alur Pendaftaran Murid Baru (PSB)](#5-alur-pendaftaran-murid-baru-psb)
6. [Technical Architecture](#6-technical-architecture)
7. [Database Schema](#7-database-schema)
8. [SEO Strategy](#8-seo-strategy)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [MVP Scope vs v2](#10-mvp-scope-vs-v2)
11. [Risiko & Catatan Teknis](#11-risiko--catatan-teknis)
12. [Appendix: Checklist Pre-Development](#12-appendix-checklist-pre-development)

---

## 1. Project Overview

### 1.1 Latar Belakang

TPQ saat ini belum memiliki kehadiran digital publik. Calon orang tua/wali murid yang ingin mencari informasi atau mendaftarkan anaknya tidak punya cara mudah untuk mengenal TPQ secara online. Landing page ini akan menjadi **pintu depan digital** TPQ — sekaligus berfungsi sebagai alat branding dan kanal pendaftaran murid baru (PSB).

Landing page ini dibangun **dalam satu Laravel project yang sama dengan Aplikasi LMS** yang telah dirancang sebelumnya, sehingga lebih hemat biaya hosting/maintenance untuk developer tunggal, dan tetap memungkinkan jalur masuk yang jelas menuju Portal Murid maupun Admin Panel LMS.

### 1.2 Tujuan

- Memperkenalkan profil, program, dan kegiatan TPQ ke publik
- Membuka kanal pendaftaran murid baru secara online (form PSB)
- Menyediakan kanal kontak cepat: WhatsApp dan telepon langsung
- Menjadi pintu masuk (entry point) menuju Portal Murid/Admin LMS via tombol Login
- Membantu SEO lokal agar TPQ mudah ditemukan saat dicari di internet

### 1.3 Target Audiens

| Audiens | Kebutuhan Utama |
|---|---|
| Calon Orang Tua/Wali Murid | Info program, lokasi, cara daftar, kontak |
| Orang Tua Murid Aktif | Akses cepat ke Login Murid (LMS) |
| Masyarakat Umum/Donatur | Mengenal profil & kegiatan TPQ |

### 1.4 Keputusan Strategis (Hasil Diskusi)

| Keputusan | Hasil |
|---|---|
| Tujuan utama | Branding + Pendaftaran online (form) + WhatsApp + Telepon |
| Arsitektur | Satu project Laravel dengan LMS (bukan project terpisah) |
| Alur data PSB | Masuk ke tabel `pendaftars` terpisah → direview admin → di-approve jadi akun Murid (hybrid, lihat Bab 5) |
| Konten tersedia | Foto kegiatan, profil pengurus, testimoni — siap dipakai |
| Kontak | WhatsApp, Telepon (click-to-call), dan form online |

---

## 2. Sitemap & Struktur Halaman

### 2.1 Pendekatan: Single Page + 1 Halaman Form Terpisah

Landing page didesain sebagai **single-page scrollable** dengan section-section utama (lazim untuk landing page UMKM/lembaga kecil — lebih cepat dibangun, SEO tetap baik untuk 1 halaman utama). Form pendaftaran dipisah ke halaman sendiri agar bisa di-share linknya langsung (misal di bio Instagram/status WhatsApp TPQ).

```
/                         → Landing Page (single page, scroll ke section via anchor link)
    ├── #beranda            (Hero)
    ├── #tentang-kami       (Profil & Visi Misi)
    ├── #program            (Kurikulum/Program Belajar)
    ├── #keunggulan         (Value Proposition)
    ├── #galeri             (Galeri Kegiatan)
    ├── #testimoni          (Testimoni Orang Tua/Murid)
    ├── #pengurus           (Struktur Pengurus)
    └── #kontak             (Lokasi, Jam Operasional, Kontak)

/daftar                   → Form Pendaftaran Murid Baru (PSB)
/daftar/terima-kasih      → Halaman konfirmasi setelah submit form

/login                    → Redirect ke halaman login LMS (sudah ada di PRD LMS)
```

### 2.2 Navigasi (Navbar)

- Logo + Nama TPQ (kiri)
- Menu: Beranda | Tentang | Program | Galeri | Testimoni | Kontak (anchor link, smooth scroll)
- Tombol CTA kanan: **"Daftar Sekarang"** (primer, warna mencolok) + **"Login"** (sekunder, mengarah ke LMS)
- Mobile: hamburger menu, sticky navbar saat scroll

---

## 3. Feature Specifications — Halaman Publik

### 3.1 Hero Section (`#beranda`)

**Tujuan:** Kesan pertama — jelas dalam 3 detik tentang siapa TPQ ini dan apa yang harus dilakukan pengunjung.

**Konten:**
| Elemen | Sumber | Keterangan |
|---|---|---|
| Background Image/Video | Upload CMS | Foto kegiatan TPQ yang representatif |
| Headline | `landing_settings.hero_headline` | Contoh: "Membentuk Generasi Qur'ani Sejak Dini" |
| Sub-headline | `landing_settings.hero_subheadline` | 1-2 kalimat pendukung |
| Statistik singkat (opsional) | Auto-count dari DB | "20+ Murid Aktif", "Kurikulum 4 Domain" |
| CTA Primer | Tombol | "Daftar Sekarang" → `/daftar` |
| CTA Sekunder | Tombol | "Hubungi via WhatsApp" → `wa.me/{no_wa}` |

---

### 3.2 Tentang Kami (`#tentang-kami`)

**Konten:**
- Foto profil TPQ (gedung/kegiatan)
- Teks "Tentang Kami" — dikelola via CMS (`landing_settings.tentang_kami`)
- **Visi:** 1 paragraf, dikelola via CMS
- **Misi:** list poin-poin, dikelola via CMS (disimpan sebagai JSON array)

---

### 3.3 Program / Kurikulum (`#program`)

**Tujuan:** Menjelaskan metode belajar — diambil langsung dari hasil diskusi LMS (4 domain pembelajaran).

**Tampilan:** 4 kartu (cards) dengan ikon

| Kartu | Ikon | Judul | Deskripsi Singkat |
|---|---|---|---|
| 1 | 📖 | Baca & Tulis | Iqra, Al-Qur'an, dan Hadist — dibimbing bertahap sesuai level |
| 2 | 🧠 | Hafalan | Surat-surat pendek, hadist, dan doa sehari-hari |
| 3 | 🤲 | Praktik Ibadah | Wudhu, sholat, dan fiqih dasar dipraktikkan langsung |
| 4 | 📚 | Halaqah & Kisah | Cerita kisah Nabi dan ceramah ringan yang menyenangkan |

Deskripsi tiap kartu dikelola via CMS sehingga admin bisa mengedit teks tanpa bantuan developer.

---

### 3.4 Keunggulan (`#keunggulan`)

**Tampilan:** 3–4 poin value proposition dengan ikon, contoh:

- ✅ Kurikulum terstruktur per jenjang (Pra-Iqra s.d. Al-Qur'an)
- ✅ Laporan perkembangan anak bisa dipantau online oleh orang tua
- ✅ Pengajar berpengalaman dan sabar membimbing anak
- ✅ Lingkungan belajar yang Islami dan menyenangkan

Konten dikelola via CMS (repeatable field, bisa tambah/kurang poin).

---

### 3.5 Galeri Kegiatan (`#galeri`)

**Tampilan:**
- Grid foto (masonry atau grid rapi), menampilkan maksimal **8–12 foto** terbaru/terpilih di homepage
- Klik foto → lightbox (perbesar gambar, navigasi next/prev)
- Lazy loading wajib (foto baru dimuat saat di-scroll ke area itu)

**Sumber data:** tabel `galleries`, diurutkan berdasarkan `urutan`, hanya yang `is_active = true`

---

### 3.6 Testimoni (`#testimoni`)

**Tampilan:** Carousel atau grid card

Setiap card testimoni menampilkan:
- Foto (opsional, fallback ke avatar inisial jika tidak ada)
- Nama
- Role (contoh: "Orang Tua dari Ahmad", "Wali Murid")
- Isi testimoni (kutipan singkat)
- Rating bintang (opsional, 1–5)

**Sumber data:** tabel `testimonis`, diurutkan `urutan`, hanya `is_active = true`

---

### 3.7 Struktur Pengurus (`#pengurus`)

**Tampilan:** Grid foto profil pengurus

Setiap card:
- Foto
- Nama
- Jabatan (contoh: "Ketua TPQ", "Ustadzah Tahfidz", "Bendahara")

**Catatan teknis:** Data ini **terpisah dari tabel `admins`** (akun login sistem). Tabel khusus `pengurus_profiles` dibuat agar pengurus bebas menentukan siapa saja dan jabatan apa yang ingin ditampilkan ke publik, tanpa terikat ke struktur akun/role teknis di LMS.

---

### 3.8 Lokasi & Kontak (`#kontak`)

**Konten:**
| Elemen | Sumber |
|---|---|
| Alamat lengkap | `landing_settings.alamat` |
| Peta lokasi | Embed Google Maps (iframe dari `landing_settings.maps_embed_url`) |
| Jam Operasional | `landing_settings.jam_operasional` |
| Tombol WhatsApp | `wa.me/{landing_settings.no_wa}` dengan pesan default ter-prefill |
| Tombol Telepon | `tel:{landing_settings.no_telpon}` — klik langsung membuka dialer di HP |
| Email (opsional) | `landing_settings.email` |
| Sosial Media (opsional) | Instagram, Facebook — ikon dengan link |

---

### 3.9 Footer

**Konten:**
- Logo + nama TPQ (ringkas)
- Quick links (anchor ke section)
- Kontak ringkas (alamat, WA, telpon)
- Tombol **"Login Murid / Pengurus"** → `/login` (masuk ke LMS)
- Copyright: "© [Tahun] [Nama TPQ]. All rights reserved."

---

### 3.10 Form Pendaftaran Murid Baru (`/daftar`)

**Field Form:**

| Field | Tipe | Wajib | Validasi |
|---|---|:---:|---|
| Nama Lengkap Calon Murid | Text | ✅ | Max 100 char |
| Tempat Lahir | Text | ❌ | |
| Tanggal Lahir | Date | ✅ | |
| Jenis Kelamin | Radio (L/P) | ✅ | |
| Nama Orang Tua/Wali | Text | ✅ | Max 100 char |
| No. WhatsApp Aktif | Text | ✅ | Format nomor HP Indonesia |
| Alamat | Textarea | ✅ | |
| Pernah Belajar Mengaji Sebelumnya? | Radio (Sudah/Belum) | ✅ | |
| Jika sudah, sampai level apa | Text | ❌ | Muncul kondisional jika "Sudah" dipilih |
| Catatan Tambahan | Textarea | ❌ | Opsional, pertanyaan/permintaan khusus |

**Anti-Spam:**
- Honeypot field tersembunyi (field jebakan untuk bot)
- Google reCAPTCHA v3 (invisible, tidak mengganggu UX pengisian form)

**Setelah Submit:**
- Redirect ke `/daftar/terima-kasih` dengan pesan:
  > "Terima kasih, [Nama Calon Murid]! Pendaftaran Anda sudah kami terima. Tim kami akan menghubungi Anda melalui WhatsApp dalam 1x24 jam."
- Tombol tambahan: "Hubungi Kami Sekarang via WhatsApp" (untuk yang ingin follow-up cepat, tidak menunggu dihubungi)

**Validasi Duplikat (Soft Check):**
- Jika ada submission dengan kombinasi nama + no. WA yang sama dalam 24 jam terakhir, tampilkan peringatan: *"Sepertinya Anda baru saja mendaftar. Tim kami akan segera menghubungi Anda."* — tetap mengizinkan submit (tidak memblokir keras, untuk menghindari false-positive).

---

## 4. Feature Specifications — Admin Panel (CMS Landing Page)

Menu baru di sidebar Admin Panel LMS: **"Landing Page"** (submenu, hanya muncul untuk role Admin & Superadmin).

```
Landing Page
  ├── Pendaftaran Murid Baru (PSB)
  ├── Galeri
  ├── Testimoni
  ├── Struktur Pengurus
  └── Pengaturan Landing Page
```

### 4.1 Pendaftaran Murid Baru (PSB) — Lihat Bab 5 untuk alur lengkap

### 4.2 Kelola Galeri

**Daftar Galeri:**
- Grid thumbnail dengan judul (opsional), kategori, status aktif/nonaktif, urutan
- Drag-and-drop reorder (atau tombol naik/turun sederhana untuk MVP)

**Form Tambah/Edit Foto:**
| Field | Tipe | Wajib |
|---|---|:---:|
| Foto | Upload | ✅ |
| Judul/Caption | Text | ❌ |
| Kategori | Text/Select | ❌ |
| Status | Toggle | ✅ |

- Validasi upload: max 2MB, auto-compress & resize ke max 1200px lebar
- Hapus foto (dengan konfirmasi)

### 4.3 Kelola Testimoni

**Form Tambah/Edit Testimoni:**
| Field | Tipe | Wajib |
|---|---|:---:|
| Nama | Text | ✅ |
| Role/Keterangan | Text | ✅ | Contoh: "Orang Tua Murid" |
| Foto | Upload | ❌ |
| Isi Testimoni | Textarea | ✅ | Max 500 karakter |
| Rating | Select (1–5) | ❌ |
| Status | Toggle | ✅ |
| Urutan | Number | ❌ |

### 4.4 Struktur Pengurus (Tampilan Publik)

**Form Tambah/Edit:**
| Field | Tipe | Wajib |
|---|---|:---:|
| Nama | Text | ✅ |
| Jabatan | Text | ✅ | Bebas diisi (tidak terikat role teknis) |
| Foto | Upload | ❌ |
| Status | Toggle | ✅ |
| Urutan | Number | ❌ |

### 4.5 Pengaturan Landing Page

Form tunggal dengan seluruh konten yang bisa diedit tanpa developer:

| Field | Tipe | Keterangan |
|---|---|---|
| Headline Hero | Text | |
| Sub-headline Hero | Text | |
| Background Hero | Upload Gambar | |
| Teks Tentang Kami | Textarea | |
| Visi | Textarea | |
| Misi | Repeatable text field | Bisa tambah/kurang poin |
| Poin Keunggulan | Repeatable text field | Bisa tambah/kurang poin |
| Alamat Lengkap | Textarea | |
| Link Google Maps Embed | Text/URL | Admin tinggal paste embed URL dari Google Maps |
| Jam Operasional | Textarea | Bebas format, contoh: "Senin–Jumat: 16.00–17.30 WIB" |
| No. WhatsApp | Text | Format: 628xxxxxxxxxx |
| No. Telepon | Text | |
| Email | Text | Opsional |
| Link Instagram | URL | Opsional |
| Link Facebook | URL | Opsional |

**Catatan:** Nama TPQ dan Logo **tidak diulang di sini** — sudah dikelola lewat menu "Pengaturan Sistem" milik Superadmin di LMS (reuse, tidak duplikasi data).

---

## 5. Alur Pendaftaran Murid Baru (PSB)

### 5.1 Diagram Alur

```
[Calon Ortu isi form di /daftar]
            │
            ▼
   Data masuk ke tabel `pendaftars`
   Status awal: "Baru"
            │
            ▼
[Admin buka menu "Pendaftaran Murid Baru"]
            │
            ├── Lihat daftar submission (filter by status)
            │
            ├── Buka detail submission
            │     └── Tambahkan catatan internal (misal: "Sudah dihubungi 21 Juni")
            │     └── Ubah status: Baru → Dihubungi
            │
            ├── Jika calon murid jadi mendaftar:
            │     └── Klik tombol "Terima & Buat Akun Murid"
            │           │
            │           ▼
            │     Form "Tambah Murid" terbuka dengan data PRE-FILLED
            │     dari submission (nama, tanggal lahir, kontak ortu, dll)
            │           │
            │           ▼
            │     Admin lengkapi: Level Awal, Username, Password
            │           │
            │           ▼
            │     Submit → Akun Murid baru tercipta di tabel `users`
            │     Status pendaftar otomatis → "Diterima"
            │     `pendaftars.user_id` terisi (relasi ke akun murid baru)
            │
            └── Jika calon murid batal/tidak lanjut:
                  └── Ubah status → "Ditolak" (dengan catatan alasan opsional)
```

### 5.2 Daftar Pendaftar (Admin View)

**Tabel:**
| Kolom | Keterangan |
|---|---|
| Tanggal Daftar | |
| Nama Calon Murid | |
| Nama Orang Tua | |
| No. WhatsApp | Klik untuk langsung chat WA |
| Status | Badge warna: Baru (biru), Dihubungi (kuning), Diterima (hijau), Ditolak (abu) |
| Aksi | Detail, Ubah Status |

**Filter:** Status, Rentang Tanggal
**Search:** Nama calon murid / nama orang tua

### 5.3 Halaman Detail Pendaftar

- Semua data dari form pendaftaran
- Riwayat perubahan status (timestamp + siapa yang ubah)
- Field catatan internal (textarea, bisa diupdate kapan saja)
- Tombol aksi:
  - "Tandai Sudah Dihubungi"
  - "Terima & Buat Akun Murid" (hanya muncul jika status belum "Diterima")
  - "Tolak Pendaftaran" (dengan konfirmasi + alasan opsional)

---

## 6. Technical Architecture

### 6.1 Prinsip Arsitektur

Landing page **tidak menggunakan framework atau project terpisah** — seluruhnya berjalan di atas Laravel 11 project yang sama dengan LMS, hanya menambahkan:
- Route group publik (tanpa middleware auth)
- Layout Blade baru khusus untuk halaman publik (`layouts/public.blade.php`) dengan desain yang lebih "marketing-feel" dibanding layout Admin/Murid
- Beberapa model & migration baru
- Controller baru di bawah namespace `Public`

### 6.2 Tambahan Tech Stack

| Kebutuhan | Teknologi |
|---|---|
| Anti-spam form | Google reCAPTCHA v3 + Honeypot field |
| Image compression | Intervention Image (reuse dari LMS) |
| Maps | Google Maps Embed (iframe biasa, tanpa API key — gratis, cukup untuk MVP) |
| SEO Meta Tags | Blade component custom `<x-seo-meta>` |
| Smooth Scroll | Alpine.js / CSS `scroll-behavior: smooth` |
| Lightbox Galeri | Library ringan (misal `glightbox` via CDN) |

### 6.3 Struktur Folder Tambahan

```
app/
├── Http/
│   └── Controllers/
│       └── Public/
│           ├── LandingController.php
│           └── PendaftaranController.php
│
├── Models/
│   ├── Pendaftar.php
│   ├── Galeri.php
│   ├── Testimoni.php
│   ├── PengurusProfile.php
│   └── LandingSetting.php

resources/
├── views/
│   ├── layouts/
│   │   └── public.blade.php
│   └── public/
│       ├── landing.blade.php
│       ├── partials/
│       │   ├── hero.blade.php
│       │   ├── tentang-kami.blade.php
│       │   ├── program.blade.php
│       │   ├── keunggulan.blade.php
│       │   ├── galeri.blade.php
│       │   ├── testimoni.blade.php
│       │   ├── pengurus.blade.php
│       │   ├── kontak.blade.php
│       │   └── footer.blade.php
│       ├── daftar.blade.php
│       └── terima-kasih.blade.php
│   └── admin/
│       └── landing/
│           ├── pendaftaran/
│           ├── galeri/
│           ├── testimoni/
│           ├── pengurus/
│           └── pengaturan.blade.php

database/
└── migrations/
    ├── xxxx_create_pendaftars_table.php
    ├── xxxx_create_galleries_table.php
    ├── xxxx_create_testimonis_table.php
    ├── xxxx_create_pengurus_profiles_table.php
    └── xxxx_create_landing_settings_table.php
```

### 6.4 Route Structure

```php
// routes/web.php — Public routes (no auth middleware)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/daftar', [PendaftaranController::class, 'create'])->name('daftar.create');
Route::post('/daftar', [PendaftaranController::class, 'store'])->name('daftar.store');
Route::get('/daftar/terima-kasih', [PendaftaranController::class, 'thankyou'])->name('daftar.thankyou');

// routes/admin.php — Tambahan di dalam group middleware admin yang sudah ada
Route::prefix('landing')->group(function () {
    Route::get('/pendaftaran', [Admin\PendaftaranController::class, 'index']);
    Route::get('/pendaftaran/{pendaftar}', [Admin\PendaftaranController::class, 'show']);
    Route::patch('/pendaftaran/{pendaftar}/status', [Admin\PendaftaranController::class, 'updateStatus']);
    Route::get('/pendaftaran/{pendaftar}/terima', [Admin\PendaftaranController::class, 'terimaForm']);
    // ↑ redirect ke form Tambah Murid dengan query params prefill

    Route::resource('/galeri', Admin\GaleriController::class)->except(['show']);
    Route::resource('/testimoni', Admin\TestimoniController::class)->except(['show']);
    Route::resource('/pengurus', Admin\PengurusProfileController::class)->except(['show']);

    Route::get('/pengaturan', [Admin\LandingSettingController::class, 'edit']);
    Route::put('/pengaturan', [Admin\LandingSettingController::class, 'update']);
});
```

### 6.5 Integrasi Prefill ke Form Tambah Murid

Saat admin klik "Terima & Buat Akun Murid" dari halaman detail pendaftar:

```php
// Admin\PendaftaranController@terimaForm
public function terimaForm(Pendaftar $pendaftar)
{
    return redirect()->route('admin.murid.create', [
        'prefill_nama'       => $pendaftar->nama_lengkap,
        'prefill_tempat_lahir' => $pendaftar->tempat_lahir,
        'prefill_tanggal_lahir' => $pendaftar->tanggal_lahir,
        'prefill_jenis_kelamin' => $pendaftar->jenis_kelamin,
        'prefill_nama_ortu'  => $pendaftar->nama_orang_tua,
        'prefill_no_hp_ortu' => $pendaftar->no_wa,
        'prefill_alamat'     => $pendaftar->alamat,
        'pendaftar_id'       => $pendaftar->id, // dipakai untuk update relasi setelah murid dibuat
    ]);
}
```

Form Tambah Murid di Admin Panel LMS membaca query params ini untuk mengisi field secara otomatis. Setelah murid berhasil disimpan, sistem mengupdate `pendaftars.user_id` dan `pendaftars.status = 'diterima'`.

---

## 7. Database Schema

### 7.1 Tabel `pendaftars`

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| nama_lengkap | VARCHAR(100) | NOT NULL | |
| tempat_lahir | VARCHAR(100) | NULLABLE | |
| tanggal_lahir | DATE | NOT NULL | |
| jenis_kelamin | ENUM('L','P') | NOT NULL | |
| nama_orang_tua | VARCHAR(100) | NOT NULL | |
| no_wa | VARCHAR(20) | NOT NULL | |
| alamat | TEXT | NOT NULL | |
| pernah_mengaji | BOOLEAN | NOT NULL, DEFAULT false | |
| level_mengaji_sebelumnya | VARCHAR(100) | NULLABLE | |
| catatan_tambahan | TEXT | NULLABLE | Dari calon ortu |
| status | ENUM('baru','dihubungi','diterima','ditolak') | NOT NULL, DEFAULT 'baru' | |
| catatan_internal | TEXT | NULLABLE | Catatan admin |
| user_id | BIGINT UNSIGNED | FK → users.id, NULLABLE | Terisi setelah diterima |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

### 7.2 Tabel `galleries`

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| judul | VARCHAR(150) | NULLABLE | |
| gambar | VARCHAR(255) | NOT NULL | Path storage |
| kategori | VARCHAR(100) | NULLABLE | |
| urutan | SMALLINT | NULLABLE | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

### 7.3 Tabel `testimonis`

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| nama | VARCHAR(100) | NOT NULL | |
| role | VARCHAR(100) | NOT NULL | Contoh: "Orang Tua Murid" |
| foto | VARCHAR(255) | NULLABLE | |
| isi | TEXT | NOT NULL | Max 500 karakter (validasi di form) |
| rating | TINYINT UNSIGNED | NULLABLE | 1–5 |
| urutan | SMALLINT | NULLABLE | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

### 7.4 Tabel `pengurus_profiles`

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| nama | VARCHAR(100) | NOT NULL | |
| jabatan | VARCHAR(100) | NOT NULL | |
| foto | VARCHAR(255) | NULLABLE | |
| urutan | SMALLINT | NULLABLE | |
| is_active | BOOLEAN | NOT NULL, DEFAULT true | |
| created_at | TIMESTAMP | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

### 7.5 Tabel `landing_settings`

Pattern **key-value** — dipilih agar fleksibel menambah field baru di masa depan tanpa perlu migration baru.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | BIGINT UNSIGNED | PK | |
| key | VARCHAR(100) | NOT NULL, UNIQUE | |
| value | TEXT | NULLABLE | |
| updated_at | TIMESTAMP | NULLABLE | |

**Contoh Keys yang Di-seed:**

| Key | Contoh Value |
|---|---|
| `hero_headline` | "Membentuk Generasi Qur'ani Sejak Dini" |
| `hero_subheadline` | "Belajar Al-Qur'an dengan metode terstruktur dan menyenangkan" |
| `tentang_kami` | Paragraf teks |
| `visi` | Paragraf teks |
| `misi` | JSON array: `["Misi 1", "Misi 2", "Misi 3"]` |
| `poin_keunggulan` | JSON array |
| `alamat` | Alamat lengkap |
| `maps_embed_url` | URL iframe Google Maps |
| `jam_operasional` | Teks bebas |
| `no_wa` | "6281234567890" |
| `no_telpon` | "0211234567" |
| `email` | "info@tpq.sch.id" |
| `instagram_url` | URL Instagram |
| `facebook_url` | URL Facebook |

### 7.6 Relasi ke Tabel LMS yang Sudah Ada

```
pendaftars
   │
   └── (nullable) user_id → users.id (tabel Murid di LMS)
```

Tidak ada perubahan pada skema LMS yang sudah dirancang sebelumnya — landing page murni menambah tabel baru tanpa mengubah struktur yang ada.

---

## 8. SEO Strategy

### 8.1 On-Page SEO

| Elemen | Implementasi |
|---|---|
| Title Tag | Dinamis per halaman: "[Nama TPQ] — Pendidikan Al-Qur'an untuk Anak \| [Lokasi]" |
| Meta Description | 150–160 karakter, mengandung kata kunci lokasi + "TPQ"/"Taman Pendidikan Al-Qur'an" |
| Heading Structure | H1 hanya 1× di Hero, H2 untuk tiap section |
| Alt Text Gambar | Wajib diisi untuk semua foto galeri & testimoni |
| URL Friendly | `/daftar` bukan `/form-pendaftaran-12345` |
| Internal Linking | Footer & navbar saling terhubung antar section |

### 8.2 Open Graph & Social Sharing

```html
<meta property="og:title" content="[Nama TPQ] - Taman Pendidikan Al-Qur'an">
<meta property="og:description" content="[Tagline singkat]">
<meta property="og:image" content="[URL gambar hero/logo]">
<meta property="og:type" content="website">
```

Penting agar saat link landing page di-share di WhatsApp/Instagram, preview-nya menarik.

### 8.3 Structured Data (Schema.org)

```json
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "[Nama TPQ]",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "[Alamat]"
  },
  "telephone": "[No Telepon]",
  "openingHours": "[Jam Operasional]"
}
```

Membantu Google menampilkan info TPQ secara lebih kaya (rich snippet) di hasil pencarian.

### 8.4 Technical SEO

- `sitemap.xml` sederhana (statis, hanya berisi `/`, `/daftar`)
- `robots.txt` mengizinkan semua crawler mengakses halaman publik
- Page speed dioptimalkan (lihat Non-Functional Requirements)
- Mobile-friendly (Google mengutamakan mobile-first indexing)

### 8.5 Catatan Non-Teknis

- Disarankan TPQ membuat **Google Business Profile** (gratis) dan mencantumkan link landing page — efeknya signifikan untuk pencarian lokal seperti "TPQ dekat saya"
- SEO organik butuh waktu (estimasi 2–6 bulan untuk mulai terlihat hasil) — perlu dikomunikasikan ke client agar ekspektasi realistis

---

## 9. Non-Functional Requirements

### 9.1 Performa

| Requirement | Target |
|---|---|
| First Contentful Paint | < 2.5 detik (koneksi 4G) |
| Largest Contentful Paint | < 3 detik |
| Ukuran gambar hero | ≤ 300KB (WebP, compressed) |
| Ukuran foto galeri | ≤ 200KB per foto (auto-compress saat upload) |
| Lazy loading | Wajib untuk semua gambar di luar viewport awal |

### 9.2 Responsivitas

- Mobile-first design (mayoritas pengunjung kemungkinan besar browsing dari HP)
- Breakpoint: Mobile (< 640px), Tablet (640–1024px), Desktop (> 1024px)
- Touch target minimum 44×44px untuk semua tombol/link

### 9.3 Keamanan

| Area | Implementasi |
|---|---|
| Form PSB | CSRF protection (built-in Laravel), reCAPTCHA v3, honeypot field |
| Rate limiting | Maksimal 5 submission per IP per jam (mencegah spam) |
| Validasi input | Server-side validation wajib untuk semua field form |
| Upload gambar (CMS) | Validasi mime-type, max size, sanitasi nama file |

### 9.4 Aksesibilitas

- Kontras warna WCAG AA minimum
- Alt text di semua gambar
- Form punya label yang jelas (bukan hanya placeholder)
- Navigasi bisa diakses via keyboard (tab order logis)

---

## 10. MVP Scope vs v2

### MVP — Phase 1

- [x] Hero, Tentang Kami, Program, Keunggulan sections
- [x] Galeri (CRUD admin + tampilan grid + lightbox)
- [x] Testimoni (CRUD admin + tampilan)
- [x] Struktur Pengurus (CRUD admin + tampilan)
- [x] Lokasi & Kontak (Maps embed, WA, Telepon, jam operasional)
- [x] Form Pendaftaran PSB lengkap dengan anti-spam
- [x] Alur approval PSB → prefill ke Tambah Murid LMS
- [x] Pengaturan Landing Page (CMS sederhana di Admin Panel)
- [x] SEO dasar (meta tags, sitemap, OG tags, structured data)
- [x] Responsive design (mobile-first)

### v2 — Phase 2

- [ ] Halaman `/galeri` dan `/program` terpisah penuh (jika konten berkembang banyak)
- [ ] Blog/Artikel (tips parenting Islami, kegiatan terbaru) — bagus untuk SEO jangka panjang
- [ ] Live chat widget di landing page
- [ ] Newsletter/WhatsApp broadcast subscription
- [ ] Multi-bahasa (jika ada kebutuhan ekspansi/cabang berbeda daerah)
- [ ] Integrasi review Google Business Profile ke halaman testimoni
- [ ] A/B testing untuk copy/CTA hero

---

## 11. Risiko & Catatan Teknis

| # | Risiko | Level | Dampak | Mitigasi |
|---|---|:---:|---|---|
| 1 | **Spam submission form PSB** | 🔴 Tinggi | Data pendaftar palsu memenuhi sistem, mengganggu kerja admin | reCAPTCHA v3 + honeypot + rate limiting per IP |
| 2 | **Foto galeri/testimoni ukuran besar memperlambat loading** | 🟡 Sedang | Landing page lambat, kesan pertama buruk, SEO turun | Auto-compress wajib via Intervention Image saat upload (resize + convert WebP) |
| 3 | **SEO butuh waktu lama untuk terlihat hasilnya** | 🟢 Rendah | Ekspektasi client tidak sesuai realita | Komunikasikan dari awal bahwa SEO organik adalah usaha jangka menengah-panjang (2–6 bulan) |
| 4 | **Data pendaftar duplikat (submit berkali-kali)** | 🟢 Rendah | Admin harus filter manual | Soft-check duplikat by nama+no_wa dalam 24 jam, tampilkan warning tapi tidak blocking |
| 5 | **Google Maps embed berubah/link invalid** | 🟢 Rendah | Peta tidak muncul di halaman kontak | Validasi format URL embed saat input di Pengaturan, beri instruksi cara ambil embed link yang benar |
| 6 | **Konten testimoni/galeri kosong saat awal launch** | 🟢 Rendah | Section terlihat kosong, kurang meyakinkan | Pastikan minimal 3–5 testimoni dan 6–8 foto sudah siap sebelum go-live (lihat checklist) |

---

## 12. Appendix: Checklist Pre-Development

### Konten yang Perlu Disiapkan Client

- [ ] Headline & tagline hero yang sudah final
- [ ] Teks "Tentang Kami", Visi, dan 3–5 poin Misi
- [ ] 3–4 poin Keunggulan TPQ
- [ ] Minimal **6–8 foto kegiatan** berkualitas baik (resolusi cukup, tidak blur)
- [ ] Minimal **3–5 testimoni** dari orang tua/murid (kutipan + nama, foto opsional)
- [ ] Foto & jabatan pengurus yang ingin ditampilkan ke publik
- [ ] Alamat lengkap TPQ
- [ ] Link Google Maps embed (cara ambil: buka Google Maps → cari lokasi → Share → Embed a map → copy HTML, ambil URL dari atribut `src`)
- [ ] Jam operasional pendaftaran/kegiatan
- [ ] Nomor WhatsApp resmi yang akan dipakai untuk CTA
- [ ] Nomor telepon (jika berbeda dari WA)
- [ ] Link Instagram/Facebook (jika ada)

### Konfirmasi Teknis

- [ ] Domain sudah aktif (sama dengan domain LMS, karena satu project)
- [ ] Google reCAPTCHA v3 site key & secret key sudah dibuat ([google.com/recaptcha](https://www.google.com/recaptcha))
- [ ] Cek apakah TPQ sudah/akan membuat Google Business Profile (paralel untuk SEO lokal)

### Pre-Launch Testing

- [ ] Test form pendaftaran end-to-end (submit → masuk ke admin panel → approve → jadi akun murid)
- [ ] Test anti-spam (coba submit berkali-kali, pastikan rate limit bekerja)
- [ ] Test tombol WhatsApp & Telepon di HP asli (bukan hanya browser desktop)
- [ ] Test responsive di berbagai ukuran layar (HP kecil, tablet, desktop)
- [ ] Test kecepatan loading (gunakan Google PageSpeed Insights atau Lighthouse)
- [ ] Test Open Graph preview (share link di WhatsApp, cek preview gambar & teks muncul benar)
- [ ] Validasi sitemap.xml dan robots.txt bisa diakses

---

*Dokumen ini melengkapi PRD Aplikasi LMS TPQ yang telah dibuat sebelumnya. Landing page dan LMS berjalan dalam satu project Laravel yang sama, sehingga seluruh tech stack, struktur folder dasar, dan prinsip keamanan mengikuti PRD LMS, dengan tambahan modul-modul yang dijabarkan di dokumen ini.*

---

**Versi:** 1.0 | **Status:** Draft Final | **Dibuat:** Juni 2025
