# GUIDELINE — LMS TPQ Nurul Rahmanil Achyar

> Dokumen ini adalah **sumber kebenaran tunggal (single source of truth)** untuk arsitektur,
> konvensi kode, workflow, dan aturan development proyek ini.
> Baca ini sebelum menyentuh kode apapun.

---

## 1. Gambaran Sistem

```
LMS TPQ = Landing Page (public) + Admin Panel (Livewire) + Portal Murid (Blade + PWA)
Platform : Laravel 13.x + PHP ^8.3
DB       : MySQL 8.x via Eloquent ORM
Frontend : Tailwind CSS 3.x + Alpine.js + Vite
```

### Tiga Domain Utama

| Domain | URL Prefix | Teknologi UI | Offline? |
|---|---|---|---|
| Landing Page & PSB | `/`, `/daftar/*` | Blade + Alpine.js | ❌ |
| Admin Panel | `/admin/*`, `/superadmin/*` | Livewire 4 + Alpine.js | ❌ |
| Portal Murid | `/murid/*` | Blade + Alpine.js | ✅ PWA |

---

## 2. Autentikasi & Role

### Dua Guard, Dua Tabel

```
Guard `admin` → Tabel `admins` → Model App\Models\Admin
Guard `web`   → Tabel `users`  → Model App\Models\User
```

### Tiga Role

| Role | Guard | Login Field | Akses |
|---|---|---|---|
| `superadmin` | `admin` | email | `/superadmin/*` + semua `/admin/*` |
| `admin` | `admin` | email | `/admin/*` |
| `murid` | `web` | username | `/murid/*` |

### Middleware (Alias di `bootstrap/app.php`)

```php
'superadmin' → auth:admin + kolom role === 'superadmin'
'admin'      → auth:admin + kolom role IN ('admin', 'superadmin')
'murid'      → auth:web hanya
```

> ⚠️ **PENTING:** Role murid bukan dari Spatie Permission — hanya `auth:web` middleware.
> Admin role adalah kolom `role` di tabel `admins`, bukan Spatie roles.

---

## 3. Struktur Route

Semua route ada di satu file `routes/web.php` (tidak dipisah).

```
/                     → LandingController@index        (public)
/daftar               → PendaftaranController           (public)
/login                → LoginController                 (guest)
/logout               → LoginController@logout          (any auth)
/offline              → Route::view                     (public)

/superadmin/*         → middleware: auth:admin + superadmin
/admin/*              → middleware: auth:admin + admin
/admin/landing/*      → (sama admin group) CMS Landing Page
/murid/*              → middleware: auth:web + murid
```

### Nama Route Conventions

```
superadmin.dashboard
superadmin.admins.{index|create|store|show|edit|update|destroy}
admin.dashboard
admin.murid.{index|create|store|show|edit|update|destroy}
admin.absensi.{index|create|store|rekap}
admin.penilaian.{baca|hafalan|tulis|praktik}.{index|store|delete}
admin.konten.doa.{index|store|update|destroy}
admin.konten.cerita.{index|create|store|edit|update|destroy}
admin.konten.panduan.{index|create|store|edit|update|destroy}
admin.pengumuman.{index|create|store|edit|update|destroy}
admin.laporan.{index|murid|export-pdf|export-excel-murid|export-excel-kelas}
admin.landing.{pendaftaran|galeri|testimoni|pengurus|pengaturan}.*
murid.{dashboard|quran|doa|hadist|cerita|panduan|nilai|absensi|asmaul-husna|pengumuman}.*
```

---

## 4. Konvensi Kode

### Bahasa UI: 100% Bahasa Indonesia

- Semua string Blade: Bahasa Indonesia
- Flash message, validasi, placeholder, label: Bahasa Indonesia
- Nama route, variable PHP, class: Bahasa Inggris (standar Laravel)
- Komentar kode: boleh English atau Indonesia

### Struktur Controller

