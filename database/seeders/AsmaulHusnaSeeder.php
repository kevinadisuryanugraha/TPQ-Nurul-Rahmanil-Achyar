<?php

namespace Database\Seeders;

use App\Models\AsmaulHusna;
use Illuminate\Database\Seeder;

class AsmaulHusnaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            [
                'urutan' => 1,
                'arab' => 'الرحمن',
                'latin' => 'Ar-Rahman',
                'arti' => 'Yang Maha Pengasih',
                'deskripsi' => 'Mempunyai kasih sayang yang sangat luas, meliputi seluruh makhluk-Nya di dunia baik yang beriman maupun yang kafir.'
            ],
            [
                'urutan' => 2,
                'arab' => 'الرحيم',
                'latin' => 'Ar-Rahim',
                'arti' => 'Yang Maha Penyayang',
                'deskripsi' => 'Mempunyai kasih sayang yang khusus dan kekal bagi hamba-hamba-Nya yang beriman di akhirat kelak.'
            ],
            [
                'urutan' => 3,
                'arab' => 'الملك',
                'latin' => 'Al-Malik',
                'arti' => 'Yang Maha Merajai',
                'deskripsi' => 'Maha Menguasai segala sesuatu, pemilik mutlak seluruh alam semesta beserta isinya tanpa ada sekutu.'
            ],
            [
                'urutan' => 4,
                'arab' => 'القدوس',
                'latin' => 'Al-Quddus',
                'arti' => 'Yang Maha Suci',
                'deskripsi' => 'Maha Bersih dan Suci dari segala macam kekurangan, cacat, kesalahan, maupun keserupaan dengan makhluk-Nya.'
            ],
            [
                'urutan' => 5,
                'arab' => 'السلام',
                'latin' => 'As-Salam',
                'arti' => 'Yang Maha Memberi Kesejahteraan',
                'deskripsi' => 'Maha Pemberi keselamatan, kedamaian, dan kesejahteraan bagi seluruh makhluk-Nya.'
            ],
            [
                'urutan' => 6,
                'arab' => 'المؤمن',
                'latin' => 'Al-Mu\'min',
                'arti' => 'Yang Maha Memberi Keamanan',
                'deskripsi' => 'Maha Pemberi keamanan dan ketenteraman di dalam hati hamba-hamba-Nya, serta pemegang janji yang terpercaya.'
            ],
            [
                'urutan' => 7,
                'arab' => 'المهيمن',
                'latin' => 'Al-Muhaimin',
                'arti' => 'Yang Maha Pemelihara',
                'deskripsi' => 'Maha Mengawasi, menjaga, dan memelihara seluruh urusan makhluk-Nya secara detail dan teliti.'
            ],
            [
                'urutan' => 8,
                'arab' => 'العزيز',
                'latin' => 'Al-Aziz',
                'arti' => 'Yang Maha Perkasa',
                'deskripsi' => 'Maha Perkasa, gagah, memiliki kekuatan mutlak yang tidak dapat dikalahkan oleh siapa pun.'
            ],
            [
                'urutan' => 9,
                'arab' => 'الجبار',
                'latin' => 'Al-Jabbar',
                'arti' => 'Yang Memiliki Mutlak Kegagahan',
                'deskripsi' => 'Maha Berkehendak untuk memaksakan kemauan-Nya kepada makhluk-Nya, serta Maha Memperbaiki keadaan hamba-Nya.'
            ],
            [
                'urutan' => 10,
                'arab' => 'المتكبر',
                'latin' => 'Al-Mutakabbir',
                'arti' => 'Yang Maha Megah',
                'deskripsi' => 'Maha Memiliki segala kebesaran, keagungan, dan kemegahan yang hanya pantas dimiliki oleh-Nya.'
            ],
            [
                'urutan' => 11,
                'arab' => 'الخالق',
                'latin' => 'Al-Khaliq',
                'arti' => 'Yang Maha Pencipta',
                'deskripsi' => 'Maha Pencipta yang menciptakan segala sesuatu dari yang tidak ada menjadi ada sesuai dengan kehendak-Nya.'
            ],
            [
                'urutan' => 12,
                'arab' => 'البارئ',
                'latin' => 'Al-Bari\'',
                'arti' => 'Yang Maha Melepaskan',
                'deskripsi' => 'Maha Merencanakan dan mewujudkan ciptaan-Nya secara rapi, seimbang, dan bebas dari cacat cela.'
            ],
            [
                'urutan' => 13,
                'arab' => 'المصور',
                'latin' => 'Al-Mushawwir',
                'arti' => 'Yang Maha Membentuk Rupa',
                'deskripsi' => 'Maha Membentuk rupa dan warna makhluk-Nya secara unik, berbeda satu sama lain sesuai keindahan-Nya.'
            ],
            [
                'urutan' => 14,
                'arab' => 'الغفار',
                'latin' => 'Al-Ghaffar',
                'arti' => 'Yang Maha Pengampun',
                'deskripsi' => 'Maha Pengampun yang berulang-kali mengampuni dosa dan kesalahan hamba-hamba-Nya yang bertobat.'
            ],
            [
                'urutan' => 15,
                'arab' => 'القهار',
                'latin' => 'Al-Qahhar',
                'arti' => 'Yang Maha Menundukkan',
                'deskripsi' => 'Maha Penakluk yang menguasai dan menundukkan segala sesuatu di bawah kehendak kekuasaan-Nya.'
            ],
            [
                'urutan' => 16,
                'arab' => 'الوهاب',
                'latin' => 'Al-Wahhab',
                'arti' => 'Yang Maha Pemberi Karunia',
                'deskripsi' => 'Maha Pemberi karunia, hadiah, dan nikmat secara terus-menerus tanpa meminta imbalan.'
            ],
            [
                'urutan' => 17,
                'arab' => 'الرزاق',
                'latin' => 'Ar-Razzaq',
                'arti' => 'Yang Maha Pemberi Rezeki',
                'deskripsi' => 'Maha Pemberi rezeki kepada seluruh makhluk ciptaan-Nya untuk kelangsungan hidup mereka.'
            ],
            [
                'urutan' => 18,
                'arab' => 'الفتاح',
                'latin' => 'Al-Fattah',
                'arti' => 'Yang Maha Pembuka Rahmat',
                'deskripsi' => 'Maha Pembuka pintu rahmat, ilmu, kesuksesan, dan jalan keluar dari setiap kesulitan hamba-Nya.'
            ],
            [
                'urutan' => 19,
                'arab' => 'العليم',
                'latin' => 'Al-Alim',
                'arti' => 'Yang Maha Mengetahui',
                'deskripsi' => 'Maha Mengetahui segala sesuatu, baik yang tampak maupun yang tersembunyi, masa lalu, masa kini, dan masa depan.'
            ],
            [
                'urutan' => 20,
                'arab' => 'القابض',
                'latin' => 'Al-Qabidh',
                'arti' => 'Yang Maha Menyempitkan',
                'deskripsi' => 'Maha Menyempitkan rezeki atau kehidupan bagi siapa saja yang dikehendaki-Nya berdasarkan keadilan-Nya.'
            ],
            [
                'urutan' => 21,
                'arab' => 'الباسط',
                'latin' => 'Al-Basith',
                'arti' => 'Yang Maha Melapangkan',
                'deskripsi' => 'Maha Melapangkan rezeki, kebahagiaan, dan kemudahan hidup bagi hamba-hamba yang dikehendaki-Nya.'
            ],
            [
                'urutan' => 22,
                'arab' => 'الخافض',
                'latin' => 'Al-Khafidh',
                'arti' => 'Yang Maha Menurunkan',
                'deskripsi' => 'Maha Menurunkan derajat orang-orang yang sombong, durhaka, dan memusuhi agama-Nya.'
            ],
            [
                'urutan' => 23,
                'arab' => 'الرافع',
                'latin' => 'Ar-Rafi\'',
                'arti' => 'Yang Maha Meninggikan',
                'deskripsi' => 'Maha Meninggikan derajat hamba-hamba-Nya yang beriman, berilmu, dan bertakwa di dunia dan akhirat.'
            ],
            [
                'urutan' => 24,
                'arab' => 'المعز',
                'latin' => 'Al-Mu\'izz',
                'arti' => 'Yang Maha Memuliakan',
                'deskripsi' => 'Maha Pemberi kemuliaan, kehormatan, dan kekuatan bagi siapa saja yang taat kepada-Nya.'
            ],
            [
                'urutan' => 25,
                'arab' => 'المذل',
                'latin' => 'Al-Mudzill',
                'arti' => 'Yang Maha Menghinakan',
                'deskripsi' => 'Maha Menghinakan siapa saja yang membangkang dan menjauh dari tuntunan kebenaran-Nya.'
            ],
            [
                'urutan' => 26,
                'arab' => 'السميع',
                'latin' => 'As-Sami\'',
                'arti' => 'Yang Maha Mendengar',
                'deskripsi' => 'Maha Mendengar segala suara, bisikan, doa, dan keluh kesah hamba-Nya tanpa membutuhkan alat pendengar.'
            ],
            [
                'urutan' => 27,
                'arab' => 'البصير',
                'latin' => 'Al-Bashir',
                'arti' => 'Yang Maha Melihat',
                'deskripsi' => 'Maha Melihat segala sesuatu yang ada di alam semesta ini, sekecil atau setersembunyi apa pun.'
            ],
            [
                'urutan' => 28,
                'arab' => 'الحكم',
                'latin' => 'Al-Hakam',
                'arti' => 'Yang Maha Menetapkan Hukum',
                'deskripsi' => 'Maha Menetapkan hukum dan keputusan yang mutlak adil, tidak ada yang dapat membatalkan keputusan-Nya.'
            ],
            [
                'urutan' => 29,
                'arab' => 'العدل',
                'latin' => 'Al-Adl',
                'arti' => 'Yang Maha Adil',
                'deskripsi' => 'Maha Adil dalam membagikan rezeki, memberi pahala, menetapkan takdir, dan tidak pernah berbuat zalim.'
            ],
            [
                'urutan' => 30,
                'arab' => 'اللطيف',
                'latin' => 'Al-Lathif',
                'arti' => 'Yang Maha Lembut',
                'deskripsi' => 'Maha Lembut kasih sayang-Nya dan sangat halus perbuatan-Nya dalam memperlakukan hamba-hamba-Nya.'
            ],
            [
                'urutan' => 31,
                'arab' => 'الخبير',
                'latin' => 'Al-Khabir',
                'arti' => 'Yang Maha Mengenal',
                'deskripsi' => 'Maha Mengetahui secara detail dan mendalam tentang segala hal yang paling rahasia sekalipun.'
            ],
            [
                'urutan' => 32,
                'arab' => 'الحليم',
                'latin' => 'Al-Halim',
                'arti' => 'Yang Maha Penyantun',
                'deskripsi' => 'Maha Penyantun yang tidak terburu-buru memberikan siksaan kepada pelaku dosa agar mereka bertobat.'
            ],
            [
                'urutan' => 33,
                'arab' => 'العظيم',
                'latin' => 'Al-Azhim',
                'arti' => 'Yang Maha Agung',
                'deskripsi' => 'Maha Agung, memiliki kebesaran yang mutlak dan tidak berujung yang melampaui akal pikiran makhluk.'
            ],
            [
                'urutan' => 34,
                'arab' => 'الغفور',
                'latin' => 'Al-Ghafur',
                'arti' => 'Yang Maha Pengampun',
                'deskripsi' => 'Maha Pengampun dosa-dosa besar bagi hamba-Nya yang bertobat dengan sungguh-sungguh.'
            ],
            [
                'urutan' => 35,
                'arab' => 'الشكور',
                'latin' => 'Asy-Syakur',
                'arti' => 'Yang Maha Menerima Syukur',
                'deskripsi' => 'Maha Menghargai dan membalas setiap amal kebaikan kecil hamba-Nya dengan pahala yang berlipat ganda.'
            ],
            [
                'urutan' => 36,
                'arab' => 'العلي',
                'latin' => 'Al-Aliyy',
                'arti' => 'Yang Maha Tinggi',
                'deskripsi' => 'Maha Tinggi kedudukan-Nya, kekuasaan-Nya, dan sifat-sifat-Nya di atas segala makhluk.'
            ],
            [
                'urutan' => 37,
                'arab' => 'الكبير',
                'latin' => 'Al-Kabir',
                'arti' => 'Yang Maha Besar',
                'deskripsi' => 'Maha Besar Dzat-Nya dan sifat-sifat-Nya, tidak ada sesuatu pun yang sebanding atau melampaui-Nya.'
            ],
            [
                'urutan' => 38,
                'arab' => 'الحفيظ',
                'latin' => 'Al-Hafizh',
                'arti' => 'Yang Maha Memelihara',
                'deskripsi' => 'Maha Memelihara dan melindungi alam semesta beserta seluruh makhluk agar tetap seimbang.'
            ],
            [
                'urutan' => 39,
                'arab' => 'المقيت',
                'latin' => 'Al-Muqit',
                'arti' => 'Yang Maha Pemberi Kecukupan',
                'deskripsi' => 'Maha Pemberi kekuatan, kecukupan, dan makanan jasmani maupun rohani untuk kehidupan makhluk-Nya.'
            ],
            [
                'urutan' => 40,
                'arab' => 'الحسيب',
                'latin' => 'Al-Hasib',
                'arti' => 'Yang Maha Pembuat Perhitungan',
                'deskripsi' => 'Maha Menjamin segala kebutuhan hamba-Nya, serta Maha Cepat dalam membuat perhitungan atas amal manusia.'
            ],
            [
                'urutan' => 41,
                'arab' => 'الجليل',
                'latin' => 'Al-Jalil',
                'arti' => 'Yang Maha Luhur',
                'deskripsi' => 'Maha Luhur dan Mulia, memiliki keagungan sifat yang sempurna dan berhak disembah.'
            ],
            [
                'urutan' => 42,
                'arab' => 'الكريم',
                'latin' => 'Al-Karim',
                'arti' => 'Yang Maha Pemurah',
                'deskripsi' => 'Maha Pemurah, memberikan nikmat dan ampunan secara melimpah ruah bahkan sebelum diminta.'
            ],
            [
                'urutan' => 43,
                'arab' => 'الرقيب',
                'latin' => 'Al-Raqib',
                'arti' => 'Yang Maha Mengawasi',
                'deskripsi' => 'Maha Mengawasi setiap gerakan, niat, dan tindakan seluruh makhluk-Nya tanpa pernah lalai.'
            ],
            [
                'urutan' => 44,
                'arab' => 'المجيب',
                'latin' => 'Al-Mujib',
                'arti' => 'Yang Maha Mengabulkan',
                'deskripsi' => 'Maha Mengabulkan doa, permintaan, dan permohonan tolong hamba-hamba-Nya.'
            ],
            [
                'urutan' => 45,
                'arab' => 'الواسع',
                'latin' => 'Al-Wasi\'',
                'arti' => 'Yang Maha Luas',
                'deskripsi' => 'Maha Luas ilmu-Nya, rezeki-Nya, rahmat-Nya, kekuasaan-Nya, dan ampunan-Nya.'
            ],
            [
                'urutan' => 46,
                'arab' => 'الحكيم',
                'latin' => 'Al-Hakim',
                'arti' => 'Yang Maha Bijaksana',
                'deskripsi' => 'Maha Bijaksana dalam setiap takdir, penciptaan, syariat, dan keputusan hukum bagi semesta.'
            ],
            [
                'urutan' => 47,
                'arab' => 'الودود',
                'latin' => 'Al-Wadud',
                'arti' => 'Yang Maha Mengasihi',
                'deskripsi' => 'Maha Mencintai hamba-hamba-Nya yang taat dan Maha Layak dicintai di atas segalanya.'
            ],
            [
                'urutan' => 48,
                'arab' => 'المجيد',
                'latin' => 'Al-Majid',
                'arti' => 'Yang Maha Mulia',
                'deskripsi' => 'Maha Agung, berhak atas pujian yang setinggi-tingginya dari seluruh alam semesta.'
            ],
            [
                'urutan' => 49,
                'arab' => 'الباعث',
                'latin' => 'Al-Ba\'its',
                'arti' => 'Yang Maha Menghidupkan Kembali',
                'deskripsi' => 'Maha Membangkitkan seluruh manusia dari alam kubur untuk mempertanggungjawabkan perbuatannya.'
            ],
            [
                'urutan' => 50,
                'arab' => 'الشهيد',
                'latin' => 'Asy-Syahid',
                'arti' => 'Yang Maha Menyaksikan',
                'deskripsi' => 'Maha Menyaksikan segala peristiwa yang terjadi di manapun dan kapanpun, tidak ada yang tersembunyi.'
            ],
            [
                'urutan' => 51,
                'arab' => 'الحق',
                'latin' => 'Al-Haqq',
                'arti' => 'Yang Maha Benar',
                'deskripsi' => 'Dzat Yang Maha Nyata kebenaran-Nya, janji-Nya benar, dan kitab suci-Nya adalah kebenaran sejati.'
            ],
            [
                'urutan' => 52,
                'arab' => 'الوكيل',
                'latin' => 'Al-Wakil',
                'arti' => 'Yang Maha Memelihara Penyerahan',
                'deskripsi' => 'Maha Mengurusi segala kemaslahatan hamba-Nya yang bertawakal dan berserah diri kepada-Nya.'
            ],
            [
                'urutan' => 53,
                'arab' => 'القوي',
                'latin' => 'Al-Qawiyy',
                'arti' => 'Yang Maha Kuat',
                'deskripsi' => 'Maha Memiliki kekuatan yang sempurna dan tidak pernah melemah selamanya.'
            ],
            [
                'urutan' => 54,
                'arab' => 'المتين',
                'latin' => 'Al-Matin',
                'arti' => 'Yang Maha Kokoh',
                'deskripsi' => 'Maha Kokoh, memiliki keteguhan kekuasaan yang tidak tergoyahkan oleh kekuatan apa pun.'
            ],
            [
                'urutan' => 55,
                'arab' => 'الولي',
                'latin' => 'Al-Waliyy',
                'arti' => 'Yang Maha Melindungi',
                'deskripsi' => 'Maha Melindungi dan menolong hamba-hamba-Nya yang beriman dalam menghadapi musuh.'
            ],
            [
                'urutan' => 56,
                'arab' => 'الحميد',
                'latin' => 'Al-Hamid',
                'arti' => 'Yang Maha Terpuji',
                'deskripsi' => 'Maha Terpuji karena Dzat, sifat, dan semua perbuatan-Nya mendatangkan kebaikan.'
            ],
            [
                'urutan' => 57,
                'arab' => 'المحصي',
                'latin' => 'Al-Muhshi',
                'arti' => 'Yang Maha Mengalkulasi',
                'deskripsi' => 'Maha Menghitung dan mencatat secara rinci jumlah segala sesuatu tanpa ada yang terlewat.'
            ],
            [
                'urutan' => 58,
                'arab' => 'المبدئ',
                'latin' => 'Al-Mubdi\'',
                'arti' => 'Yang Maha Memulai',
                'deskripsi' => 'Maha Memulai penciptaan alam semesta beserta isinya dari awal mula belum ada contoh sebelumnya.'
            ],
            [
                'urutan' => 59,
                'arab' => 'المعيد',
                'latin' => 'Al-Mu\'id',
                'arti' => 'Yang Maha Mengembalikan',
                'deskripsi' => 'Maha Mengembalikan kehidupan makhluk setelah kematian mereka untuk diadili di akhirat.'
            ],
            [
                'urutan' => 60,
                'arab' => 'المحيي',
                'latin' => 'Al-Muhyi',
                'arti' => 'Yang Maha Menghidupkan',
                'deskripsi' => 'Maha Pemberi nyawa dan kehidupan bagi jasmani yang mati, serta cahaya iman bagi ruhani.'
            ],
            [
                'urutan' => 61,
                'arab' => 'المميت',
                'latin' => 'Al-Mumit',
                'arti' => 'Yang Maha Mematikan',
                'deskripsi' => 'Maha Menetapkan kematian bagi setiap makhluk yang bernyawa pada waktu ajal yang telah ditentukan.'
            ],
            [
                'urutan' => 62,
                'arab' => 'الحي',
                'latin' => 'Al-Hayyu',
                'arti' => 'Yang Maha Hidup',
                'deskripsi' => 'Maha Hidup kekal abadi selamanya, tidak berawal dan tidak berakhir, serta tidak tersentuh rasa kantuk.'
            ],
            [
                'urutan' => 63,
                'arab' => 'القيوم',
                'latin' => 'Al-Qayyum',
                'arti' => 'Yang Maha Mandiri',
                'deskripsi' => 'Maha Mandiri, berdiri sendiri tanpa butuh bantuan makhluk, dan mengatur kehidupan seluruh makhluk.'
            ],
            [
                'urutan' => 64,
                'arab' => 'الواجد',
                'latin' => 'Al-Wajid',
                'arti' => 'Yang Maha Penemu',
                'deskripsi' => 'Maha Kaya, mudah menemukan dan mendapatkan apa saja yang dikehendaki-Nya tanpa hambatan.'
            ],
            [
                'urutan' => 65,
                'arab' => 'الماجد',
                'latin' => 'Al-Majid',
                'arti' => 'Yang Maha Mulia',
                'deskripsi' => 'Maha Memiliki keluhuran, kemuliaan, kehormatan, kebaikan, dan kedermawaan yang tak terbatas.'
            ],
            [
                'urutan' => 66,
                'arab' => 'الواحد',
                'latin' => 'Al-Wahid',
                'arti' => 'Yang Maha Tunggal',
                'deskripsi' => 'Maha Tunggal, tidak terbagi-bagi dalam dzat, sifat, maupun perbuatan-Nya.'
            ],
            [
                'urutan' => 67,
                'arab' => 'الأحد',
                'latin' => 'Al-Ahad',
                'arti' => 'Yang Maha Esa',
                'deskripsi' => 'Maha Esa, tiada sekutu, tiada tandingan, tiada bapak, anak, maupun setara bagi-Nya.'
            ],
            [
                'urutan' => 68,
                'arab' => 'الصمد',
                'latin' => 'As-Samad',
                'arti' => 'Yang Maha Dibutuhkan',
                'deskripsi' => 'Maha Dibutuhkan, menjadi tempat bergantung, bersandar, dan memohon segala kebutuhan bagi makhluk.'
            ],
            [
                'urutan' => 69,
                'arab' => 'القادر',
                'latin' => 'Al-Qadir',
                'arti' => 'Yang Maha Menentukan',
                'deskripsi' => 'Maha Berkuasa penuh untuk menentukan takdir, penciptaan, dan kehendak-Nya secara mutlak.'
            ],
            [
                'urutan' => 70,
                'arab' => 'المقتدر',
                'latin' => 'Al-Muqtadir',
                'arti' => 'Yang Maha Berkuasa',
                'deskripsi' => 'Maha Berkuasa, memiliki kekuatan yang tak terbatas untuk mewujudkan apa pun yang diinginkan.'
            ],
            [
                'urutan' => 71,
                'arab' => 'المقدم',
                'latin' => 'Al-Muqaddim',
                'arti' => 'Yang Maha Mendahulukan',
                'deskripsi' => 'Maha Mendahulukan sebagian perkara atas sebagian lainnya sesuai dengan hikmah kebijaksanaan-Nya.'
            ],
            [
                'urutan' => 72,
                'arab' => 'المؤخر',
                'latin' => 'Al-Mu\'akhkhir',
                'arti' => 'Yang Maha Mengakhirkan',
                'deskripsi' => 'Maha Mengakhirkan apa saja yang dikehendaki-Nya seperti azab bagi pendosa agar mereka bertobat.'
            ],
            [
                'urutan' => 73,
                'arab' => 'الأول',
                'latin' => 'Al-Awwal',
                'arti' => 'Yang Maha Awal',
                'deskripsi' => 'Yang Maha Pertama, ada sebelum segala sesuatu diciptakan dan tidak ada permulaan bagi keberadaan-Nya.'
            ],
            [
                'urutan' => 74,
                'arab' => 'الآخر',
                'latin' => 'Al-Akhir',
                'arti' => 'Yang Maha Akhir',
                'deskripsi' => 'Yang Maha Akhir, tetap kekal abadi setelah seluruh alam semesta dan makhluk hancur.'
            ],
            [
                'urutan' => 75,
                'arab' => 'الظاهر',
                'latin' => 'Azh-Zhahir',
                'arti' => 'Yang Maha Nyata',
                'deskripsi' => 'Maha Nyata bukti-bukti kewujudan dan kekuasaan-Nya melalui tanda-tanda kebesaran alam semesta.'
            ],
            [
                'urutan' => 76,
                'arab' => 'الباطن',
                'latin' => 'Al-Bathin',
                'arti' => 'Yang Maha Gaib',
                'deskripsi' => 'Maha Tersembunyi dari pancaindra makhluk di dunia, tidak ada sesuatu pun yang lebih dekat dibanding-Nya.'
            ],
            [
                'urutan' => 77,
                'arab' => 'الوالي',
                'latin' => 'Al-Wali',
                'arti' => 'Yang Maha Memerintah',
                'deskripsi' => 'Maha Menguasai dan mengendalikan semua urusan ciptaan-Nya dengan kekuasaan penuh.'
            ],
            [
                'urutan' => 78,
                'arab' => 'المتعالي',
                'latin' => 'Al-Muta\'ali',
                'arti' => 'Yang Maha Tinggi',
                'deskripsi' => 'Maha Suci dan bersih dari segala tuduhan, kekurangan, dan keserupaan dengan makhluk.'
            ],
            [
                'urutan' => 79,
                'arab' => 'البر',
                'latin' => 'Al-Barr',
                'arti' => 'Yang Maha Penderma',
                'deskripsi' => 'Maha Melimpahkan kebaikan, kelapangan rezeki, dan kedermawanan bagi semua hamba.'
            ],
            [
                'urutan' => 80,
                'arab' => 'التواب',
                'latin' => 'At-Tawwab',
                'arti' => 'Yang Maha Penerima Tobat',
                'deskripsi' => 'Maha Menerima tobat hamba-Nya yang menyesali perbuatan dosa dan kembali ke jalan kebenaran.'
            ],
            [
                'urutan' => 81,
                'arab' => 'المنتقم',
                'latin' => 'Al-Muntaqim',
                'arti' => 'Yang Maha Pemberi Balasan',
                'deskripsi' => 'Maha Memberikan hukuman dan balasan yang setimpal kepada pelaku kejahatan dan kedurhakaan.'
            ],
            [
                'urutan' => 82,
                'arab' => 'العفو',
                'latin' => 'Al-Afuww',
                'arti' => 'Yang Maha Pemaaf',
                'deskripsi' => 'Maha Menghapuskan dosa dan kesalahan hamba-Nya secara total hingga bersih seperti tidak pernah ada.'
            ],
            [
                'urutan' => 83,
                'arab' => 'الرؤوف',
                'latin' => 'Ar-Ra\'uf',
                'arti' => 'Yang Maha Pengasih',
                'deskripsi' => 'Maha Penuh kelembutan kasih sayang, belas kasih, dan kemudahan dalam syariat bagi hamba.'
            ],
            [
                'urutan' => 84,
                'arab' => 'مالك الملك',
                'latin' => 'Malikul-Mulk',
                'arti' => 'Pemilik Kekuasaan',
                'deskripsi' => 'Pemegang kerajaan semesta raya, pemilik mutlak kekuasaan yang berhak memberi dan mencabut kekuasaan.'
            ],
            [
                'urutan' => 85,
                'arab' => 'ذو الجلال والإكرام',
                'latin' => 'Dzul-Jalali wal-Ikram',
                'arti' => 'Pemilik Keagungan dan Kemuliaan',
                'deskripsi' => 'Maha Memiliki keagungan yang ditakuti dan kemuliaan yang dicintai, layak diagungkan.'
            ],
            [
                'urutan' => 86,
                'arab' => 'المقسط',
                'latin' => 'Al-Muqsit',
                'arti' => 'Yang Maha Adil',
                'deskripsi' => 'Maha Adil dalam menegakkan kebenaran, menuntut hak bagi yang dizalimi dari orang yang zalim.'
            ],
            [
                'urutan' => 87,
                'arab' => 'الجامع',
                'latin' => 'Al-Jami\'',
                'arti' => 'Yang Maha Mengumpulkan',
                'deskripsi' => 'Maha Mengumpulkan segala sesuatu yang berserakan, serta mengumpulkan seluruh manusia di Hari Kiamat.'
            ],
            [
                'urutan' => 88,
                'arab' => 'الغني',
                'latin' => 'Al-Ghaniyy',
                'arti' => 'Yang Maha Kaya',
                'deskripsi' => 'Maha Kaya secara mutlak, tidak membutuhkan sesuatu pun dari makhluk, sedangkan makhluk butuh kepada-Nya.'
            ],
            [
                'urutan' => 89,
                'arab' => 'المغني',
                'latin' => 'Al-Mughni',
                'arti' => 'Yang Maha Pemberi Kekayaan',
                'deskripsi' => 'Maha Pemberi kekayaan, kecukupan, dan kepuasan hidup kepada hamba-hamba pilihan-Nya.'
            ],
            [
                'urutan' => 90,
                'arab' => 'المانع',
                'latin' => 'Al-Mani\'',
                'arti' => 'Yang Maha Mencegah',
                'deskripsi' => 'Maha Mencegah bahaya bagi orang beriman, serta Maha Menahan rezeki bagi orang tertentu demi hikmah.'
            ],
            [
                'urutan' => 91,
                'arab' => 'الضار',
                'latin' => 'Adh-Dhar',
                'arti' => 'Yang Maha Pemberi Kemudaratan',
                'deskripsi' => 'Maha Menetapkan kemudaratan, ujian, dan kesusahan bagi siapa saja untuk menguji keimanan mereka.'
            ],
            [
                'urutan' => 92,
                'arab' => 'النافع',
                'latin' => 'An-Nafi\'',
                'arti' => 'Yang Maha Pemberi Manfaat',
                'deskripsi' => 'Maha Menetapkan manfaat, kegembiraan, dan kebaikan hidup bagi siapa saja yang dikehendaki-Nya.'
            ],
            [
                'urutan' => 93,
                'arab' => 'النور',
                'latin' => 'An-Nur',
                'arti' => 'Yang Maha Menerangi',
                'deskripsi' => 'Maha Pemberi cahaya bagi alam semesta secara fisik, serta cahaya hidayah bagi hati manusia.'
            ],
            [
                'urutan' => 94,
                'arab' => 'الهادي',
                'latin' => 'Al-Hadi',
                'arti' => 'Yang Maha Pemberi Petunjuk',
                'deskripsi' => 'Maha Pemberi petunjuk jalan kebenaran (hidayah) kepada hamba agar selamat di dunia dan akhirat.'
            ],
            [
                'urutan' => 95,
                'arab' => 'البديع',
                'latin' => 'Al-Badi\'',
                'arti' => 'Yang Maha Pencipta Keindahan',
                'deskripsi' => 'Maha Pencipta keindahan alam semesta yang menakjubkan tanpa ada contoh pendahulu sebelumnya.'
            ],
            [
                'urutan' => 96,
                'arab' => 'الباقي',
                'latin' => 'Al-Baqi',
                'arti' => 'Yang Maha Kekal',
                'deskripsi' => 'Yang Maha Kekal abadi, tidak tersentuh kemusnahan, kepunahan, atau perubahan selamanya.'
            ],
            [
                'urutan' => 97,
                'arab' => 'الوارث',
                'latin' => 'Al-Warits',
                'arti' => 'Yang Maha Pewaris',
                'deskripsi' => 'Maha Mewarisi alam semesta beserta segala isinya setelah seluruh makhluk ciptaan-Nya binasa.'
            ],
            [
                'urutan' => 98,
                'arab' => 'الرشيد',
                'latin' => 'Ar-Rasyid',
                'arti' => 'Yang Maha Pandai',
                'deskripsi' => 'Maha Cerdas dan Maha Benar dalam mengatur dan mengarahkan segala urusan menuju jalan keselamatan.'
            ],
            [
                'urutan' => 99,
                'arab' => 'الصبور',
                'latin' => 'As-Sabur',
                'arti' => 'Yang Maha Sabar',
                'deskripsi' => 'Maha Penyabar, tidak tergesa-gesa menyiksa pelaku dosa sebelum batas waktu ketetapan-Nya.'
            ],
        ];

        foreach ($names as $name) {
            AsmaulHusna::updateOrCreate(
                ['urutan' => $name['urutan']],
                [
                    'arab' => $name['arab'],
                    'latin' => $name['latin'],
                    'arti' => $name['arti'],
                    'deskripsi' => $name['deskripsi'],
                    'is_active' => true,
                ]
            );
        }
    }
}
