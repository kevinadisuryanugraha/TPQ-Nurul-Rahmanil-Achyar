<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboard;
use App\Http\Controllers\Superadmin\AdminController as SuperadminAdmin;
use App\Http\Controllers\Superadmin\SettingController as SuperadminSetting;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MuridController as AdminMurid;
use App\Http\Controllers\Admin\LevelController as AdminLevel;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensi;
use App\Http\Controllers\Admin\PenilaianController as AdminPenilaian;
use App\Http\Controllers\Admin\KontenController as AdminKonten;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumuman;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;

use App\Http\Controllers\Murid\DashboardController as MuridDashboard;
use App\Http\Controllers\Murid\QuranController as MuridQuran;
use App\Http\Controllers\Murid\DoaController as MuridDoa;
use App\Http\Controllers\Murid\HadistController as MuridHadist;
use App\Http\Controllers\Murid\CeritaController as MuridCerita;
use App\Http\Controllers\Murid\PanduanController as MuridPanduan;
use App\Http\Controllers\Murid\NilaiController as MuridNilai;
use App\Http\Controllers\Murid\AbsensiController as MuridAbsensi;
use App\Http\Controllers\Murid\AsmaulHusnaController as MuridAsmaulHusna;
use App\Http\Controllers\Murid\PengumumanController as MuridPengumuman;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\PendaftaranController;
use App\Http\Controllers\Admin\Landing\PendaftaranController as AdminLandingPendaftaran;
use App\Http\Controllers\Admin\Landing\GaleriController as AdminLandingGaleri;
use App\Http\Controllers\Admin\Landing\TestimoniController as AdminLandingTestimoni;
use App\Http\Controllers\Admin\Landing\PengurusProfileController as AdminLandingPengurus;
use App\Http\Controllers\Admin\Landing\LandingSettingController as AdminLandingSetting;

use Illuminate\Support\Facades\Route;

// Public Routes (Accessible by everyone)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/daftar', [PendaftaranController::class, 'create'])->name('daftar.create');
Route::post('/daftar', [PendaftaranController::class, 'store'])->name('daftar.store');
Route::get('/daftar/terima-kasih', [PendaftaranController::class, 'thankyou'])->name('daftar.thankyou');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Offline Fallback Route
Route::view('/offline', 'offline')->name('offline');

// Superadmin Route Group
Route::prefix('superadmin')->middleware(['auth:admin', 'superadmin'])->group(function () {
    Route::get('/dashboard', [SuperadminDashboard::class, 'index'])->name('superadmin.dashboard');
    Route::resource('/admins', SuperadminAdmin::class)->names('superadmin.admins');
    Route::post('/admins/{id}/reset-password', [SuperadminAdmin::class, 'resetPassword'])->name('superadmin.admins.reset-password');
    Route::get('/settings', [SuperadminSetting::class, 'index'])->name('superadmin.settings');
    Route::put('/settings', [SuperadminSetting::class, 'update'])->name('superadmin.settings.update');
});