```php
// TIDAK ada logic berat di Controller
// Controller = validasi + call model/service + return view/redirect

namespace App\Http\Controllers\Admin;

class MuridController extends Controller
{
    public function index()      // daftar + filter + paginate
    public function create()     // form tambah
    public function store()      // simpan data baru
    public function show($id)    // detail view
    public function edit($id)    // form edit
    public function update()     // update data
    public function destroy()    // hapus/nonaktifkan
}
```

### Blade View Conventions

- Layout admin → `@extends('layouts.admin')` + `@livewireStyles` / `@livewireScripts`
- Layout murid → `@extends('layouts.murid')` — NO Livewire
- Layout publik → `@extends('layouts.public')`
- Font Awesome via CDN: tersedia di semua layout
- Google Fonts (Plus Jakarta Sans + Amiri): tersedia di semua layout
- Scroll-reveal class `.reveal` + `.reveal-delay-{1-4}`: tersedia di public layout

### Model Conventions

```php
class User extends Authenticatable
{
    protected $guard_name = 'web'; // WAJIB pada HasRoles trait
    protected $fillable = [...];   // Explicit fillable, tidak pakai guarded = []
    protected $casts = [...];      // Cast semua field boolean dan date

    // Relasi: camelCase method name
    public function currentLevel()     // belongsTo
    public function absensis()         // hasMany
    public function penilaianBacas()   // hasMany
}
```

### Livewire Component Conventions

- HANYA dipakai di Admin Panel — JANGAN install di portal murid
- Simpan di `app/Livewire/Admin/`
- View: `resources/views/livewire/admin/`
- Saat ini hanya `AbsensiInput.php` yang aktif sebagai Livewire component

---

## 5. Database Conventions

### Tabel Utama

| Tabel | Model | Keterangan |
|---|---|---|
| `admins` | Admin | Pengurus + Superadmin |
| `users` | User | Murid (santri) |
| `levels` | Level | 8 level: Pra-Iqra → Al-Qur'an |
| `user_level_histories` | UserLevelHistory | Audit trail perubahan level |
| `absensis` | Absensi | Unique: (user_id, tanggal, sesi) |
| `penilaian_bacas` | PenilaianBaca | Domain 1: baca |
| `penilaian_hafalans` | PenilaianHafalan | Domain 2: hafalan |
| `penilaian_tulises` | PenilaianTulis | Domain 3: tulis |
| `penilaian_praktiks` | PenilaianPraktik | Domain 4: praktik |
| `penilaian_praktik_komponens` | PenilaianPraktikKomponen | Checklist per praktik |
| `surahs` | Surah | 114 surat (seed, read-only) |
| `ayats` | Ayat | ~6348 ayat (seed, read-only) |
| `duas` | Doa | Doa-doa (seed + CRUD admin) |
| `hadiths` | Hadist | Hadist (seed + CRUD admin) |
| `cerita_kisahs` | CeritaKisah | Cerita (full CRUD + TipTap) |
| `panduan_praktiks` | PanduanPraktik | Panduan langkah-langkah |
| `langkah_panduans` | LangkahPanduan | Step per panduan |
| `pengumumans` | Pengumuman | Sistem pengumuman |
| `pendaftars` | Pendaftar | PSB form submissions |
| `galleries` | Galeri | Foto landing page |
| `testimonis` | Testimoni | Testimoni landing page |
| `pengurus_profiles` | PengurusProfile | Profil pengurus landing page |
| `landing_settings` | LandingSetting | Key-value CMS landing page |
| `asmaul_husnas` | AsmaulHusna | 99 Asmaul Husna |

### Seeder Order

```
LevelSeeder → DefaultAdminSeeder → QuranSeeder → DoaSeeder →
HadistSeeder → LandingPageSeeder → AsmaulHusnaSeeder
```

### Konvensi Migration

- Semua FK dengan `->constrained()->cascadeOnDelete()` atau `->nullable()`
- Selalu pakai `$table->timestamps()` kecuali tabel pivot simple
- Unique constraint absensi: `unique(['user_id', 'tanggal', 'sesi'])`

