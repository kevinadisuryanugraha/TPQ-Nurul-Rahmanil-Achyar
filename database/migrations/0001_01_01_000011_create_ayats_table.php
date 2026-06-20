<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ayats', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('surah_id')->unsigned();
            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');
            $table->smallInteger('nomor_ayat')->unsigned();
            $table->text('teks_arab');
            $table->text('teks_latin')->nullable();
            $table->text('terjemahan');

            $table->unique(['surah_id', 'nomor_ayat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ayats');
    }
};
