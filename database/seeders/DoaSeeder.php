<?php

namespace Database\Seeders;

use App\Models\Doa;
use Illuminate\Database\Seeder;

class DoaSeeder extends Seeder
{
    public function run(): void
    {
        $duas = [
            [
                'judul' => 'Doa Sebelum Makan',
                'teks_arab' => 'اللَّهُمَّ بَارِكْ لَنَا فِيمَا رَزَقْتَنَا وَقِنَا عَذَابَ النَّارِ',
                'transliterasi' => 'Allahumma baariklanaa fiimaa razaqtanaa waqinaa \'adzaaban-naar.',
                'terjemahan' => 'Ya Allah, berkahilah kami pada apa yang telah Engkau rezekikan kepada kami dan jagalah kami dari siksa neraka.',
                'kategori' => 'Harian',
                'urutan' => 1,
            ],
            [
                'judul' => 'Doa Sesudah Makan',
                'teks_arab' => 'الْحَمْدُ لِلَّهِ الَّذِي أَطْعَمَنَا وَسَقَانَا وَجَعَلَنَا مُسْلِمِينَ',
                'transliterasi' => 'Alhamdu lillahil-ladzii ath\'amanaa wasaqaanaa waja\'alanaa muslimiin.',
                'terjemahan' => 'Segala puji bagi Allah yang telah memberi kami makan dan minum, serta menjadikan kami termasuk golongan orang-orang muslim.',
                'kategori' => 'Harian',
                'urutan' => 2,
            ],
            [
                'judul' => 'Doa Sebelum Tidur',
                'teks_arab' => 'بِاسْمِكَ اللَّهُمَّ أَحْيَا وَأَمُوتُ',
                'transliterasi' => 'Bismika-allahumma ahyaa wa amuutu.',
                'terjemahan' => 'Dengan nama-Mu ya Allah, aku hidup dan aku mati.',
                'kategori' => 'Harian',
                'urutan' => 3,
            ],
            [
                'judul' => 'Doa Bangun Tidur',
                'teks_arab' => 'الْحَمْدُ لِلَّهِ الَّذِي أَحْيَانَا بَعْدَ مَا أَمَاتَنَا وَإِلَيْهِ النُّشُورُ',
                'transliterasi' => 'Alhamdu lillahil-ladzii ahyaanaa ba\'da maa amaatanaa wa ilaihin-nusyuur.',
                'terjemahan' => 'Segala puji bagi Allah yang telah menghidupkan kami setelah mematikan kami (tidur) dan kepada-Nyalah kami kembali.',
                'kategori' => 'Harian',
                'urutan' => 4,
            ],
            [
                'judul' => 'Doa Masuk Kamar Mandi',
                'teks_arab' => 'اللَّهُمَّ إِنِّي أَعُوذُ بِكَ مِنَ الْخُبُثِ وَالْخَبَائِثِ',
                'transliterasi' => 'Allahumma innii a\'uudzu bika minal-khubutsi wal-khabaa\'its.',
                'terjemahan' => 'Ya Allah, sesungguhnya aku berlindung kepada-Mu dari godaan setan laki-laki dan setan perempuan.',
                'kategori' => 'Harian',
                'urutan' => 5,
            ],
            [
                'judul' => 'Doa Keluar Kamar Mandi',
                'teks_arab' => 'غُفْرَانَكَ الْحَمْدُ لِلَّهِ الَّذِي أَذْهَبَ عَنِّي الْأَذَى وَعَافَانِي',
                'transliterasi' => 'Ghufraanaka, alhamdu lillahil-ladzii adz-haba \'annil-adzaa wa \'aafaanii.',
                'terjemahan' => 'Aku memohon ampunan-Mu. Segala puji bagi Allah yang telah menghilangkan penyakit dari tubuhku dan menyehatkanku.',
                'kategori' => 'Harian',
                'urutan' => 6,
            ],
            [
                'judul' => 'Doa Sebelum Belajar',
                'teks_arab' => 'رَبِّ زِدْنِي عِلْمًا وَارْزُقْنِي فَهْمًا',
                'transliterasi' => 'Rabbi zidnii \'ilman warzuqnii fahman.',
                'terjemahan' => 'Ya Tuhanku, tambahkanlah ilmu kepadaku dan berilah aku karunia pemahaman.',
                'kategori' => 'Belajar',
                'urutan' => 1,
            ],
            [
                'judul' => 'Doa untuk Kedua Orang Tua',
                'teks_arab' => 'رَبِّ اغْفِرْ لِي وَلِوَالِدَيَّ وَارْحَمْهُمَا كَمَا رَبَّيَانِي صَغِيرًا',
                'transliterasi' => 'Rabbighfir lii waliwaalidayya warhamhumaa kamaa rabbayaanii shaghiiraa.',
                'terjemahan' => 'Ya Tuhanku, ampunilah aku dan kedua orang tuaku, dan sayangilah mereka berdua sebagaimana mereka berdua mendidikku di waktu kecil.',
                'kategori' => 'Harian',
                'urutan' => 7,
            ],
            [
                'judul' => 'Doa Bepergian (Keluar Rumah)',
                'teks_arab' => 'بِسْمِ اللَّهِ تَوَكَّلْتُ عَلَى اللَّهِ لَا حَوْلَ وَلَا قُوَّةَ إِلَّا بِاللَّهِ',
                'transliterasi' => 'Bismillaahi tawakkaltu \'alallaahi, laa haula wa laa quwwata illaa billaah.',
                'terjemahan' => 'Dengan nama Allah, aku berserah diri kepada Allah. Tiada daya dan kekuatan kecuali dengan pertolongan Allah.',
                'kategori' => 'Bepergian',
                'urutan' => 1,
            ],
            [
                'judul' => 'Doa Naik Kendaraan',
                'teks_arab' => 'سُبْحَانَ الَّذِي سَخَّرَ لَنَا هَٰذَا وَمَا كُنَّا لَهُ مُقْرِنِينَ وَإِنَّا إِلَىٰ رَبِّنَا لَمُنْقَلِبُونَ',
                'transliterasi' => 'Subhaanal-ladzii sakh-khara lanaa haadzaa wa maa kunnaa lahu muqriniin, wa innaa ilaa rabbinaa lamunqalibuun.',
                'terjemahan' => 'Maha Suci Allah yang telah menundukkan semua ini bagi kami padahal kami sebelumnya tidak mampu menguasainya, dan sesungguhnya kami akan kembali kepada Tuhan kami.',
                'kategori' => 'Bepergian',
                'urutan' => 2,
            ],
            [
                'judul' => 'Doa Masuk Masjid',
                'teks_arab' => 'اللَّهُمَّ افْتَحْ لِي أَبْوَابَ رَحْمَتِكَ',
                'transliterasi' => 'Allahummaf-tah lii abwaaba rahmatik.',
                'terjemahan' => 'Ya Allah, bukakanlah bagiku pintu-pintu rahmat-Mu.',
                'kategori' => 'Masjid',
                'urutan' => 1,
            ],
            [
                'judul' => 'Doa Keluar Masjid',
                'teks_arab' => 'اللَّهُمَّ إِنِّي أَسْأَلُكَ مِنْ فَضْلِكَ',
                'transliterasi' => 'Allahumma innii as\'aluka min fadhlik.',
                'terjemahan' => 'Ya Allah, sesungguhnya aku memohon keutamaan dari-Mu.',
                'kategori' => 'Masjid',
                'urutan' => 2,
            ],
            [
                'judul' => 'Doa Kebaikan Dunia Akhirat (Sapu Jagad)',
                'teks_arab' => 'رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الْآخِرَةِ حَسَنَةً وَقِنَا عَذَابَ النَّارِ',
                'transliterasi' => 'Rabbanaa aatinaa fid-dunyaa hasanatan wa fil-aakhirati hasanatan waqinaa \'adzaaban-naar.',
                'terjemahan' => 'Ya Tuhan kami, berilah kami kebaikan di dunia dan kebaikan di akhirat, dan lindungilah kami dari azab neraka.',
                'kategori' => 'Keselamatan',
                'urutan' => 1,
            ],
        ];

        foreach ($duas as $dua) {
            Doa::updateOrCreate(
                ['judul' => $dua['judul']],
                [
                    'teks_arab' => $dua['teks_arab'],
                    'transliterasi' => $dua['transliterasi'],
                    'terjemahan' => $dua['terjemahan'],
                    'kategori' => $dua['kategori'],
                    'is_active' => true,
                    'urutan' => $dua['urutan'],
                ]
            );
        }
    }
}