// Admin Route Group
Route::prefix('admin')->middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    
    // Murid Management
    Route::resource('/murid', AdminMurid::class)->names('admin.murid');
    Route::post('/murid/{user}/reset-password', [AdminMurid::class, 'resetPassword'])->name('admin.murid.reset-password');
    Route::get('/level', [AdminLevel::class, 'index'])->name('admin.level.index');
    Route::post('/murid/{user}/naik-level', [AdminLevel::class, 'naikLevel'])->name('admin.murid.naik-level');
    Route::post('/murid/{user}/turun-level', [AdminLevel::class, 'turunLevel'])->name('admin.murid.turun-level');
    
    // Absensi (Attendance)
    Route::resource('/absensi', AdminAbsensi::class)->names('admin.absensi');
    Route::get('/absensi-rekap', [AdminAbsensi::class, 'rekap'])->name('admin.absensi.rekap');
    
    // Penilaian (Scoring 4-domains)
    Route::get('/penilaian', [AdminPenilaian::class, 'index'])->name('admin.penilaian.index');
    Route::get('/penilaian/baca', [AdminPenilaian::class, 'baca'])->name('admin.penilaian.baca');
    Route::post('/penilaian/baca', [AdminPenilaian::class, 'storeBaca'])->name('admin.penilaian.baca.store');
    Route::delete('/penilaian/baca/{id}', [AdminPenilaian::class, 'deleteBaca'])->name('admin.penilaian.baca.delete');
    
    Route::get('/penilaian/hafalan', [AdminPenilaian::class, 'hafalan'])->name('admin.penilaian.hafalan');
    Route::post('/penilaian/hafalan', [AdminPenilaian::class, 'storeHafalan'])->name('admin.penilaian.hafalan.store');
    Route::delete('/penilaian/hafalan/{id}', [AdminPenilaian::class, 'deleteHafalan'])->name('admin.penilaian.hafalan.delete');
    
    Route::get('/penilaian/tulis', [AdminPenilaian::class, 'tulis'])->name('admin.penilaian.tulis');
    Route::post('/penilaian/tulis', [AdminPenilaian::class, 'storeTulis'])->name('admin.penilaian.tulis.store');
    Route::delete('/penilaian/tulis/{id}', [AdminPenilaian::class, 'deleteTulis'])->name('admin.penilaian.tulis.delete');
    
    Route::get('/penilaian/praktik', [AdminPenilaian::class, 'praktik'])->name('admin.penilaian.praktik');
    Route::post('/penilaian/praktik', [AdminPenilaian::class, 'storePraktik'])->name('admin.penilaian.praktik.store');
    Route::delete('/penilaian/praktik/{id}', [AdminPenilaian::class, 'deletePraktik'])->name('admin.penilaian.praktik.delete');
    
    // Konten (Doa, Hadist, Cerita, Panduan)
    Route::get('/konten/doa', [AdminKonten::class, 'doaIndex'])->name('admin.konten.doa.index');
    Route::post('/konten/doa', [AdminKonten::class, 'doaStore'])->name('admin.konten.doa.store');
    Route::put('/konten/doa/{id}', [AdminKonten::class, 'doaUpdate'])->name('admin.konten.doa.update');
    Route::delete('/konten/doa/{id}', [AdminKonten::class, 'doaDestroy'])->name('admin.konten.doa.destroy');

    Route::get('/konten/hadist', [AdminKonten::class, 'hadistIndex'])->name('admin.konten.hadist.index');
    Route::post('/konten/hadist', [AdminKonten::class, 'hadistStore'])->name('admin.konten.hadist.store');
    Route::put('/konten/hadist/{id}', [AdminKonten::class, 'hadistUpdate'])->name('admin.konten.hadist.update');
    Route::delete('/konten/hadist/{id}', [AdminKonten::class, 'hadistDestroy'])->name('admin.konten.hadist.destroy');

    Route::resource('/konten/cerita', AdminKonten::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names([
        'index' => 'admin.konten.cerita.index',
        'create' => 'admin.konten.cerita.create',
        'store' => 'admin.konten.cerita.store',
        'edit' => 'admin.konten.cerita.edit',
        'update' => 'admin.konten.cerita.update',
        'destroy' => 'admin.konten.cerita.destroy',
    ]);
    
    Route::resource('/konten/panduan', AdminKonten::class)->only(['show'])->names([
        'show' => 'admin.konten.panduan.show',
    ]);
    // Custom routes for Panduan (due to step-by-step logic)
    Route::get('/konten/panduan-praktik', [AdminKonten::class, 'panduanIndex'])->name('admin.konten.panduan.index');
    Route::get('/konten/panduan-praktik/create', [AdminKonten::class, 'panduanCreate'])->name('admin.konten.panduan.create');
    Route::post('/konten/panduan-praktik', [AdminKonten::class, 'panduanStore'])->name('admin.konten.panduan.store');
    Route::get('/konten/panduan-praktik/{id}/edit', [AdminKonten::class, 'panduanEdit'])->name('admin.konten.panduan.edit');
    Route::put('/konten/panduan-praktik/{id}', [AdminKonten::class, 'panduanUpdate'])->name('admin.konten.panduan.update');
    Route::delete('/konten/panduan-praktik/{id}', [AdminKonten::class, 'panduanDestroy'])->name('admin.konten.panduan.destroy');
    
    // Langkah Panduan Praktik
    Route::post('/konten/panduan-praktik/{id}/langkah', [AdminKonten::class, 'langkahStore'])->name('admin.konten.langkah.store');
    Route::put('/konten/langkah/{id}', [AdminKonten::class, 'langkahUpdate'])->name('admin.konten.langkah.update');
    Route::delete('/konten/langkah/{id}', [AdminKonten::class, 'langkahDestroy'])->name('admin.konten.langkah.destroy');

    // Flashcard CMS management routes
    Route::resource('/konten/flashcard', \App\Http\Controllers\Admin\FlashcardController::class)->names('admin.konten.flashcard');
    Route::get('/konten/flashcard/{deck_id}/item', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsIndex'])->name('admin.konten.flashcard.items.index');
    Route::post('/konten/flashcard/{deck_id}/item', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsStore'])->name('admin.konten.flashcard.items.store');
    Route::put('/konten/flashcard/item/{item_id}', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsUpdate'])->name('admin.konten.flashcard.items.update');
    Route::delete('/konten/flashcard/item/{item_id}', [\App\Http\Controllers\Admin\FlashcardController::class, 'itemsDestroy'])->name('admin.konten.flashcard.items.destroy');

    // Announcement
    Route::resource('/pengumuman', AdminPengumuman::class)->names('admin.pengumuman');

    // Laporan & Export
    Route::get('/laporan', [AdminLaporan::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/murid', [AdminLaporan::class, 'murid'])->name('admin.laporan.murid');
    Route::get('/laporan/export-pdf', [AdminLaporan::class, 'exportPdf'])->name('admin.laporan.export-pdf');
    Route::get('/laporan/export-excel-murid', [AdminLaporan::class, 'exportExcelMurid'])->name('admin.laporan.export-excel-murid');
    Route::get('/laporan/export-excel-kelas', [AdminLaporan::class, 'exportExcelKelas'])->name('admin.laporan.export-excel-kelas');

    // Landing Page CMS Route Group
    Route::prefix('landing')->name('admin.landing.')->group(function () {
        Route::get('/pendaftaran', [AdminLandingPendaftaran::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/{pendaftar}', [AdminLandingPendaftaran::class, 'show'])->name('pendaftaran.show');
        Route::patch('/pendaftaran/{pendaftar}/status', [AdminLandingPendaftaran::class, 'updateStatus'])->name('pendaftaran.update-status');
        Route::get('/pendaftaran/{pendaftar}/terima', [AdminLandingPendaftaran::class, 'terimaForm'])->name('pendaftaran.terima');
        
        Route::resource('/galeri', AdminLandingGaleri::class)->except(['show'])->names('galeri');
        Route::resource('/testimoni', AdminLandingTestimoni::class)->except(['show'])->names('testimoni');
        Route::resource('/pengurus', AdminLandingPengurus::class)->except(['show'])->names('pengurus');
        
        Route::get('/pengaturan', [AdminLandingSetting::class, 'edit'])->name('pengaturan.edit');
        Route::put('/pengaturan', [AdminLandingSetting::class, 'update'])->name('pengaturan.update');
    });
});

// Murid (Student) Route Group
Route::prefix('murid')->middleware(['auth:web', 'murid'])->group(function () {
    Route::get('/dashboard', [MuridDashboard::class, 'index'])->name('murid.dashboard');
    Route::get('/quran', [MuridQuran::class, 'index'])->name('murid.quran.index');
    Route::get('/quran/{id}', [MuridQuran::class, 'show'])->name('murid.quran.show');
    Route::get('/doa', [MuridDoa::class, 'index'])->name('murid.doa.index');
    Route::get('/hadist', [MuridHadist::class, 'index'])->name('murid.hadist.index');
    Route::get('/cerita', [MuridCerita::class, 'index'])->name('murid.cerita.index');
    Route::get('/cerita/{id}', [MuridCerita::class, 'show'])->name('murid.cerita.show');
    Route::get('/panduan', [MuridPanduan::class, 'index'])->name('murid.panduan.index');
    Route::get('/panduan/{id}', [MuridPanduan::class, 'show'])->name('murid.panduan.show');
    Route::get('/nilai', [MuridNilai::class, 'index'])->name('murid.nilai.index');
    Route::get('/absensi', [MuridAbsensi::class, 'index'])->name('murid.absensi.index');
    Route::get('/asmaul-husna', [MuridAsmaulHusna::class, 'index'])->name('murid.asmaul-husna.index');
    Route::get('/pengumuman', [MuridPengumuman::class, 'index'])->name('murid.pengumuman.index');
    Route::get('/flashcard', [\App\Http\Controllers\Murid\FlashcardController::class, 'index'])->name('murid.flashcard.index');
    Route::get('/flashcard/{id}', [\App\Http\Controllers\Murid\FlashcardController::class, 'show'])->name('murid.flashcard.show');
});