---

## 6. Frontend Stack

### Tailwind CSS 3.x

- Konfigurasi di `tailwind.config.js`
- Extended colors: tidak ada custom color baru — pakai emerald, amber, stone, gray
- Build via `npm run dev` (development) atau `npm run build` (production)
- `.npmrc` set `ignore-scripts=true` — jalankan npm scripts secara eksplisit

### Alpine.js

- Tersedia di semua layout via `resources/js/app.js`
- Admin Layout: `x-data="{ sidebarOpen: false }"` di body
- Public Layout: `x-data="{ mobileMenuOpen: false, scrolled: false }"` di body
- Jangan tambah Alpine store/plugin kecuali benar-benar diperlukan

### Icon Library

- Font Awesome 6.4.0 via CDN `cdnjs.cloudflare.com`
- Prefix: `fa-solid`, `fa-regular`, `fa-brands`
- **JANGAN** pakai emoji sebagai icon (violates ui-ux-pro-max rule)

### Google Fonts

- Public layout: **Plus Jakarta Sans** (body) + **Amiri** (Arabic text)
- Admin layout: **Plus Jakarta Sans**
- Murid layout: **Plus Jakarta Sans** + **Amiri**

---

## 7. PWA — Portal Murid

### Service Worker

- File: `public/sw.js` (custom, tanpa Workbox)
- Strategi cache per URL pattern:

| URL Pattern | Strategi |
|---|---|
| `/murid/quran`, `/murid/doa`, `/murid/hadist` | Cache-first |
| `/murid/dashboard`, `/murid/nilai`, `/murid/absensi` | Stale-while-revalidate |
| `/murid/pengumuman` | Network-first |
| Asset statis (CSS, JS, font, icon) | Cache-first permanent |
| Fallback | Network-first → `/offline` |

- Manifest: `public/manifest.webmanifest`
- Offline page: `resources/views/offline.blade.php` → route `/offline`

### Registrasi SW

- Diregistrasi inline di `layouts/murid.blade.php` (bukan di `layouts/admin.blade.php`)
- Public layout juga meregistrasi SW (untuk offline landing page fallback)

---

## 8. Upload File & Storage

- Disimpan di `storage/app/public/` dengan symlink ke `public/storage/`
- Gambar diproses via `intervention/image` ^4.1 → resize + compress sebelum simpan
- Validasi: `mimes:jpg,jpeg,png,webp`, max 2MB (foto murid), max 1MB (thumbnail konten)
- Panduan langkah: max 300KB per gambar setelah compress
- Tampilkan via `asset(Storage::url(...))` atau langsung dari path

---

## 9. Testing

### Setup

```bash
composer run test          # config:clear → phpunit
php artisan test --filter=SomeTest
```

- Driver: SQLite `:memory:` (konfigurasi di `phpunit.xml`)
- Factories: `UserFactory`, `PendaftarFactory`, `LevelFactory`

### Test Suite Coverage (Existing)

| File Test | Scope |
|---|---|
| `AuthMiddlewareTest` | Middleware route protection |
| `AdminMuridTest` | CRUD santri + reset password |
| `AdminAbsensiTest` | Input absensi, rekap |
| `AdminPenilaianTest` | 4 domain penilaian |
| `AdminKontenTest` | Doa, Hadist, Cerita, Panduan |
| `AdminPengumumanTest` | CRUD + filter pengumuman |
| `AdminLaporanTest` | Export PDF + Excel |
| `PSBFlowTest` | Flow pendaftaran PSB |
| `PublicRoutesTest` | Landing + daftar routes |
| `MuridPortalTest` | Portal murid basic access |

---

## 10. Ekspor (Laporan)

| Format | Package | Controller | Scope |
|---|---|---|---|
| PDF | `barryvdh/laravel-dompdf` | `LaporanController@exportPdf` | Per murid, format rapor |
| Excel Murid | `maatwebsite/excel` via `MuridExport` | `exportExcelMurid` | Data murid + penilaian |
| Excel Rekap Kelas | via `KelasRecapExport` | `exportExcelKelas` | Rekap absensi semua murid |

