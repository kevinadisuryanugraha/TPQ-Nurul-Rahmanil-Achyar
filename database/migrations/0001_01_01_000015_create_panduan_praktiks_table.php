<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panduan_praktiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('judul', 255);
            $table->string('cover_image', 255)->nullable();
            $table->text('deskripsi');
            $table->string('jenis_praktik', 100);
            $table->foreignId('level_target_id')->nullable()->constrained('levels')->onDelete('set null');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panduan_praktiks');
    }
};
