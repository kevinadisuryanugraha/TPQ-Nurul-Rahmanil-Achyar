<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langkah_panduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panduan_praktik_id')->constrained('panduan_praktiks')->onDelete('cascade');
            $table->tinyInteger('nomor_urut')->unsigned();
            $table->string('judul_langkah', 255);
            $table->text('deskripsi');
            $table->string('gambar', 255)->nullable();

            $table->unique(['panduan_praktik_id', 'nomor_urut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langkah_panduans');
    }
};