---

## 11. Konten Seeding

| Konten | Sumber | File |
|---|---|---|
| Al-Qur'an (114 surat + 6348 ayat) | `quran-json` (GitHub) | `database/data/quran.json` |
| Doa (~60 doa) | Kurasi manual | `database/data/doa.json` |
| Hadist (~80 hadist) | hadith.gading.dev + kurasi | `database/data/hadist.json` |
| Asmaul Husna (99) | Data statis | `AsmaulHusnaSeeder` |

---

## 12. Landing Page CMS

Admin dapat mengelola konten landing page via `/admin/landing/*`:

| Fitur | Route | Controller |
|---|---|---|
| Pengaturan (hero, kontak, dll) | `admin.landing.pengaturan.*` | `LandingSettingController` |
| Galeri Foto | `admin.landing.galeri.*` | `GaleriController` |
| Testimoni | `admin.landing.testimoni.*` | `TestimoniController` |
| Profil Pengurus | `admin.landing.pengurus.*` | `PengurusProfileController` |
| Data Pendaftar PSB | `admin.landing.pendaftaran.*` | `PendaftaranController` |

`LandingSetting` menggunakan pola `key → value` dengan method static `getValue(key)`.

---

## 13. Do's & Don't's

### ✅ DO

- Gunakan Bahasa Indonesia untuk semua string yang ditampilkan ke user
- Selalu tambahkan `.reveal` class pada section baru di halaman publik
- Pakai Font Awesome untuk semua ikon — tidak ada emoji sebagai ikon
- Pastikan semua form publik punya CSRF `@csrf`
- Pakai `fieldset + legend` untuk form dengan beberapa grup field
- Scope semua query murid ke `user_id = auth()->id()`
- Tambah index database untuk kolom yang sering di-filter
- Validasi semua input di server-side (FormRequest atau manual)
- Compress gambar sebelum simpan via Intervention Image

### ❌ DON'T

- Jangan install Livewire component di portal murid (`/murid/*`)
- Jangan split `routes/web.php` — semua di satu file
- Jangan hardcode ID atau string konstan — pakai seeder/config
- Jangan hapus permanen murid atau admin — cukup `is_active = false`
- Jangan pakai emoji sebagai ikon struktural di UI
- Jangan tulis raw SQL query — pakai Eloquent ORM
- Jangan tambah Alpine.js store/plugin tanpa diskusi
- Jangan pakai `font-size < 14px` untuk body text (WCAG readable-font-size)
- Jangan tambah route file baru — semua di `web.php`

---

## 14. Perintah Penting

```bash
# Jalankan aplikasi (semuanya sekaligus)
composer run dev

# Build CSS/JS untuk production
npm run build

# Jalankan semua test
composer run test

# Jalankan test spesifik
php artisan test --filter=AdminMuridTest

# Migrasi fresh + seed ulang
php artisan migrate:fresh --seed

# Clear semua cache
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Buat storage link
php artisan storage:link
```

---

## 15. Onboarding Checklist Developer Baru

- [ ] Clone repo + `cp .env.example .env` + `php artisan key:generate`
- [ ] `composer install` + `npm install`
- [ ] Buat database MySQL + konfigurasi `.env`
- [ ] `php artisan migrate --seed`
- [ ] `php artisan storage:link`
- [ ] `npm run build` atau `npm run dev`
- [ ] Login sebagai superadmin (lihat `DefaultAdminSeeder` untuk credentials)
- [ ] Baca `PRD_LMS_TPQ.md` dan `PRD_LandingPage_TPQ.md`
- [ ] Baca `AGENTS.md` (`.agents/AGENTS.md`)
- [ ] Jalankan `composer run test` dan pastikan semua hijau

---

*Last Updated: Juli 2026 | Maintained by: Kevin Adi Surya Nugraha*
