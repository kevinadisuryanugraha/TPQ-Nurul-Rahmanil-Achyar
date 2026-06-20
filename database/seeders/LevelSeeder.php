<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['urutan' => 1, 'nama' => 'Pra-Iqra', 'deskripsi' => 'Pengenalan huruf hijaiyah dasar'],
            ['urutan' => 2, 'nama' => 'Iqra 1', 'deskripsi' => 'Membaca huruf berharakat fathah tunggal'],
            ['urutan' => 3, 'nama' => 'Iqra 2', 'deskripsi' => 'Membaca huruf bersambung fathah & kasrah'],
            ['urutan' => 4, 'nama' => 'Iqra 3', 'deskripsi' => 'Membaca kasrah, dammah, sukun & tanwin'],
            ['urutan' => 5, 'nama' => 'Iqra 4', 'deskripsi' => 'Membaca mad (panjang/pendek) & sukun'],
            ['urutan' => 6, 'nama' => 'Iqra 5', 'deskripsi' => 'Membaca qalqalah, tasydid, & waqaf'],
            ['urutan' => 7, 'nama' => 'Iqra 6', 'deskripsi' => 'Pengenalan tajwid dasar & tartil awal'],
            ['urutan' => 8, 'nama' => 'Al-Qur\'an', 'deskripsi' => 'Membaca Mushaf Al-Qur\'an secara tartil'],
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(
                ['urutan' => $level['urutan']],
                ['nama' => $level['nama'], 'deskripsi' => $level['deskripsi']]
            );
        }
    }
}
