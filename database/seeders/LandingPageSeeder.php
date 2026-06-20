<?php

namespace Database\Seeders;

use App\Models\LandingSetting;
use App\Models\Galeri;
use App\Models\Testimoni;
use App\Models\PengurusProfile;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Landing Settings (Key-Value)
        $settings = [
            'hero_headline' => 'Membentuk Generasi Qur\'ani Sejak Dini',
            'hero_subheadline' => 'Belajar Al-Qur\'an dengan metode terstruktur, menyenangkan, dan dipantau penuh oleh orang tua secara online.',
            'tentang_kami' => 'TPQ Al-Istiqomah adalah lembaga pendidikan Al-Qur\'an tingkat dasar yang berkomitmen mendidik anak-anak agar mahir membaca, menghafal, dan mengamalkan nilai-nilai Al-Qur\'an dalam kehidupan sehari-hari.',
            'visi' => 'Menjadi TPQ unggulan dalam membentuk generasi Islam yang saleh, cerdas, berakhlak mulia, dan cinta Al-Qur\'an.',
            'misi' => [
                'Menyelenggarakan pembelajaran baca tulis Al-Qur\'an secara bertahap dan teratur.',
                'Membiasakan santri menghafal surat-surat pendek, doa harian, dan hadist pilihan.',
                'Menanamkan aqidah dan akhlakul karimah melalui pembiasaan praktik ibadah.',
                'Membina kedisiplinan dan kemandirian santri dalam suasana belajar yang menyenangkan.'
            ],
            'poin_keunggulan' => [
                [
                    'title' => 'Kurikulum Terstruktur',
                    'desc' => 'Materi dipelajari bertahap per level mulai dari Pra-Iqra hingga tingkat Al-Qur\'an.'
                ],
                [
                    'title' => 'Laporan Online',
                    'desc' => 'Orang tua dapat memantau perkembangan hafalan dan kehadiran anak secara langsung di aplikasi.'
                ],
                [
                    'title' => 'Pengajar Berpengalaman',
                    'desc' => 'Dibimbing langsung oleh Ustadz & Ustadzah yang sabar, ramah anak, dan bersertifikasi.'
                ],
                [
                    'title' => 'Suasana Menyenangkan',
                    'desc' => 'Dilengkapi kelas halaqah, kisah islami interaktif, dan kuis menarik agar anak tidak bosan.'
                ]
            ],
            'alamat' => 'Jl. Masjid Al-Istiqomah No. 45, Kebayoran Baru, Jakarta Selatan',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.273187123908!2d106.8005312!3d-6.2276709!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f14fb36f1c37%3A0x6b9d62d08a0d49b4!2sMasjid%20Al-Istiqomah!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid',
            'jam_operasional' => 'Senin - Jumat: 16.00 - 17.30 WIB',
            'no_wa' => '6281234567890',
            'no_telpon' => '0217201234',
            'email' => 'info@tpqalistiqomah.sch.id',
            'instagram_url' => 'https://instagram.com/tpqalistiqomah',
            'facebook_url' => 'https://facebook.com/tpqalistiqomah'
        ];

        foreach ($settings as $key => $value) {
            LandingSetting::setValue($key, $value);
        }

        // 2. Seed Mock Galleries if empty
        if (Galeri::count() === 0) {
            $galleries = [
                [
                    'judul' => 'Pembelajaran Iqra Individu',
                    'gambar' => '/images/default-gallery-1.webp',
                    'kategori' => 'Kegiatan Kelas',
                    'urutan' => 1,
                    'is_active' => true,
                ],
                [
                    'judul' => 'Setoran Hafalan Surat Pendek',
                    'gambar' => '/images/default-gallery-2.webp',
                    'kategori' => 'Tahfidz',
                    'urutan' => 2,
                    'is_active' => true,
                ],
                [
                    'judul' => 'Praktik Gerakan Sholat Berjamaah',
                    'gambar' => '/images/default-gallery-3.webp',
                    'kategori' => 'Ibadah',
                    'urutan' => 3,
                    'is_active' => true,
                ],
                [
                    'judul' => 'Kisah Nabi & Sahabat',
                    'gambar' => '/images/default-gallery-4.webp',
                    'kategori' => 'Halaqah',
                    'urutan' => 4,
                    'is_active' => true,
                ]
            ];

            foreach ($galleries as $gal) {
                Galeri::create($gal);
            }
        }

        // 3. Seed Mock Testimonials if empty
        if (Testimoni::count() === 0) {
            $testimonials = [
                [
                    'nama' => 'Bapak Joko Susilo',
                    'role' => 'Orang Tua dari Ahmad (Level Iqra 4)',
                    'foto' => null,
                    'isi' => 'Sangat bersyukur menyekolahkan anak di sini. Sekarang perkembangannya luar biasa, anak saya sudah hafal 15 surat pendek dan sholatnya makin tertib. Pantauan online lewat aplikasinya juga sangat membantu saya memantau dari kantor.',
                    'rating' => 5,
                    'urutan' => 1,
                    'is_active' => true,
                ],
                [
                    'nama' => 'Ibu Rahmawati',
                    'role' => 'Wali Murid dari Aisyah (Level Al-Qur\'an)',
                    'foto' => null,
                    'isi' => 'Ustadz dan Ustadzahnya sangat sabar dalam membimbing anak-anak. Metode belajarnya santai tapi terarah. Aisyah yang dulunya malu-malu sekarang jadi berani tampil hafalan di depan umum.',
                    'rating' => 5,
                    'urutan' => 2,
                    'is_active' => true,
                ],
                [
                    'nama' => 'Ibu Hartati',
                    'role' => 'Orang Tua dari Yusuf (Level Pra-Iqra)',
                    'foto' => null,
                    'isi' => 'Anak saya senang sekali berangkat mengaji karena suasananya ceria dan ada kegiatan mendongeng kisah Nabi. Sangat direkomendasikan untuk mengenalkan Quran sejak dini.',
                    'rating' => 5,
                    'urutan' => 3,
                    'is_active' => true,
                ]
            ];

            foreach ($testimonials as $test) {
                Testimoni::create($test);
            }
        }

        // 4. Seed Mock Pengurus Profiles if empty
        if (PengurusProfile::count() === 0) {
            $pengurus = [
                [
                    'nama' => 'Ustadz H. Ahmad Hambali, Lc.',
                    'jabatan' => 'Kepala Madrasah & Dewan Pembina',
                    'foto' => null,
                    'urutan' => 1,
                    'is_active' => true,
                ],
                [
                    'nama' => 'Ustadzah Siti Aminah, S.Pd.I',
                    'jabatan' => 'Koordinator Kurikulum & Pengajar',
                    'foto' => null,
                    'urutan' => 2,
                    'is_active' => true,
                ],
                [
                    'nama' => 'Ustadz Zainal Arifin',
                    'jabatan' => 'Bendahara & Pengajar Kelas Al-Qur\'an',
                    'foto' => null,
                    'urutan' => 3,
                    'is_active' => true,
                ]
            ];

            foreach ($pengurus as $peng) {
                PengurusProfile::create($peng);
            }
        }
    }
}
