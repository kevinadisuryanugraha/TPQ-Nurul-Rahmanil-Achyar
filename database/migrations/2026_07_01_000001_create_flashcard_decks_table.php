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
