# Interactive Flashcards Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun fitur Pembelajaran Interaktif berbasis Flashcards yang dinamis dan kustom di portal murid serta pengelolaannya di panel admin.

**Architecture:** Menerapkan skema unified deck di mana dek kustom maupun otomatis (Doa/Hadits/Quran) dilacak dari tabel `flashcard_decks` dan dimainkan menggunakan 3D flip CSS + Alpine.js di frontend murid.

**Tech Stack:** Laravel 13.x, Tailwind CSS 3.x, Alpine.js, PHPUnit.

## Global Constraints
- Bahasa UI 100% Bahasa Indonesia.
- Jangan gunakan emoji sebagai ikon struktural (gunakan Font Awesome).
- Gunakan font Amiri untuk teks Arab berukuran besar dan berarah RTL.
- Semua form publik harus menyertakan token CSRF.

---

### Task 1: Database Migrations & Seeders

**Files:**
- Create: `database/migrations/2026_07_01_000001_create_flashcard_decks_table.php`
- Create: `database/migrations/2026_07_01_000002_create_flashcard_items_table.php`
- Create: `database/seeders/FlashcardDeckSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

**Interfaces:**
- Produces: Tabel database `flashcard_decks` dan `flashcard_items` siap digunakan dengan data bawaan terisi.

- [ ] **Step 1: Write migration for flashcard decks**
  Create file `database/migrations/2026_07_01_000001_create_flashcard_decks_table.php`:
  ```php
  <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration {
      public function up(): void
      {
          Schema::create('flashcard_decks', function (Blueprint $table) {
              $table->id();
              $table->string('nama', 100);
              $table->string('deskripsi', 255)->nullable();
              $table->enum('source_type', ['system_doa', 'system_hadist', 'system_quran', 'custom'])->default('custom');
              $table->foreignId('level_target_id')->nullable()->constrained('levels')->nullOnDelete();
              $table->boolean('is_active')->default(true);
              $table->timestamps();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('flashcard_decks');
      }
  };
  ```

- [ ] **Step 2: Write migration for flashcard items**
  Create file `database/migrations/2026_07_01_000002_create_flashcard_items_table.php`:
  ```php
  <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration {
      public function up(): void
      {
          Schema::create('flashcard_items', function (Blueprint $table) {
              $table->id();
              $table->foreignId('deck_id')->constrained('flashcard_decks')->cascadeOnDelete();
              $table->text('front_content');
              $table->text('back_content');
              $table->integer('urutan')->default(0);
              $table->timestamps();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('flashcard_items');
      }
  };
  ```

- [ ] **Step 3: Run migrations**
  Run: `php artisan migrate`
  Expected: Migrations succeed.

- [ ] **Step 4: Create and register FlashcardDeckSeeder**
  Create `database/seeders/FlashcardDeckSeeder.php`:
  ```php
  <?php

  namespace Database\Seeders;

  use Illuminate\Database\Seeder;
  use App\Models\FlashcardDeck;

  class FlashcardDeckSeeder extends Seeder
  {
      public function run(): void
      {
          FlashcardDeck::create([
              'nama' => 'Doa Harian Santri',
              'deskripsi' => 'Kumpulan doa harian untuk aktivitas sehari-hari.',
              'source_type' => 'system_doa',
              'level_target_id' => 1, // Pra-Iqra
              'is_active' => true,
          ]);

          FlashcardDeck::create([
              'nama' => 'Hadits Akhlak Mulia',
              'deskripsi' => 'Kumpulan hadits pendek mengenai adab dan perilaku terpuji.',
              'source_type' => 'system_hadist',
              'level_target_id' => 2, // Iqra 1
              'is_active' => true,
          ]);
      }
  }
  ```

  Modify `database/seeders/DatabaseSeeder.php` to include:
  ```diff
  +$this->call(FlashcardDeckSeeder::class);
  ```

- [ ] **Step 5: Run seeders**
  Run: `php artisan db:seed --class=FlashcardDeckSeeder`
  Expected: Seeders run successfully.

- [ ] **Step 6: Commit changes**
  Run: `git add database/` and `git commit -m "feat: add migrations and seeder for flashcards"`

---

### Task 2: Models & Relationships

**Files:**
- Create: `app/Models/FlashcardDeck.php`
- Create: `app/Models/FlashcardItem.php`

**Interfaces:**
- Produces: `FlashcardDeck` and `FlashcardItem` models with proper relationships.

- [ ] **Step 1: Create FlashcardDeck model**
  Create `app/Models/FlashcardDeck.php`:
  ```php
  <?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\HasMany;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class FlashcardDeck extends Model
  {
      protected $fillable = ['nama', 'deskripsi', 'source_type', 'level_target_id', 'is_active'];

      protected $casts = [
          'is_active' => 'boolean',
      ];

      public function items(): HasMany
      {
          return $this->hasMany(FlashcardItem::class, 'deck_id')->orderBy('urutan');
      }

      public function level(): BelongsTo
      {
          return $this->belongsTo(Level::class, 'level_target_id');
      }
  }
  ```

- [ ] **Step 2: Create FlashcardItem model**
  Create `app/Models/FlashcardItem.php`:
  ```php
  <?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class FlashcardItem extends Model
  {
      protected $fillable = ['deck_id', 'front_content', 'back_content', 'urutan'];

      public function deck(): BelongsTo
      {
          return $this->belongsTo(FlashcardDeck::class, 'deck_id');
      }
  }
  ```

- [ ] **Step 3: Commit**
  Run: `git add app/Models/` and `git commit -m "feat: add flashcard models and relationships"`

---

### Task 3: Admin Flashcard Controller & Routing

**Files:**
- Create: `app/Http/Controllers/Admin/FlashcardController.php`
- Modify: `routes/web.php`

**Interfaces:**
- Consumes: Models `FlashcardDeck` and `FlashcardItem`.
- Produces: CRUD controller endpoints and routing definitions.

- [ ] **Step 1: Implement Admin FlashcardController**
  Create `app/Http/Controllers/Admin/FlashcardController.php`:
  ```php
  <?php

  namespace App\Http\Controllers\Admin;

  use App\Http\Controllers\Controller;
  use App\Models\FlashcardDeck;
  use App\Models\FlashcardItem;
  use App\Models\Level;
  use Illuminate\Http\Request;

  class FlashcardController extends Controller
  {
      public function index()
      {
          $decks = FlashcardDeck::with('level')->get();
          return view('admin.landing.flashcard.index', compact('decks'));
      }

      public function create()
      {
          $levels = Level::orderBy('urutan')->get();
          return view('admin.landing.flashcard.create', compact('levels'));
      }

      public function store(Request $request)
      {
          $validated = $request->validate([
              'nama' => 'required|string|max:100',
              'deskripsi' => 'nullable|string|max:255',
              'source_type' => 'required|in:system_doa,system_hadist,system_quran,custom',
              'level_target_id' => 'nullable|exists:levels,id',
              'is_active' => 'required|boolean',
          ]);

          FlashcardDeck::create($validated);
          return redirect()->route('admin.konten.flashcard.index')->with('success', 'Dek Flashcard berhasil dibuat.');
      }

      public function edit($id)
      {
          $deck = FlashcardDeck::findOrFail($id);
          $levels = Level::orderBy('urutan')->get();
          return view('admin.landing.flashcard.edit', compact('deck', 'levels'));
      }

      public function update(Request $request, $id)
      {
          $deck = FlashcardDeck::findOrFail($id);
          $validated = $request->validate([
              'nama' => 'required|string|max:100',
              'deskripsi' => 'nullable|string|max:255',
              'level_target_id' => 'nullable|exists:levels,id',
              'is_active' => 'required|boolean',
          ]);

          $deck->update($validated);
          return redirect()->route('admin.konten.flashcard.index')->with('success', 'Dek Flashcard berhasil diperbarui.');
      }

      public function destroy($id)
      {
          $deck = FlashcardDeck::findOrFail($id);
          $deck->delete();
          return redirect()->route('admin.konten.flashcard.index')->with('success', 'Dek Flashcard berhasil dihapus.');
      }

      public function itemsIndex($deck_id)
      {
          $deck = FlashcardDeck::findOrFail($deck_id);
          if ($deck->source_type !== 'custom') {
              return redirect()->route('admin.konten.flashcard.index')->with('error', 'Dek bawaan sistem tidak bisa diedit kartunya secara manual.');
          }
          $items = $deck->items;
          return view('admin.landing.flashcard.items', compact('deck', 'items'));
      }

      public function itemsStore(Request $request, $deck_id)
      {
          $deck = FlashcardDeck::findOrFail($deck_id);
          $validated = $request->validate([
              'front_content' => 'required|string',
              'back_content' => 'required|string',
              'urutan' => 'nullable|integer',
          ]);

          $deck->items()->create($validated);
          return redirect()->back()->with('success', 'Kartu berhasil ditambahkan.');
      }

      public function itemsUpdate(Request $request, $item_id)
      {
          $item = FlashcardItem::findOrFail($item_id);
          $validated = $request->validate([
              'front_content' => 'required|string',
              'back_content' => 'required|string',
              'urutan' => 'required|integer',
          ]);

          $item->update($validated);
          return redirect()->back()->with('success', 'Kartu berhasil diperbarui.');
      }

      public function itemsDestroy($item_id)
      {
          $item = FlashcardItem::findOrFail($item_id);
          $item->delete();
          return redirect()->back()->with('success', 'Kartu berhasil dihapus.');
      }
  }
  ```

- [ ] **Step 2: Register routes in web.php**
  Open `routes/web.php`. Inside the `Route::prefix('admin')` group, add:
  ```php
  // Flashcard CMS management routes
  Route::resource('/konten/flashcard', \App\Http\Controllers\Admin\FlashcardController::class)->names('admin.konten.flashcard');
  Route::get('/konten/flashcard/{deck_id}/item', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsIndex'])->name('admin.konten.flashcard.items.index');
  Route::post('/konten/flashcard/{deck_id}/item', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsStore'])->name('admin.konten.flashcard.items.store');
  Route::put('/konten/flashcard/item/{item_id}', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsUpdate'])->name('admin.konten.flashcard.items.update');
  Route::delete('/konten/flashcard/item/{item_id}', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsDestroy'])->name('admin.konten.flashcard.items.destroy');
  ```

- [ ] **Step 3: Commit**
  Run: `git add app/Http/Controllers/ routes/` and `git commit -m "feat: add admin flashcard routes and controller"`

---

### Task 4: Admin Panel Views

**Files:**
- Create: `resources/views/admin/landing/flashcard/index.blade.php`
- Create: `resources/views/admin/landing/flashcard/create.blade.php`
- Create: `resources/views/admin/landing/flashcard/edit.blade.php`
- Create: `resources/views/admin/landing/flashcard/items.blade.php`
- Modify: `resources/views/layouts/admin.blade.php` (sidebar navigation update)

**Interfaces:**
- Consumes: Admin Flashcard routes and layouts.
- Produces: The HTML forms and tables for admin flashcard management.

- [ ] **Step 1: Create Index view**
  Create `resources/views/admin/landing/flashcard/index.blade.php` (tabel ringkasan dek, dengan tombol aksi):
  ```html
  @extends('layouts.admin')
  @section('title', 'Manajemen Flashcard')
  @section('page_title', 'Kelola Dek Flashcard')
  @section('content')
  <div class="space-y-6">
      <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
          <p class="text-xs text-gray-500">Kelola kumpulan kartu interaktif bawaan sistem atau kustom.</p>
          <a href="{{ route('admin.konten.flashcard.create') }}" class="px-4 py-2 bg-emerald-800 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition">
              <i class="fa-solid fa-plus mr-1"></i> Tambah Dek Baru
          </a>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
          <table class="w-full text-left border-collapse">
              <thead>
                  <tr class="bg-gray-50 text-[10px] text-gray-400 uppercase border-b border-gray-100 font-bold">
                      <th class="px-6 py-4">Nama Dek</th>
                      <th class="px-6 py-4">Tipe Sumber</th>
                      <th class="px-6 py-4">Rekomendasi Level</th>
                      <th class="px-6 py-4">Status</th>
                      <th class="px-6 py-4 text-right">Aksi</th>
                  </tr>
              </thead>
              <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                  @foreach($decks as $deck)
                  <tr>
                      <td class="px-6 py-4">
                          <span class="font-bold text-gray-900 block">{{ $deck->nama }}</span>
                          <span class="text-xs text-gray-400 block mt-0.5">{{ $deck->deskripsi ?? '-' }}</span>
                      </td>
                      <td class="px-6 py-4">
                          @if($deck->source_type === 'custom')
                              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">KUSTOM (MANUAL)</span>
                          @else
                              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">SISTEM (OTOMATIS)</span>
                          @endif
                      </td>
                      <td class="px-6 py-4 font-semibold text-emerald-800">
                          {{ $deck->level->nama ?? 'Semua Level' }}
                      </td>
                      <td class="px-6 py-4">
                          <span class="w-2.5 h-2.5 rounded-full inline-block {{ $deck->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                      </td>
                      <td class="px-6 py-4 text-right space-x-1">
                          @if($deck->source_type === 'custom')
                              <a href="{{ route('admin.konten.flashcard.items.index', $deck->id) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition">
                                  <i class="fa-solid fa-list-check mr-1"></i> Kelola Kartu
                              </a>
                          @endif
                          <a href="{{ route('admin.konten.flashcard.edit', $deck->id) }}" class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-bold transition">
                              Edit
                          </a>
                          <form action="{{ route('admin.konten.flashcard.destroy', $deck->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dek ini?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-xs font-bold transition">
                                  Hapus
                              </button>
                          </form>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
  </div>
  @endsection
  ```

- [ ] **Step 2: Create Add Deck view**
  Create `resources/views/admin/landing/flashcard/create.blade.php`:
  ```html
  @extends('layouts.admin')
  @section('title', 'Tambah Dek Flashcard')
  @section('content')
  <div class="max-w-2xl bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="h-1.5 bg-emerald-800"></div>
      <form action="{{ route('admin.konten.flashcard.store') }}" method="POST" class="p-8 space-y-6">
          @csrf
          <div>
              <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Dek</label>
              <input type="text" name="nama" id="nama" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800">
          </div>
          <div>
              <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
              <input type="text" name="deskripsi" id="deskripsi" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800">
          </div>
          <div>
              <label for="source_type" class="block text-sm font-bold text-gray-700 mb-2">Sumber Data Kartu</label>
              <select name="source_type" id="source_type" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                  <option value="custom">Kustom (Input manual satu per satu)</option>
                  <option value="system_doa">Doa Harian (Otomatis dari bank data doa)</option>
                  <option value="system_hadist">Hadits Pendek (Otomatis dari bank data hadits)</option>
                  <option value="system_quran">Al-Qur'an Surat Pendek (Otomatis dari database surah)</option>
              </select>
          </div>
          <div>
              <label for="level_target_id" class="block text-sm font-bold text-gray-700 mb-2">Rekomendasi Level Santri</label>
              <select name="level_target_id" id="level_target_id" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                  <option value="">Semua Level</option>
                  @foreach($levels as $level)
                      <option value="{{ $level->id }}">{{ $level->nama }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label for="is_active" class="block text-sm font-bold text-gray-700 mb-2">Status Aktif</label>
              <select name="is_active" id="is_active" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                  <option value="1">Aktif</option>
                  <option value="0">Nonaktif</option>
              </select>
          </div>
          <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
              <a href="{{ route('admin.konten.flashcard.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700 transition">Batal</a>
              <button type="submit" class="px-5 py-3 bg-emerald-800 hover:bg-emerald-700 rounded-xl text-xs font-bold text-white transition">Simpan Dek</button>
          </div>
      </form>
  </div>
  @endsection
  ```

- [ ] **Step 3: Create Edit Deck view**
  Create `resources/views/admin/landing/flashcard/edit.blade.php`:
  ```html
  @extends('layouts.admin')
  @section('title', 'Edit Dek Flashcard')
  @section('content')
  <div class="max-w-2xl bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="h-1.5 bg-emerald-800"></div>
      <form action="{{ route('admin.konten.flashcard.update', $deck->id) }}" method="POST" class="p-8 space-y-6">
          @csrf
          @method('PUT')
          <div>
              <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Dek</label>
              <input type="text" name="nama" id="nama" value="{{ old('nama', $deck->nama) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800">
          </div>
          <div>
              <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
              <input type="text" name="deskripsi" id="deskripsi" value="{{ old('deskripsi', $deck->deskripsi) }}" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800">
          </div>
          <div>
              <label class="block text-sm font-bold text-gray-400 mb-2">Sumber Data (Tidak bisa diubah)</label>
              <input type="text" disabled value="{{ strtoupper($deck->source_type) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-400 focus:outline-none">
          </div>
          <div>
              <label for="level_target_id" class="block text-sm font-bold text-gray-700 mb-2">Rekomendasi Level Santri</label>
              <select name="level_target_id" id="level_target_id" class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                  <option value="">Semua Level</option>
                  @foreach($levels as $level)
                      <option value="{{ $level->id }}" {{ $deck->level_target_id == $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>
                  @endforeach
              </select>
          </div>
          <div>
              <label for="is_active" class="block text-sm font-bold text-gray-700 mb-2">Status Aktif</label>
              <select name="is_active" id="is_active" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 bg-white">
                  <option value="1" {{ $deck->is_active ? 'selected' : '' }}>Aktif</option>
                  <option value="0" {{ !$deck->is_active ? 'selected' : '' }}>Nonaktif</option>
              </select>
          </div>
          <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
              <a href="{{ route('admin.konten.flashcard.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700 transition">Batal</a>
              <button type="submit" class="px-5 py-3 bg-emerald-800 hover:bg-emerald-700 rounded-xl text-xs font-bold text-white transition">Simpan Perubahan</button>
          </div>
      </form>
  </div>
  @endsection
  ```

- [ ] **Step 4: Create items view for custom decks**
  Create `resources/views/admin/landing/flashcard/items.blade.php`:
  ```html
  @extends('layouts.admin')
  @section('title', 'Kelola Kartu')
  @section('page_title')
      Kelola Kartu: {{ $deck->nama }}
  @endsection
  @section('content')
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Form Tambah Kartu -->
      <div class="lg:col-span-5 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden h-fit">
          <div class="h-1.5 bg-emerald-800"></div>
          <div class="p-6">
              <h3 class="font-bold text-gray-800 text-sm mb-4">Tambah Kartu Baru</h3>
              <form action="{{ route('admin.konten.flashcard.items.store', $deck->id) }}" method="POST" class="space-y-4">
                  @csrf
                  <div>
                      <label for="front_content" class="block text-xs font-bold text-gray-600 mb-1.5">Sisi Depan (Pertanyaan / Petunjuk)</label>
                      <textarea name="front_content" id="front_content" required rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-emerald-800 resize-none" placeholder="Masukkan tulisan depan..."></textarea>
                  </div>
                  <div>
                      <label for="back_content" class="block text-xs font-bold text-gray-600 mb-1.5">Sisi Belakang (Jawaban / Penjelasan)</label>
                      <textarea name="back_content" id="back_content" required rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-emerald-800 resize-none" placeholder="Masukkan tulisan belakang..."></textarea>
                  </div>
                  <div>
                      <label for="urutan" class="block text-xs font-bold text-gray-600 mb-1.5">Nomor Urutan</label>
                      <input type="number" name="urutan" id="urutan" value="0" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-emerald-800">
                  </div>
                  <button type="submit" class="w-full py-3 bg-emerald-800 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                      <i class="fa-solid fa-plus mr-1"></i> Tambah Kartu
                  </button>
              </form>
          </div>
      </div>

      <!-- List Kartu Existing -->
      <div class="lg:col-span-7 space-y-4">
          <h3 class="font-bold text-gray-800 text-sm">Daftar Kartu yang Sudah Ada</h3>
          
          @if($items->isEmpty())
              <div class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm text-gray-400">
                  <i class="fa-solid fa-clone text-3xl mb-3 block text-gray-300"></i>
                  <span class="text-xs">Belum ada kartu di dek ini. Tambahkan kartu pertama Anda di sebelah kiri.</span>
              </div>
          @else
              <div class="space-y-3">
                  @foreach($items as $index => $item)
                      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4" x-data="{ editing: false }">
                          
                          <!-- View Mode -->
                          <div x-show="!editing" class="flex justify-between items-start">
                              <div class="grid grid-cols-2 gap-4 w-10/12">
                                  <div>
                                      <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Sisi Depan</span>
                                      <p class="text-xs text-gray-700 mt-1 font-medium whitespace-pre-line">{{ $item->front_content }}</p>
                                  </div>
                                  <div>
                                      <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Sisi Belakang</span>
                                      <p class="text-xs text-gray-700 mt-1 font-medium whitespace-pre-line">{{ $item->back_content }}</p>
                                  </div>
                              </div>
                              <div class="flex gap-1.5">
                                  <button @click="editing = true" class="p-1 text-gray-500 hover:text-emerald-800 transition"><i class="fa-solid fa-pen text-xs"></i></button>
                                  <form action="{{ route('admin.konten.flashcard.items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kartu ini?')">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="p-1 text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                  </form>
                              </div>
                          </div>

                          <!-- Edit Mode -->
                          <form x-show="editing" style="display:none;" action="{{ route('admin.konten.flashcard.items.update', $item->id) }}" method="POST" class="space-y-4">
                              @csrf
                              @method('PUT')
                              <div>
                                  <label class="block text-xs font-bold text-gray-600 mb-1">Edit Sisi Depan</label>
                                  <textarea name="front_content" required rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none resize-none">{{ $item->front_content }}</textarea>
                              </div>
                              <div>
                                  <label class="block text-xs font-bold text-gray-600 mb-1">Edit Sisi Belakang</label>
                                  <textarea name="back_content" required rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none resize-none">{{ $item->back_content }}</textarea>
                              </div>
                              <div class="grid grid-cols-2 gap-4">
                                  <div>
                                      <label class="block text-xs font-bold text-gray-600 mb-1">Urutan</label>
                                      <input type="number" name="urutan" value="{{ $item->urutan }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none">
                                  </div>
                                  <div class="flex gap-2 justify-end items-end">
                                      <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-200 transition">Batal</button>
                                      <button type="submit" class="px-4 py-2 bg-emerald-800 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">Simpan</button>
                                  </div>
                              </div>
                          </form>

                      </div>
                  @endforeach
              </div>
          @endif
      </div>
  </div>
  @endsection
  ```

- [ ] **Step 5: Add sidebar link to admin layout**
  Open `resources/views/layouts/admin.blade.php`. In the Content Management section, add:
  ```html
  <a href="{{ route('admin.konten.flashcard.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl hover:bg-emerald-800 transition duration-200 {{ request()->routeIs('admin.konten.flashcard.*') ? 'bg-emerald-800 text-white font-semibold' : '' }}">
      <i class="fa-solid fa-clone w-5"></i>
      <span class="text-sm">Flashcards</span>
  </a>
  ```

- [ ] **Step 6: Commit**
  Run: `git add resources/views/` and `git commit -m "feat: add admin CRUD views and layout link for flashcards"`

---

### Task 5: Murid Portal Flashcard Controller & Index View

**Files:**
- Create: `app/Http/Controllers/Murid/FlashcardController.php`
- Create: `resources/views/murid/flashcard/index.blade.php`
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/murid.blade.php` (sidebar navigation update)

**Interfaces:**
- Consumes: Models `FlashcardDeck` and `User`.
- Produces: The list of active flashcard decks in the student dashboard area.

- [ ] **Step 1: Create Murid FlashcardController**
  Create `app/Http/Controllers/Murid/FlashcardController.php` (handling sorting by target level):
  ```php
  <?php

  namespace App\Http\Controllers\Murid;

  use App\Http\Controllers\Controller;
  use App\Models\FlashcardDeck;
  use App\Models\Doa;
  use App\Models\Hadist;
  use App\Models\Surah;
  use Illuminate\Http\Request;

  class FlashcardController extends Controller
  {
      public function index()
      {
          $student = auth()->user();
          $decks = FlashcardDeck::where('is_active', true)
              ->get()
              ->sortByDesc(function ($deck) use ($student) {
                  return $deck->level_target_id == $student->current_level_id ? 2 : 1;
              });

          return view('murid.flashcard.index', compact('decks', 'student'));
      }

      public function show($id)
      {
          $deck = FlashcardDeck::where('id', $id)->where('is_active', true)->firstOrFail();
          $student = auth()->user();
          $cardsData = [];

          if ($deck->source_type === 'system_doa') {
              $doas = Doa::where('is_active', true)->orderBy('urutan')->get();
              foreach ($doas as $doa) {
                  $cardsData[] = [
                      'front' => "Membaca Doa: " . $doa->judul . "\n\nArti:\n" . $doa->terjemahan,
                      'back' => $doa->teks_arab . "\n\n" . $doa->transliterasi,
                  ];
              }
          } elseif ($deck->source_type === 'system_hadist') {
              $hadists = Hadist::where('is_active', true)->get();
              foreach ($hadists as $hadist) {
                  $cardsData[] = [
                      'front' => "Arti Hadits:\n" . $hadist->terjemahan . "\n\nSumber: " . $hadist->sumber_kitab,
                      'back' => $hadist->teks_arab,
                  ];
              }
          } elseif ($deck->source_type === 'system_quran') {
              // Get 10 surahs starting from short ones (Juz 30 surahs like Al-Fatihah, An-Nas, Al-Falaq)
              $surahs = Surah::orderBy('id', 'desc')->take(10)->get();
              foreach ($surahs as $surah) {
                  $cardsData[] = [
                      'front' => "Surat " . $surah->nama_latin . " (" . $surah->nama_indonesia . ")\n\nArti Nama:\n" . $surah->arti,
                      'back' => $surah->nama_arab,
                  ];
              }
          } else {
              $items = $deck->items;
              foreach ($items as $item) {
                  $cardsData[] = [
                      'front' => $item->front_content,
                      'back' => $item->back_content,
                  ];
              }
          }

          if (empty($cardsData)) {
              return redirect()->route('murid.flashcard.index')->with('error', 'Dek ini belum memiliki kartu untuk dimainkan.');
          }

          return view('murid.flashcard.show', compact('deck', 'cardsData'));
      }
  }
  ```

- [ ] **Step 2: Add student portal routes in web.php**
  Open `routes/web.php`. Inside `Route::prefix('murid')` group, add:
  ```php
  Route::get('/flashcard', [\App\Http\Controllers\Murid\FlashcardController::class, 'index'])->name('murid.flashcard.index');
  Route::get('/flashcard/{id}', [\App\Http\Controllers\Murid\FlashcardController::class, 'show'])->name('murid.flashcard.show');
  ```

- [ ] **Step 3: Create Murid portal index view**
  Create `resources/views/murid/flashcard/index.blade.php`:
  ```html
  @extends('layouts.murid')
  @section('title', 'Pembelajaran Flashcard')
  @section('content')
  <div class="space-y-6">
      <div class="bg-gradient-to-r from-emerald-800 to-teal-950 p-6 rounded-3xl text-white relative overflow-hidden shadow-md">
          <div class="absolute inset-0 pattern-islamic opacity-10"></div>
          <div class="relative z-10 space-y-1">
              <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider">Interaktif & Edukatif</span>
              <h2 class="text-xl font-extrabold">Ayo Belajar dengan Flashcard!</h2>
              <p class="text-xs text-emerald-100/75 max-w-sm">Pilih dek kartu di bawah ini untuk mulai melatih ingatan dan hafalanmu.</p>
          </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($decks as $deck)
              @php
                  $isRecommended = $deck->level_target_id == $student->current_level_id;
              @endphp
              <a href="{{ route('murid.flashcard.show', $deck->id) }}" class="block p-5 bg-white rounded-2xl border {{ $isRecommended ? 'border-amber-200 bg-amber-50/20' : 'border-gray-100' }} hover:shadow-md transition">
                  <div class="flex justify-between items-start mb-3">
                      <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-lg shadow-sm">
                          <i class="fa-solid fa-clone"></i>
                      </span>
                      @if($isRecommended)
                          <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-500 text-white uppercase tracking-wider flex items-center gap-1 shadow-sm">
                              <i class="fa-solid fa-star"></i> Untukmu
                          </span>
                      @endif
                  </div>
                  <h3 class="font-extrabold text-gray-900 text-sm">{{ $deck->nama }}</h3>
                  <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ $deck->deskripsi ?? 'Ayo klik untuk mulai latihan.' }}</p>
                  
                  <div class="border-t border-dashed border-gray-100 mt-4 pt-3 flex justify-between items-center text-[10px] text-emerald-800 font-bold">
                      <span>Mulai Belajar</span>
                      <i class="fa-solid fa-arrow-right-long text-amber-600"></i>
                  </div>
              </a>
          @endforeach
      </div>
  </div>
  @endsection
  ```

- [ ] **Step 4: Modify layouts/murid.blade.php to add Flashcard link**
  Add flashcard option in navigation bottom or sidebar for students. Open `resources/views/layouts/murid.blade.php`. In bottom navigation or side menu:
  ```html
  <a href="{{ route('murid.flashcard.index') }}" class="flex flex-col items-center justify-center text-xs font-semibold py-1 px-3 rounded-xl {{ request()->routeIs('murid.flashcard.*') ? 'text-emerald-800' : 'text-gray-400' }}">
      <i class="fa-solid fa-clone text-lg mb-0.5"></i>
      <span>Latihan</span>
  </a>
  ```

- [ ] **Step 5: Commit**
  Run: `git add app/Http/Controllers/ resources/views/ routes/` and `git commit -m "feat: add student portal flashcard index and layout links"`

---

### Task 6: Murid Portal Flashcard Play View (3D Flip UI)

**Files:**
- Create: `resources/views/murid/flashcard/show.blade.php`

**Interfaces:**
- Consumes: The show route from student flashcard controller.
- Produces: Visual card interaction with Alpine.js rotation.

- [ ] **Step 1: Create student flashcard study view**
  Create `resources/views/murid/flashcard/show.blade.php`:
  ```html
  @extends('layouts.murid')
  @section('title', 'Latihan: ' . $deck->nama)
  @section('content')
  <div class="max-w-md mx-auto px-4 py-8 space-y-6" x-data="flashcardSession()">

      <!-- Progress and Back Navigation -->
      <div class="flex items-center justify-between">
          <a href="{{ route('murid.flashcard.index') }}" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-gray-700 text-xs font-bold rounded-xl flex items-center gap-1.5 transition">
              <i class="fa-solid fa-chevron-left text-[10px]"></i> Kembali
          </a>
          <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100" x-text="`Kartu ${currentIndex + 1} dari ${cards.length}`"></span>
      </div>

      <!-- 3D Flip Card Container -->
      <div class="perspective-1000 w-full h-80 cursor-pointer" @click="flipped = !flipped">
          <div class="relative w-full h-full duration-500 transform-style-3d select-none" :class="flipped ? 'rotate-y-180' : ''">
              
              <!-- Front Face -->
              <div class="absolute inset-0 bg-white border border-emerald-50 rounded-3xl p-8 flex flex-col justify-between backface-hidden shadow-lg">
                  <div class="flex justify-between items-center text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                      <span>Sisi Depan</span>
                      <span class="text-amber-500"><i class="fa-solid fa-circle-question"></i> Pertanyaan / Arti</span>
                  </div>
                  
                  <div class="flex-1 flex items-center justify-center text-center px-4">
                      <p class="text-sm sm:text-base font-extrabold text-emerald-950 leading-relaxed whitespace-pre-line" x-text="cards[currentIndex].front"></p>
                  </div>
                  
                  <div class="text-center text-[11px] text-gray-400 font-semibold mt-4">
                      <i class="fa-solid fa-rotate mr-1 text-emerald-800"></i> Klik kartu untuk membalik
                  </div>
              </div>

              <!-- Back Face -->
              <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-8 flex flex-col justify-between backface-hidden rotate-y-180 shadow-lg relative overflow-hidden">
                  <div class="absolute inset-0 pattern-islamic opacity-10 pointer-events-none"></div>
                  
                  <div class="flex justify-between items-center text-[10px] text-emerald-300 font-bold uppercase tracking-wider relative z-10">
                      <span>Sisi Belakang</span>
                      <span class="text-amber-400"><i class="fa-solid fa-square-check"></i> Lafadz / Jawaban</span>
                  </div>
                  
                  <div class="flex-1 flex flex-col items-center justify-center text-center px-4 relative z-10 space-y-4">
                      <!-- Arabic view (Amiri font with RTL) -->
                      <p class="arabic-text text-3xl font-bold leading-loose text-amber-300 tracking-wide whitespace-pre-line" x-text="cards[currentIndex].back"></p>
                  </div>
                  
                  <div class="text-center text-[11px] text-emerald-300/60 font-semibold mt-4 relative z-10">
                      <i class="fa-solid fa-rotate mr-1 text-amber-400"></i> Klik kartu untuk membalik
                  </div>
              </div>

          </div>
      </div>

      <!-- Navigation buttons -->
      <div class="flex items-center gap-3">
          <button type="button" @click="prevCard()" :disabled="currentIndex === 0"
                  class="flex-1 py-4 bg-white border border-gray-200 hover:bg-gray-50 disabled:opacity-40 text-gray-700 text-xs font-bold rounded-2xl shadow-sm transition flex items-center justify-center gap-1.5">
              <i class="fa-solid fa-arrow-left"></i> Kartu Sebelumnya
          </button>
          <button type="button" @click="nextCard()" :disabled="currentIndex === cards.length - 1"
                  class="flex-1 py-4 bg-emerald-800 hover:bg-emerald-700 disabled:opacity-40 text-white text-xs font-bold rounded-2xl shadow-sm transition flex items-center justify-center gap-1.5">
              Kartu Berikutnya <i class="fa-solid fa-arrow-right"></i>
          </button>
      </div>

      <!-- Reset button -->
      <div class="text-center pt-2">
          <button type="button" @click="resetSession()" class="text-xs text-gray-400 hover:text-emerald-800 font-semibold transition">
              <i class="fa-solid fa-rotate-left mr-1"></i> Reset Latihan
          </button>
      </div>

  </div>
  @endsection

  @section('scripts')
  <script>
      function flashcardSession() {
          return {
              currentIndex: 0,
              flipped: false,
              cards: @json($cardsData),
              nextCard() {
                  if (this.currentIndex < this.cards.length - 1) {
                      this.flipped = false;
                      setTimeout(() => {
                          this.currentIndex++;
                      }, 200);
                  }
              },
              prevCard() {
                  if (this.currentIndex > 0) {
                      this.flipped = false;
                      setTimeout(() => {
                          this.currentIndex--;
                      }, 200);
                  }
              },
              resetSession() {
                  this.flipped = false;
                  setTimeout(() => {
                      this.currentIndex = 0;
                  }, 200);
              }
          }
      }
  </script>
  @endsection
  ```

- [ ] **Step 2: Commit**
  Run: `git add resources/views/` and `git commit -m "feat: add interactive 3D flashcard study screen for students"`

---

### Task 7: Automated Tests

**Files:**
- Create: `tests/Feature/FlashcardTest.php`

**Interfaces:**
- Consumes: PHPUnit framework and flashcard routes.
- Produces: Test verification logs.

- [ ] **Step 1: Create test file**
  Create `tests/Feature/FlashcardTest.php`:
  ```php
  <?php

  namespace Tests\Feature;

  use Tests\TestCase;
  use App\Models\Admin;
  use App\Models\User;
  use App\Models\Level;
  use App\Models\FlashcardDeck;
  use Illuminate\Foundation\Testing\RefreshDatabase;

  class FlashcardTest extends TestCase
  {
      use RefreshDatabase;

      protected function setUp(): void
      {
          parent::setUp();
          
          // Seed level
          $this->level = Level::create([
              'nama' => 'Pra-Iqra',
              'urutan' => 1,
              'deskripsi' => 'Level awal',
          ]);

          $this->admin = Admin::factory()->create([
              'role' => 'admin',
          ]);

          $this->student = User::factory()->create([
              'current_level_id' => $this->level->id,
          ]);
      }

      public function test_admin_can_access_flashcard_deck_crud()
      {
          $response = $this->actingAs($this->admin, 'admin')
              ->get(route('admin.konten.flashcard.index'));

          $response->assertStatus(200);
      }

      public function test_admin_can_create_custom_flashcard_deck_and_item()
      {
          $deckData = [
              'nama' => 'Kuis Tajwid Lengkap',
              'deskripsi' => 'Materi tajwid hukum nun sukun.',
              'source_type' => 'custom',
              'level_target_id' => $this->level->id,
              'is_active' => 1,
          ];

          $response = $this->actingAs($this->admin, 'admin')
              ->post(route('admin.konten.flashcard.store'), $deckData);

          $response->assertRedirect(route('admin.konten.flashcard.index'));
          $this->assertDatabaseHas('flashcard_decks', ['nama' => 'Kuis Tajwid Lengkap']);

          $deck = FlashcardDeck::where('nama', 'Kuis Tajwid Lengkap')->first();

          $itemData = [
              'front_content' => 'Hukum nun mati ketemu ba?',
              'back_content' => 'Iqlab',
              'urutan' => 1,
          ];

          $itemResponse = $this->actingAs($this->admin, 'admin')
              ->post(route('admin.konten.flashcard.items.store', $deck->id), $itemData);

          $itemResponse->assertStatus(302);
          $this->assertDatabaseHas('flashcard_items', ['front_content' => 'Hukum nun mati ketemu ba?']);
      }

      public function test_student_can_view_flashcard_decks()
      {
          FlashcardDeck::create([
              'nama' => 'Dek Santri',
              'deskripsi' => 'Uji kartu santri',
              'source_type' => 'custom',
              'level_target_id' => $this->level->id,
              'is_active' => true,
          ]);

          $response = $this->actingAs($this->student, 'web')
              ->get(route('murid.flashcard.index'));

          $response->assertStatus(200);
          $response->assertSee('Dek Santri');
      }
  }
  ```

- [ ] **Step 2: Run new test file**
  Run: `php artisan test --filter=FlashcardTest`
  Expected: All 3 tests passed.

- [ ] **Step 3: Run full suite**
  Run: `php artisan test`
  Expected: All tests pass.

- [ ] **Step 4: Commit and finalize**
  Run: `git add tests/` and `git commit -m "test: add integration test suite for flashcards"`
