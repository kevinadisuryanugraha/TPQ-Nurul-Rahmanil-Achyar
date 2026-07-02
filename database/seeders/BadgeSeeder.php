<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'nama' => 'Rajin Mengaji',
                'deskripsi' => 'Telah hadir 5 kali di sesi belajar TPQ.',
                'icon' => 'fa-solid fa-book-open text-emerald-500',
                'syarat_tipe' => 'absensi',
                'syarat_jumlah' => 5,
            ],
            [
                'nama' => 'Disiplin Santri',
                'deskripsi' => 'Telah hadir 15 kali di sesi belajar TPQ.',
                'icon' => 'fa-solid fa-calendar-check text-blue-500',
                'syarat_tipe' => 'absensi',
                'syarat_jumlah' => 15,
            ],
            [
                'nama' => 'Penghafal Pemula',
                'deskripsi' => 'Menyelesaikan latihan flashcard pertama kali.',
                'icon' => 'fa-solid fa-hands-praying text-amber-500',
                'syarat_tipe' => 'flashcard',
                'syarat_jumlah' => 1,
            ],
            [
                'nama' => 'Hafidz Cilik',
                'deskripsi' => 'Menyelesaikan 5 kali latihan flashcard.',
                'icon' => 'fa-solid fa-trophy text-yellow-500',
                'syarat_tipe' => 'flashcard',
                'syarat_jumlah' => 5,
            ],
            [
                'nama' => 'Santri Cerdas',
                'deskripsi' => 'Memiliki nilai ujian tulis rata-rata 85 atau lebih.',
                'icon' => 'fa-solid fa-brain text-purple-500',
                'syarat_tipe' => 'tulis',
                'syarat_jumlah' => 85,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['nama' => $badge['nama']], $badge);
        }
    }
}
