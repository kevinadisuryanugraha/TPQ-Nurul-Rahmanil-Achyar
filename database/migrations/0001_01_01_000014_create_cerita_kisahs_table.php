<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cerita_kisahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('judul', 255);
            $table->string('thumbnail', 255)->nullable();
            $table->longText('konten');
            $table->enum('kategori', ['kisah_nabi', 'kisah_sahabat', 'islami_lainnya']);
            $table->foreignId('level_target_id')->nullable()->constrained('levels')->onDelete('set null');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cerita_kisahs');
    }
};
