# AGENTS.md — LMS TPQ

> **LMS TPQ** — Laravel 13.x + PHP ^8.3. Single-project LMS + Landing Page for Taman Pendidikan Al-Qur'an.

Before coding, read `GUIDELINE.md` — it is the single source of truth for architecture, conventions, and workflow.

## Quick start

```bash
composer run setup        # full first-time setup (composer install → .env → key:generate → migrate → npm ci --ignore-scripts → npm run build)
composer run dev          # PHP server + queue + logs + Vite HMR concurrently
composer run test         # runs config:clear then phpunit
php artisan test --filter=SomeTest
php artisan migrate --seed
npm run build
```

## Auth

- **Two guards**: `admin` (admins table, Admin model), `web` (users table, User model)
- Both models use `Spatie\Permission\Traits\HasRoles` with explicit `$guard_name`
- Admin role is the Admin model's native `role` column (`superadmin`|`admin`), NOT Spatie roles
- **Login**: admins login via **email**, students via **username** (custom `LoginController` on single `/login` form)
- **Custom middleware** (aliased in `bootstrap/app.php`):
  - `superadmin` → `auth:admin` + role column `=== 'superadmin'`
  - `admin` → `auth:admin` + role column `=== 'admin'|'superadmin'`
  - `murid` → `auth:web` only
- No "forgot password" — admins reset passwords for lower roles

## Routes

All in `routes/web.php` (no API routes, no file splitting):

| Prefix | Middleware | Purpose |
|---|---|---|
| `/` | public | Landing page, `/daftar` PSB form |
| `/superadmin/*` | `auth:admin` + `superadmin` | System config, manage admins |
| `/admin/*` | `auth:admin` + `admin` | All LMS management features |
| `/admin/landing/*` | (same admin group) | Landing page CMS |
| `/admin/konten/flashcard/*` | (same admin group) | Flashcard deck + item CRUD |
| `/murid/*` | `auth:web` + `murid` | Student portal |
| `/login` | `guest` | Single login for all roles |
| `/offline` | public | PWA offline fallback |

Route names follow: `superadmin.*`, `admin.*`, `murid.*`, `admin.landing.*`, `admin.konten.flashcard.*`, `daftar.*`

## Architecture

- **Admin Panel**: Livewire 4 + Alpine.js — `@livewireStyles`/`@livewireScripts` in `layouts/admin.blade.php`. Only Livewire component: `app/Livewire/Admin/AbsensiInput.php`
- **Portal Murid**: Blade + Alpine.js — offline-capable PWA via custom SW at `public/sw.js` (registered inline in `layouts/murid.blade.php`)
- **Landing Page**: Public Blade layouts (`resources/views/layouts/public.blade.php`) — also registers PWA manifest
- **Services**: `GamificationService` (points + badges), `WhatsAppService` (Fonnte gateway with file log fallback)
- Superadmin manages system + admin accounts; Admin does daily ops; Murid views own data

## Testing

- PHPUnit with SQLite `:memory:` (configured in `phpunit.xml`)
- Suites: `tests/Unit`, `tests/Feature`
- Factories: `UserFactory`, `PendaftarFactory`, `LevelFactory`
- Tests cover: auth, admin CRUD (murid, absensi, penilaian, konten, pengumuman, laporan, flashcard), PSB flow, public routes, gamification, WhatsApp notifications
- Admin tests create admin via `Admin::create(...)` (no factory); User via `User::factory()`
- Level factory is required in setup for any test using `actingAs($user, 'web')`

## Seeders

Order in `DatabaseSeeder`:
```
LevelSeeder → DefaultAdminSeeder → QuranSeeder → DoaSeeder → HadistSeeder → LandingPageSeeder → AsmaulHusnaSeeder → FlashcardDeckSeeder → BadgeSeeder
```

## Style & toolchain gotchas

- **UI language**: 100% Bahasa Indonesia — all Blade views, error messages, placeholders, seed data, validation strings are in Indonesian. Do not write English strings.
- **Font Awesome** loaded via CDN (`cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css`) in **all** layouts (admin, superadmin, murid, public)
- **PWA manifest** only in murid + public layouts (not admin/superadmin)
- **PWA**: custom service worker at `public/sw.js` (no npm package, no Workbox)
- **Scroll-reveal**: public layout has `.reveal` and `.reveal-delay-{1..4}` CSS classes for landing page animations
- `.npmrc` sets `ignore-scripts=true` — npm scripts must be run explicitly

## Key packages

- `spatie/laravel-permission` — both guards
- `livewire/livewire` ^4.3 — admin only (do NOT install on murid portal)
- `barryvdh/laravel-dompdf` — PDF exports
- `maatwebsite/excel` ^3.1 — Excel exports via `MuridExport` and `KelasRecapExport`
- `intervention/image` ^4.1 — image resize/compress
- `mews/purifier` — HTML sanitization (TipTap content)
- `@tiptap/core` + starter-kit — rich text editor (cerita, panduan)

## References

- `GUIDELINE.md` — single source of truth; read first
- `PRD_LMS_TPQ.md` — full LMS feature specs (design doc; verify against code)
- `PRD_LandingPage_TPQ.md` — landing page / PSB specs (design doc; verify against code)
