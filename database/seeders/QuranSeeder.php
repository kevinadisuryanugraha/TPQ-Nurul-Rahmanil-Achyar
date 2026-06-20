<?php

namespace Database\Seeders;

use App\Models\Surah;
use App\Models\Ayat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class QuranSeeder extends Seeder
{
    public function run(): void
    {
        $dataPath = database_path('data');
        if (!file_exists($dataPath)) {
            mkdir($dataPath, 0755, true);
        }

        $jsonFile = $dataPath . '/quran_id.json';

        if (!file_exists($jsonFile)) {
            $this->command->info('Downloading Quran JSON from CDN...');
            // Increase timeout as the file is about 2.5MB
            $response = Http::timeout(60)->get('https://cdn.jsdelivr.net/npm/quran-json@3.1.2/dist/quran_id.json');
            if ($response->successful()) {
                file_put_contents($jsonFile, $response->body());
            } else {
                $this->command->error('Failed to download Quran data. Seeder aborted.');
                return;
            }
        }

        $this->command->info('Seeding Quran data to database...');
        $quran = json_decode(file_get_contents($jsonFile), true);

        foreach ($quran as $surahData) {
            $surah = Surah::updateOrCreate(
                ['id' => $surahData['id']],
                [
                    'nama_arab' => $surahData['name'],
                    'nama_latin' => $surahData['transliteration'],
                    'nama_indonesia' => $surahData['translation'] ?? $surahData['transliteration'],
                    'arti' => $surahData['translation'] ?? '',
                    'tempat_turun' => $surahData['type'] === 'meccan' ? 'makkah' : 'madinah',
                    'jumlah_ayat' => $surahData['total_verses'],
                ]
            );

            $ayats = [];
            foreach ($surahData['verses'] as $verse) {
                $ayats[] = [
                    'surah_id' => $surah->id,
                    'nomor_ayat' => $verse['id'],
                    'teks_arab' => $verse['text'],
                    'teks_latin' => null,
                    'terjemahan' => $verse['translation'],
                ];
            }

            Ayat::where('surah_id', $surah->id)->delete();
            Ayat::insert($ayats);
        }
        $this->command->info('Quran data seeded successfully!');
    }
}
