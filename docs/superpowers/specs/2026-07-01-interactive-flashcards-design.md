# SPECIFICATION DESIGN: Interactive Flashcards

## 1. Metadata
*   **Target Release**: Juli 2026
*   **Status**: Approved
*   **Author**: Antigravity & Kevin Adi Surya Nugraha
*   **Scope**: MVP Pembelajaran Interaktif (Flashcard)

---

## 2. Project Overview & Objectives
Tujuan dari fitur ini adalah menyediakan media belajar mandiri yang seru, interaktif, dan edukatif bagi santri (murid) untuk menghafal doa, hadits, arti surat pendek, dan materi kustom buatan pengurus (admin). 

Sistem akan menggunakan kombinasi data otomatis (di-convert langsung dari data yang sudah ada di database) dan data kustom (yang dimasukkan manual oleh admin).

---

## 3. Database Schema

### 3.1 `flashcard_decks` (Tabel Dek/Koleksi)
```php
Schema::create('flashcard_decks', function (Blueprint $table) {
    $table->id();
    $table->string('nama', 100);
    $table->string('deskripsi', 255)->nullable();
    $table->enum('source_type', ['system_doa', 'system_hadist', 'system_quran', 'custom'])->default('custom');
    $table->foreignId('level_target_id')->nullable()->constrained('levels')->nullOnDelete();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 3.2 `flashcard_items` (Tabel Kartu Kustom)
```php
Schema::create('flashcard_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('deck_id')->constrained('flashcard_decks')->cascadeOnDelete();
    $table->text('front_content');
    $table->text('back_content');
    $table->integer('urutan')->default(0);
    $table->timestamps();
});
```

---

## 4. Models & Relationships

### 4.1 `App\Models\FlashcardDeck`
```php
class FlashcardDeck extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'source_type', 'level_target_id', 'is_active'];

    public function items()
    {
        return $this->hasMany(FlashcardItem::class, 'deck_id')->orderBy('urutan');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_target_id');
    }
}
```

### 4.2 `App\Models\FlashcardItem`
```php
class FlashcardItem extends Model
{
    protected $fillable = ['deck_id', 'front_content', 'back_content', 'urutan'];

    public function deck()
    {
        return $this->belongsTo(FlashcardDeck::class, 'deck_id');
    }
}
```

---

## 5. Admin Panel CRUD Interface

### 5.1 Rute Admin (`routes/web.php`)
```php
Route::prefix('admin')->middleware(['auth:admin', 'admin'])->group(function () {
    Route::resource('/konten/flashcard', AdminFlashcardController::class)->names('admin.konten.flashcard');
    Route::get('/konten/flashcard/{deck_id}/item', [AdminFlashcardController::class, 'itemsIndex'])->name('admin.konten.flashcard.items.index');
    Route::post('/konten/flashcard/{deck_id}/item', [AdminFlashcardController::class, 'itemsStore'])->name('admin.konten.flashcard.items.store');
    Route::put('/konten/flashcard/item/{item_id}', [AdminFlashcardController::class, 'itemsUpdate'])->name('admin.konten.flashcard.items.update');
    Route::delete('/konten/flashcard/item/{item_id}', [AdminFlashcardController::class, 'itemsDestroy'])->name('admin.konten.flashcard.items.destroy');
});
```

### 5.2 `AdminFlashcardController` Logic Rules
1. **Pencegahan Edit Kartu Sistem**: Ketika `source_type !== 'custom'`, admin tidak diperkenankan mengakses halaman pengisian item kartu (`itemsIndex`) atau men-submit item baru ke dek tersebut. Sisi frontend akan menyembunyikan tombol kelola kartu untuk tipe ini.
2. **Metadata Editable**: Semua tipe dek (baik sistem maupun kustom) tetap bisa diubah namanya, deskripsinya, status aktifnya (`is_active`), dan level rekomendasinya (`level_target_id`).

---

## 6. Portal Murid Antarmuka Interaktif

### 6.1 Rute Murid (`routes/web.php`)
```php
Route::prefix('murid')->middleware(['auth:web', 'murid'])->group(function () {
    Route::get('/flashcard', [MuridFlashcardController::class, 'index'])->name('murid.flashcard.index');
    Route::get('/flashcard/{id}', [MuridFlashcardController::class, 'show'])->name('murid.flashcard.show');
});
```

### 6.2 Konversi Data Otomatis di `MuridFlashcardController@show`
Controller akan merestrukturisasi data yang dikirim ke view menjadi format JSON standar berikut:
```json
[
  { "front": "Judul/Teks Depan", "back": "Lafadz Arab/Teks Belakang" }
]
```

Aturan konversi data bawaan sistem:
*   **`system_doa`**:
    *   `front`: `"Membaca Doa: " + judul + "\n\nArti:\n" + terjemahan`
    *   `back`: Teks Arab (Amiri font) + Transliterasi Latin
*   **`system_hadist`**:
    *   `front`: `"Arti Hadits:\n" + terjemahan + "\n\nSumber: " + sumber_kitab`
    *   `back`: Teks Arab Hadits
*   **`system_quran`**:
    *   Pemuatan surah/ayat berdasarkan rekomendasi level santri.
    *   `front`: `"Surat " + nama_surah + " Ayat " + nomor_ayat + "\n\nArti:\n" + terjemahan`
    *   `back`: Teks Arab Ayat (Uthmani/Amiri font)

### 6.3 Interaksi Sisi Depan (Blade + Tailwind + Alpine.js)
*   **Layout 3D Flip**:
    ```html
    <div class="perspective-1000 w-full h-80 cursor-pointer" @click="flipped = !flipped">
        <div class="relative w-full h-full duration-500 transform-style-3d" :class="flipped ? 'rotate-y-180' : ''">
            <!-- Front Card Face -->
            <div class="absolute inset-0 bg-white border border-gray-100 rounded-3xl p-8 flex flex-col justify-between backface-hidden shadow-md">
                ...
            </div>
            <!-- Back Card Face (Rotated 180deg) -->
            <div class="absolute inset-0 bg-emerald-900 text-white rounded-3xl p-8 flex flex-col justify-center items-center backface-hidden rotate-y-180 shadow-md">
                ...
            </div>
        </div>
    </div>
    ```
*   **Reset on Navigasi**: Ketika murid mengklik tombol "Sebelumnya" atau "Berikutnya", status `flipped` wajib diubah ke `false` terlebih dahulu untuk menghindari visual bug kartu belakang yang langsung terlihat.

---

## 7. Rencana Pengujian (Testing Plan)
1. **Automated Feature Test**:
   *   `tests/Feature/FlashcardTest.php` dibuat untuk memvalidasi hak akses Admin dan Murid.
   *   Memastikan data Doa/Hadits otomatis terkonversi dengan benar di view murid.
   *   Memastikan reCAPTCHA/honeypot dan policy route aman.
2. **Manual Test Cases**:
   *   Verifikasi structural hierarchy font Amiri pada mobile browser (RTL alignment).
   *   Verifikasi kelancaran transisi 3D Flip di berbagai browser (Safari, Chrome Mobile).
