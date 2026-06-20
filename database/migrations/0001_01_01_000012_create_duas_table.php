<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('teks_arab');
            $table->text('transliterasi');
            $table->text('terjemahan');
            $table->string('kategori', 100);
            $table->boolean('is_active')->default(true);
            $table->smallInteger('urutan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duas');
    }
};
