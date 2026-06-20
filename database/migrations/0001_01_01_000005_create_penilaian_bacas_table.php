<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_bacas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_bacaan', ['iqra', 'alquran', 'tilawah']);
            $table->tinyInteger('jilid_juz')->nullable()->unsigned();
            $table->smallInteger('halaman_ayat')->nullable()->unsigned();
            $table->string('keterangan_posisi', 100)->nullable();
            $table->enum('kelancaran', ['lancar', 'cukup', 'perlu_latihan']);
            $table->text('catatan_tajwid')->nullable();
            $table->text('catatan_umum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_bacas');
    }
};
