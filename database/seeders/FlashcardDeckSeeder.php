<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlashcardDeckSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('flashcard_decks')->insert([
            [
                'nama' => 'Doa Harian Santri',
                'deskripsi' => 'Kumpulan doa harian untuk aktivitas sehari-hari.',
                'source_type' => 'system_doa',
                'level_target_id' => 1, // Pra-Iqra
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Hadits Akhlak Mulia',
                'deskripsi' => 'Kumpulan hadits pendek mengenai adab dan perilaku terpuji.',
                'source_type' => 'system_hadist',
                'level_target_id' => 2, // Iqra 1
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
