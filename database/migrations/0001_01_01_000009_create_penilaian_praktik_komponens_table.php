<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_praktik_komponens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_praktik_id')->constrained('penilaian_praktiks')->onDelete('cascade');
            $table->string('nama_komponen', 100);
            $table->boolean('is_terpenuhi')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_praktik_komponens');
    }
};
