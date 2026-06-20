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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nama_orang_tua', 100);
            $table->string('no_wa', 20);
            $table->text('alamat');
            $table->boolean('pernah_mengaji')->default(false);
            $table->string('level_mengaji_sebelumnya', 100)->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->enum('status', ['baru', 'dihubungi', 'diterima', 'ditolak'])->default('baru');
            $table->text('catatan_internal')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
