<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $secure = [
            // Bagian 1: Kompas Nilai
            [
                'quiz_name' => 'Kompas Nilai',
                'type'      => 'secure',
                'question'  => 'Aku paling termotivasi ketika...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Berhasil menyelesaikan tantangan yang sangat rumit', 'category' => 'Achievement'],
                    'B' => ['text' => 'Bisa membantu orang lain mencapai tujuannya', 'category' => 'Helping Others'],
                    'C' => ['text' => 'Memiliki kebebasan untuk mengatur waktuku sendiri', 'category' => 'Freedom & Autonomy'],
                    'D' => ['text' => 'Bekerja dalam tim yang kompak dan menyenangkan', 'category' => 'Collaboration'],
                ]),
                'order'     => 1,
            ],
            [
                'quiz_name' => 'Kompas Nilai',
                'type'      => 'secure',
                'question'  => 'Bagian paling menarik dari sebuah proyek adalah...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Sensasi mengejar target dan tujuan yang jelas', 'category' => 'Results-Driven'],
                    'B' => ['text' => 'Kesempatan untuk membuat dampak positif', 'category' => 'Making a Difference'],
                    'C' => ['text' => 'Proses mengeksplorasi ide-ide baru dan cara-cara inovatif', 'category' => 'Innovation'],
                    'D' => ['text' => 'Membangun hubungan dan kepercayaan dengan orang-orang dalam tim', 'category' => 'Relationships'],
                ]),
                'order'     => 2,
            ],
            [
                'quiz_name' => 'Kompas Nilai',
                'type'      => 'secure',
                'question'  => 'Aku ingin dikenal sebagai seseorang yang...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Sangat kompeten dan ahli di bidangnya', 'category' => 'Expertise'],
                    'B' => ['text' => 'Peduli dan bisa diandalkan untuk membantu', 'category' => 'Dependability'],
                    'C' => ['text' => 'Kreatif dan punya perspektif yang unik', 'category' => 'Originality'],
                    'D' => ['text' => 'Menjadi bagian penting dari komunitasnya', 'category' => 'Community'],
                ]),
                'order'     => 3,
            ],

            // Bagian 2: Peralatan Andalan
            [
                'quiz_name' => 'Peralatan Andalan',
                'type'      => 'secure',
                'question'  => 'Aku paling percaya diri ketika menggunakan skill...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Analitis & Logika (memecah masalah, data)', 'category' => 'Analytical'],
                    'B' => ['text' => 'Komunikasi & Bahasa (menulis, presentasi, negosiasi)', 'category' => 'Communicative'],
                    'C' => ['text' => 'Seni & Praktek (mendesain, membuat, membangun)', 'category' => 'Artistic/Hands-on'],
                    'D' => ['text' => 'Sosial & Emosional (memimpin, memotivasi, mendengar)', 'category' => 'Interpersonal'],
                ]),
                'order'     => 4,
            ],
            [
                'quiz_name' => 'Peralatan Andalan',
                'type'      => 'secure',
                'question'  => 'Skill yang paling ingin kuasai adalah skill untuk...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Menganalisis data besar dan memprediksi tren', 'category' => 'Futurist'],
                    'B' => ['text' => 'Membujuk dan menginspirasi banyak orang', 'category' => 'Influencer'],
                    'C' => ['text' => 'Menciptakan produk fisik/digital yang indah dan fungsional', 'category' => 'Creator'],
                    'D' => ['text' => 'Memimpin tim yang solid untuk mencapai goal besar', 'category' => 'Leader'],
                ]),
                'order'     => 5,
            ],

            // Bagian 3: Medan Petualangan
            [
                'quiz_name' => 'Medan Petualangan',
                'type'      => 'secure',
                'question'  => 'Lingkungan kerja idealku adalah...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Dinamis, cepat, berorientasi pada target dan bonus', 'category' => 'Fast-Paced'],
                    'B' => ['text' => 'Stabil, terstruktur, dengan prosedur yang jelas', 'category' => 'Structured'],
                    'C' => ['text' => 'Fleksibel, tidak kaku, mendukung eksperimen dan cara kerja personal', 'category' => 'Flexible'],
                    'D' => ['text' => 'Komunal, hangat, dimana semua orang saling support', 'category' => 'Supportive'],
                ]),
                'order'     => 6,
            ],
            [
                'quiz_name' => 'Medan Petualangan',
                'type'      => 'secure',
                'question'  => 'Saat menghadapi ketidakpastian, aku...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Justru tertantang dan melihatnya sebagai peluang', 'category' => 'Opportunity-Seeker'],
                    'B' => ['text' => 'Lebih nyaman mengikuti rencana yang sudah terbukti', 'category' => 'Stability-Seeker'],
                    'C' => ['text' => 'Berusaha beradaptasi dan mencari cara baru', 'category' => 'Adaptor'],
                    'D' => ['text' => 'Mengandalkan diskusi dengan tim untuk mendapatkan kejelasan', 'category' => 'Collaborator'],
                ]),
                'order'     => 7,
            ],
        ];



        $insecure = [
            // Bagian 1: Kekuatan Super (Strength-Spotter)
            [
                'quiz_name' => 'Kekuatan Super',
                'type'      => 'insecure',
                'question'  => 'Di waktu luang, aku paling suka...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Membaca/menonton hal baru yang menarik', 'category' => 'Pembelajar'],
                    'B' => ['text' => 'Menyendiri untuk menggambar/menulis/bermain musik', 'category' => 'Kreator'],
                    'C' => ['text' => 'Ngobrol atau berkumpul dengan teman', 'category' => 'Sosialis'],
                    'D' => ['text' => 'Merapikan kamar atau merencanakan jadwal', 'category' => 'Perencana'],
                ]),
                'order'     => 1,
            ],
            [
                'quiz_name' => 'Kekuatan Super',
                'type'      => 'insecure',
                'question'  => 'Ketika menghadapi masalah yang sulit, aku...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Mencari banyak referensi dan tutorial', 'category' => 'Pemecah Masalah'],
                    'B' => ['text' => 'Memikirkan solusi yang tidak biasa', 'category' => 'Inovator'],
                    'C' => ['text' => 'Langsung menelepon teman untuk minta saran', 'category' => 'Kolaborator'],
                    'D' => ['text' => 'Membuat daftar langkah-langkah untuk menyelesaikannya', 'category' => 'Strategis'],
                ]),
                'order'     => 2,
            ],
            [
                'quiz_name' => 'Kekuatan Super',
                'type'      => 'insecure',
                'question'  => 'Aku merasa paling bangga jika...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Berhasil memahami konsep yang rumit', 'category' => 'Pemikir Mendalam'],
                    'B' => ['text' => 'Karyaku (gambar, tulisan, proyek) dipuji orang', 'category' => 'Seniman'],
                    'C' => ['text' => 'Aku bisa membantu teman yang kesusahan', 'category' => 'Penolong'],
                    'D' => ['text' => 'Semua tugas terselesaikan tepat waktu dengan rapi', 'category' => 'Teliti'],
                ]),
                'order'     => 3,
            ],
            [
                'quiz_name' => 'Kekuatan Super',
                'type'      => 'insecure',
                'question'  => 'Dalam kerja kelompok, peran alamiku adalah...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Si pencari fakta dan data', 'category' => 'Peneliti'],
                    'B' => ['text' => 'Si pencetus ide gila', 'category' => 'Idea Generator'],
                    'C' => ['text' => 'Si perekat tim yang menjaga suasana', 'category' => 'Mediator'],
                    'D' => ['text' => 'Si pengatur waktu dan pembagi tugas', 'category' => 'Manajer Proyek'],
                ]),
                'order'     => 4,
            ],
            [
                'quiz_name' => 'Kekuatan Super',
                'type'      => 'insecure',
                'question'  => 'Kekuatan terbesarku adalah...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Rasa ingin tahuku yang tinggi', 'category' => 'Curiosity'],
                    'B' => ['text' => 'Imajinasiku yang liar', 'category' => 'Imagination'],
                    'C' => ['text' => 'Kemampuanku memahami perasaan orang', 'category' => 'Empathy'],
                    'D' => ['text' => 'Konsistensi dan ketekunanku', 'category' => 'Perseverance'],
                ]),
                'order'     => 5,
            ],

            // Bagian 2: Mode Belajar (Learning Style Detector)
            [
                'quiz_name' => 'Mode Belajar',
                'type'      => 'insecure',
                'question'  => 'Aku paling mudah ingat suatu pelajaran jika...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Guru menjelaskan dengan suara jelas', 'category' => 'Auditori'],
                    'B' => ['text' => 'Melihat diagram, grafik, atau video', 'category' => 'Visual'],
                    'C' => ['text' => 'Langsung praktik atau membuat model', 'category' => 'Kinestetik'],
                ]),
                'order'     => 6,
            ],
            [
                'quiz_name' => 'Mode Belajar',
                'type'      => 'insecure',
                'question'  => 'Kalau aku sedang bosan belajar, aku akan...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Mendengarkan musik atau rekaman pelajaran', 'category' => 'Auditori'],
                    'B' => ['text' => 'Menandai buku dengan stabilo atau membuat mind map', 'category' => 'Visual'],
                    'C' => ['text' => 'Belajar sambil jalan-jalan atau menggunakan benda nyata', 'category' => 'Kinestetik'],
                ]),
                'order'     => 7,
            ],
            [
                'quiz_name' => 'Mode Belajar',
                'type'      => 'insecure',
                'question'  => 'Untuk menghafal rumus, caraku adalah...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Mengucapkannya berulang-ulang', 'category' => 'Auditori'],
                    'B' => ['text' => 'Menuliskannya di kertas berkali-kali', 'category' => 'Visual'],
                    'C' => ['text' => 'Menggerakkan tangan atau berjalan sambil menghafal', 'category' => 'Kinestetik'],
                ]),
                'order'     => 8,
            ],

            // Bagian 3: Bahan Bakar Motivasi (Drive Engine)
            [
                'quiz_name' => 'Bahan Bakar Motivasi',
                'type'      => 'insecure',
                'question'  => 'Aku termotivasi untuk belajar paling keras ketika...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Ingin mendapatkan nilai terbaik di kelas', 'category' => 'Performance'],
                    'B' => ['text' => 'Topiknya sangat menarik dan ingin ku kuasai', 'category' => 'Mastery'],
                    'C' => ['text' => 'Ada hadiah atau imbalan dari orang tua', 'category' => 'External'],
                    'D' => ['text' => 'Merasa puas bisa mengerti sesuatu yang baru', 'category' => 'Internal'],
                ]),
                'order'     => 9,
            ],
            [
                'quiz_name' => 'Bahan Bakar Motivasi',
                'type'      => 'insecure',
                'question'  => 'Saat dapat nilai jelek, reaksiku adalah...',
                'answers'   => json_encode([
                    'A' => ['text' => 'Ingin membuktikan bahwa aku bisa dapat nilai lebih baik', 'category' => 'Growth Mindset'],
                    'B' => ['text' => 'Takut dimarahi atau dibandingkan dengan teman', 'category' => 'Fixed Mindset'],
                    'C' => ['text' => 'Langsung melihat di mana kesalahanku dan belajar darinya', 'category' => 'Resilience'],
                    'D' => ['text' => 'Menyalahkan guru atau soal yang terlalu sulit', 'category' => 'External Blame'],
                ]),
                'order'     => 10,
            ],
        ];


        DB::table('questionnaires')->insert($secure);
        DB::table('questionnaires')->insert($insecure);
    }
}
