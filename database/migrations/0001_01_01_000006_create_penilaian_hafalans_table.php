<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_hafalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_hafalan', ['surat', 'hadist', 'doa']);
            $table->string('nama_item', 150);
            $table->enum('status', ['hafal_sempurna', 'hafal_dengan_kesalahan', 'perlu_diulang']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_hafalans');
    }
};
