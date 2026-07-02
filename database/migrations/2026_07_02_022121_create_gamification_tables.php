<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add points column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('is_active');
        });

        // 2. Create badges table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('deskripsi');
            $table->string('icon');
            $table->string('syarat_tipe'); // absensi, flashcard, tulis
            $table->integer('syarat_jumlah');
            $table->timestamps();
        });

        // 3. Create user_badges pivot table
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
