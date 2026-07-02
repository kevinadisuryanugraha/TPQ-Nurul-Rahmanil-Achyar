# TASK BREAKDOWN & ROADMAP — LMS TPQ Nurul Rahmanil Achyar

Dokumen ini berisi breakdown modul, progress saat ini, dan roadmap tugas yang harus diselesaikan untuk mencapai rilis penuh (produksi).

---

## 1. Modul 1: Autentikasi & Authorization (100% Selesai)

Semua fungsionalitas dasar autentikasi telah diimplementasikan dan diverifikasi oleh unit testing.

- [x] Login page terpadu (username untuk murid, email untuk admin/superadmin)
- [x] Custom LoginController handling dual guard (`admin` & `web`)
- [x] Custom Middleware: `superadmin`, `admin`, `murid`
- [x] Pembatasan rute web dengan middleware yang sesuai
- [x] Reset password admin oleh superadmin
- [x] Reset password murid oleh admin

---

## 2. Modul 2: Landing Page & Pendaftaran PSB (100% Selesai)

Desain visual dan interaksi frontend halaman publik telah ditingkatkan sesuai standar "UI/UX Pro Max".

- [x] **Landing Page** (`/`):
  - [x] Visual Hero premium: hexagonal Islamic geometric SVG + Basmallah eyebrow + rotating ring
  - [x] Layout non-repetitif (4 struktur layout berbeda)
  - [x] 4 pilar program dengan Bento-style card variation
  - [x] Galeri foto interaktif dengan lightbox modal (Alpine.js)
  - [x] Testimoni wali santri & profil pengurus yang dinamis
  - [x] Animasi scroll-reveal (prefers-reduced-motion aware)
- [x] **Form Pendaftaran PSB** (`/daftar`):
  - [x] Visual progress steps (4 tahapan)
  - [x] Form fields dikelompokkan dengan `fieldset/legend` semantic
  - [x] Radio buttons digantikan dengan card-style selector (Alpine.js state)
  - [x] Input validation feedback, input helper, dan focus ring emerald 3px
  - [x] Pencegahan spam via Honeypot + Google reCAPTCHA
  - [x] Loading state pada tombol submit (spinner + disabled state)
- [x] **Terima Kasih Page** (`/daftar/terima-kasih`):
  - [x] Animasi sukses scale-in + pulse check ring
  - [x] 3-langkah orientasi setelah pendaftaran
  - [x] WhatsApp CTA untuk fast-response konfirmasi

---

## 3. Modul 3: Manajemen Sistem & Pengurus (100% Selesai)

- [x] CRUD admin/pengurus oleh superadmin
- [x] Soft delete (aktif/nonaktifkan) status admin
- [x] CMS Landing Page (hero text, alamat, kontak, dll)
- [x] CRUD testimoni, galeri, dan profil pengurus
- [x] Pengaturan logo & nama TPQ oleh superadmin
- [x] Konfigurasi sesi TPQ (pagi/sore/malam) oleh superadmin

---

## 4. Modul 4: Manajemen Murid & Level (100% Selesai)

- [x] CRUD murid/santri oleh admin
- [x] Auto-generate username & password murid dari nama panggilan
- [x] Kenaikan/penurunan level murid (Pra-Iqra hingga Al-Qur'an)
- [x] Audit trail perubahan level (`user_level_histories`)
- [x] Pencarian & filter murid berdasarkan status dan level

---

## 5. Modul 5: Absensi Harian (100% Selesai)

- [x] Input absensi massal per kelas/sesi menggunakan Livewire
- [x] Pencegahan data ganda via composite key `(user_id, tanggal, sesi)`
- [x] Edit absensi hari-hari sebelumnya
- [x] Rekapitulasi absensi bulanan per santri

---

## 6. Modul 6: Penilaian 4 Domain (100% Selesai)

- [x] Domain 1: Penilaian Baca (Iqra / Al-Qur'an / Tilawah, Jilid/Juz, Catatan Tajwid)
- [x] Domain 2: Penilaian Hafalan (Surat Pendek, Hadist, Doa, Status Kelancaran)
- [x] Domain 3: Penilaian Tulis (Materi, Nilai 0-100, Auto-Grade A/B/C/D)
- [x] Domain 4: Penilaian Praktik (Wudhu, Sholat, Tayamum, Checklist Komponen Fikih)

---

## 7. Modul 7: Portal Murid & PWA (100% Selesai)

- [x] Dashboard murid: progress level, ringkasan absensi, nilai terakhir
- [x] Modul Belajar: Al-Qur'an (114 surat + teks Arab Amiri), Doa-doa, Hadist terpilih
- [x] PWA offline caching strategy (`public/sw.js` + `manifest.webmanifest`)
- [x] Halaman offline fallback (`/offline`)
- [x] Update banner jika ada service worker versi baru

---

## 8. Modul 8: Laporan & Ekspor (100% Selesai)

- [x] Export rapor murid per individu ke format PDF (Dompdf)
- [x] Export rekap penilaian murid per individu ke Excel (Laravel Excel)
- [x] Export rekap absensi keseluruhan kelas/periode ke Excel (Laravel Excel)

---

## 🚀 ROADMAP BERIKUTNYA (Fase 2 / Pasca-Rilis)

Meskipun MVP saat ini sudah 100% selesai dan lulus semua uji unit/fitur, berikut adalah backlog fitur pengembangan lebih lanjut yang direkomendasikan:

### ⚡ Skala Prioritas Tinggi (High Priority)
1. **Push Notifications**: Notifikasi pengumuman baru, hari libur, atau pengingat setoran hafalan langsung ke HP wali santri (via Firebase Cloud Messaging / Web Push API).
2. **Grafik Perkembangan Nilai**: Chart visual (menggunakan chart.js atau library ringan) di portal murid untuk menunjukkan perkembangan halaman Iqra/Al-Qur'an dan nilai tulis santri dari waktu ke waktu.
3. **Integrasi WhatsApp Gateway**: Kirim pesan konfirmasi otomatis ke orang tua setelah admin melakukan update level atau menginput absensi ("Ananda [Nama] hari ini tidak hadir").

### 🟢 Skala Prioritas Menengah (Medium Priority)
1. **Video Tutorial Panduan Praktik**: Menambahkan link/pemutar video singkat pada modul panduan wudhu dan gerakan sholat agar santri bisa meniru gerakan dengan lebih baik.
2. **Sistem Multi-Cabang**: Jika TPQ membuka cabang baru, schema DB dapat diperluas dengan menambahkan kolom `cabang_id` di tabel murid dan transaksi.
3. **Sub-Role Admin**: Pemisahan hak akses admin (misal: Ustadz hanya bisa input nilai & absen, Tata Usaha mengelola keuangan/profil, Kepala TPQ melihat laporan keseluruhan).

### 🔵 Skala Prioritas Rendah (Low Priority)
1. **Gamifikasi & Lencana**: Penghargaan digital berupa badge atau lencana (misal: "Bintang Absensi", "Hafidz Cilik") jika murid mencapai tingkat kehadiran tertentu atau lulus target hafalan.
2. **Audio Murattal per Ayat**: Fitur play audio tilawah per ayat di portal murid (menggunakan source audio publik gratis).

---

*Terakhir Diperbarui: Juli 2026 | Tim Pengembang LMS TPQ Nurul Rahmanil Achyar*
