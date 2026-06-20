<?php

namespace Database\Seeders;

use App\Models\Hadist;
use Illuminate\Database\Seeder;

class HadistSeeder extends Seeder
{
    public function run(): void
    {
        $hadiths = [
            [
                'teks_arab' => 'إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ',
                'terjemahan' => 'Sesungguhnya setiap amal perbuatan itu tergantung pada niatnya.',
                'sumber_kitab' => 'HR. Bukhari & Muslim',
                'perawi' => 'Umar bin Khattab',
                'kategori' => 'Adab',
            ],
            [
                'teks_arab' => 'الطُّهُورُ شَطْرُ الْإِيمَانِ',
                'terjemahan' => 'Kesucian/kebersihan itu adalah sebagian dari iman.',
                'sumber_kitab' => 'HR. Muslim',
                'perawi' => 'Abu Malik al-Asy\'ari',
                'kategori' => 'Kebersihan',
            ],
            [
                'teks_arab' => 'طَلَبُ الْعِلْمِ فَرِيضَةٌ عَلَى كُلِّ مُسْلِمٍ',
                'terjemahan' => 'Menuntut ilmu itu wajib bagi setiap muslim.',
                'sumber_kitab' => 'HR. Ibnu Majah',
                'perawi' => 'Anas bin Malik',
                'kategori' => 'Ilmu',
            ],
            [
                'teks_arab' => 'مَنْ لَا يَرْحَمْ لَا يُرْحَمْ',
                'terjemahan' => 'Barangsiapa yang tidak menyayangi, niscaya tidak akan disayangi.',
                'sumber_kitab' => 'HR. Bukhari',
                'perawi' => 'Abu Hurairah',
                'kategori' => 'Akhlak',
            ],
            [
                'teks_arab' => 'تَبَسُّمُكَ فِي وَجْهِ أَخِيكَ لَكَ صَدَقَةٌ',
                'terjemahan' => 'Senyummu di hadapan saudaramu adalah sedekah bagimu.',
                'sumber_kitab' => 'HR. Tirmidzi',
                'perawi' => 'Abu Dzar',
                'kategori' => 'Akhlak',
            ],
            [
                'teks_arab' => 'مَنْ كَانَ يُؤْمِنُ بِاللَّهِ وَالْيَوْمِ الْآخِرِ فَلْيَقُلْ خَيْرًا أَوْ لِيَصْمُتْ',
                'terjemahan' => 'Barangsiapa yang beriman kepada Allah dan hari akhir, hendaklah dia berkata yang baik atau diam.',
                'sumber_kitab' => 'HR. Bukhari & Muslim',
                'perawi' => 'Abu Hurairah',
                'kategori' => 'Lisan',
            ],
            [
                'teks_arab' => 'إِنَّ اللَّهَ جَمِيلٌ يُحِبُّ الْجَمَالَ',
                'terjemahan' => 'Sesungguhnya Allah itu Maha Indah dan menyukai keindahan.',
                'sumber_kitab' => 'HR. Muslim',
                'perawi' => 'Abdullah bin Mas\'ud',
                'kategori' => 'Akhlak',
            ],
            [
                'teks_arab' => 'يَا غُلَامُ، سَمِّ اللَّهَ، وَكُلْ بِيَمِينِكَ، وَكُلْ مِمَّا يَلِيكَ',
                'terjemahan' => 'Wahai anak muda, sebutlah nama Allah (bacalah Bismillah), makanlah dengan tangan kananmu, dan makanlah makanan yang paling dekat denganmu.',
                'sumber_kitab' => 'HR. Bukhari & Muslim',
                'perawi' => 'Omar bin Abi Salamah',
                'kategori' => 'Adab',
            ],
            [
                'teks_arab' => 'لَا تَغْضَبْ وَلَكَ الْجَنَّةُ',
                'terjemahan' => 'Janganlah kamu marah, maka bagimu adalah surga.',
                'sumber_kitab' => 'HR. Thabrani',
                'perawi' => 'Abu Darda',
                'kategori' => 'Akhlak',
            ],
            [
                'teks_arab' => 'تَهَادَوْا تَحَابُّوا',
                'terjemahan' => 'Saling memberi hadiah-lah kalian, niscaya kalian akan saling mencintai.',
                'sumber_kitab' => 'HR. Bukhari (Al-Adab Al-Mufrad)',
                'perawi' => 'Abu Hurairah',
                'kategori' => 'Akhlak',
            ],
        ];

        foreach ($hadiths as $hadith) {
            Hadist::create([
                'teks_arab' => $hadith['teks_arab'],
                'terjemahan' => $hadith['terjemahan'],
                'sumber_kitab' => $hadith['sumber_kitab'],
                'perawi' => $hadith['perawi'],
                'kategori' => $hadith['kategori'],
                'is_active' => true,
            ]);
        }
    }
}
