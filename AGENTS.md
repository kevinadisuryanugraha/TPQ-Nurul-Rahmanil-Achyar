# AGENTS.md — LMS TPQ

> **LMS TPQ** — Laravel 13.x + PHP ^8.3. Single-project LMS + Landing Page for Taman Pendidikan Al-Qur'an.

## Quick start

```bash
# Full setup (first time):
composer run setup

# Daily dev (serves PHP, queue, logs, Vite concurrently):
composer run dev

# Run tests (clears config first):
composer run test

# Individual commands:
php artisan test --filter=SomeTest               # single test
php artisan migrate --seed                        # fresh migrate + seed
npm run build                                     # Vite production build
```

## Auth architecture

- **Two guards**: `admin` (Admin/Superadmin via `admins` table), `web` (Murid via `users` table)
- Both models (`Admin`, `User`) use `Spatie\Permission\Traits\HasRoles` with explicit `$guard_name`
- **Custom middleware** (aliased in `bootstrap/app.php`): `superadmin`, `admin`, `murid`
  - EnsureMurid: checks `auth:web` only
  - EnsureAdmin: checks `auth:admin` + role `admin|superadmin`
  - EnsureSuperadmin: checks `isSuperadmin()` (role === 'superadmin')
- Spatie middleware also aliased: `role`, `permission`, `role_or_permission`
- No "forgot password" — admins reset passwords for lower roles

## Routes

All routes in `routes/web.php` (no API routes, no file splitting):

| Prefix | Middleware | Purpose |
|---|---|---|
| `/` | public | Landing page, `/daftar` PSB form |
| `/superadmin/*` | `auth:admin` + `superadmin` | System config, manage admins |
| `/admin/*` | `auth:admin` + `admin` | All LMS management features |
| `/admin/landing/*` | (same admin group) | Landing page CMS (PSB, gallery, etc.) |
| `/murid/*` | `auth:web` + `murid` | Student portal (Quran, doa, etc.) |
| `/login` | `guest` | Single login for all roles |
| `/offline` | public | PWA offline fallback |

All routes named following pattern: `superadmin.*`, `admin.*`, `murid.*`, `admin.landing.*`, `daftar.*`

## Architecture

- **Admin Panel**: Livewire 4 + Alpine.js (no offline support)
- **Portal Murid**: Blade + Alpine.js, offline-capable via custom SW at `public/sw.js`
- **Landing Page**: Public Blade layouts (`resources/views/layouts/public.blade.php`)
- **Superadmin** manages system settings + admin accounts; **Admin** does all daily ops; **Murid** views own data only

## Key packages (notable usage context)

- `spatie/laravel-permission` — roles & permissions (both guards)
- `livewire/livewire` ^4.3 — admin-only UI reactivity
- `barryvdh/laravel-dompdf` — PDF exports (laporan)
- `maatwebsite/excel` ^3.1 — Excel exports (laporan)
- `intervention/image` ^4.1 — image resize/compress (galeri, pengurus)
- `mews/purifier` — HTML sanitization (TipTap rich-text content)
- `@tiptap/core` + starter-kit — rich text editor for stories/guides

## Testing

- PHPUnit with SQLite `:memory:` (configured in `phpunit.xml`)
- Test suites: `tests/Unit`, `tests/Feature`
- `composer run test` runs `php artisan config:clear` first (don't skip)

## Seeders (run in order via `DatabaseSeeder`)

```
LevelSeeder → DefaultAdminSeeder → QuranSeeder → DoaSeeder → HadistSeeder → LandingPageSeeder → AsmaulHusnaSeeder
```

## Code style

- **PHP**: Laravel Pint (no `pint.json` — uses defaults)
- **JS/CSS**: Vite build, Tailwind CSS 3.x, Alpine.js 3.x, PostCSS with autoprefixer
- **EditorConfig**: 4-space indent, LF, UTF-8 (2-space for yaml)
- `.npmrc` sets `ignore-scripts=true` — scripts must be run explicitly

## PWA

- Custom service worker at `public/sw.js` (no npm package)
- Manifest at `public/manifest.webmanifest`
- Offline page at `routes/web.php` → `resources/views/offline.blade.php`
- Theme color: `#064e3b` (emerald-900)

## Important constraints

- **No route files split** — everything in `routes/web.php`; no `api.php`
- **No CI/CD** — no `.github/` workflows
- **Admin guard model**: `App\Models\Admin` (`admins` table); **Student guard model**: `App\Models\User` (`users` table)
- `composer run setup` does: `composer install`, copies `.env`, `key:generate`, `migrate --force`, `npm install --ignore-scripts`, `npm run build`
- `composer run dev` uses `npx concurrently` to run `php artisan serve`, `php artisan queue:listen`, `php artisan pail` (logs), and `npm run dev`
- `storage/app/public` linked via `php artisan storage:link`

## PRD references

- `PRD_LMS_TPQ.md` — full specifications for LMS features
- `PRD_LandingPage_TPQ.md` — specifications for public landing page + PSB flow
- These are **design documents** — actual implementation may differ; verify against code before assuming completeness
