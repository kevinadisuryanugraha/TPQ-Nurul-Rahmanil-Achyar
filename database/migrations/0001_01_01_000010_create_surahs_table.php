<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surahs', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->primary(); // 1-114
            $table->string('nama_arab', 100);
            $table->string('nama_latin', 100);
            $table->string('nama_indonesia', 100);
            $table->string('arti', 200)->nullable();
            $table->enum('tempat_turun', ['makkah', 'madinah']);
            $table->smallInteger('jumlah_ayat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};
