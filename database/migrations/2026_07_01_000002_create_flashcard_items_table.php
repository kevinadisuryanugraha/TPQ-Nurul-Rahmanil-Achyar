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
