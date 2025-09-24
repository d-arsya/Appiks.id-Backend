<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalyzeQuestionnaireRequest;
use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuestionnaireController extends Controller
{
    use ApiResponder;

    /**
     * Get questionnaire by type
     *
     * Akan mendapatkan pertanyaan angket berdasarkan data terakhir mood siswa tersebut. Hanya bisa diakses oleh siswa
     */
    #[Group('Questionnaire')]
    public function getAllQuestionnaires()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'student';
        });
        $type = in_array(Auth::user()->lastmood(), ['neutral', 'happy']) ? 'secure' : 'insecure';
        $questionnaires = Questionnaire::where('type', $type)->get();

        return $this->success(QuestionnaireResource::collection($questionnaires));
    }

    /**
     * Get questionnaire by type and order
     */
    #[Group('Questionnaire')]
    public function getOneQuestionnaire(Request $request, string $type, int $order)
    {
        $request->validate([
            'type' => 'string|  in:insecure,secure',
            'order' => 'integer|min:1|max:10',
        ]);
        $questionnaire = Questionnaire::whereType($type)->whereOrder($order)->first();

        return $this->success(new QuestionnaireResource($questionnaire));
    }

    /**
     * Analyze questionnaire answer
     *
     * Menganalisa hasil angket siswa. Hanya bisa diakses oleh siswa dan mengirimkan semua jawaban angket berupa array string jawaban
     *
     * @param  string  $type  secure | insecure.
     */
    #[Group('Questionnaire')]
    public function analyzeQuestionnaire(AnalyzeQuestionnaireRequest $request, string $type)
    {
        request()->validate([
            'type' => 'string|in:secure,insecure',
        ]);
        $answers = $request->validated();
        $answers = $this->convertToAlphabet($type, $answers['answers']);
        if ($type == 'insecure') {
            $result = $this->analyzeInsecureQuiz($answers);
        } else {
            $result = $this->analyzeSecureQuiz($answers);
        }

        return $this->success($result);
    }

    protected function analyzeInsecureQuiz($answers)
    {
        // Check if the input is a valid array
        if (! is_array($answers) || empty($answers)) {
            return ['error' => 'Invalid input. Please provide an array of quiz answers.'];
        }

        // Initialize counters for each section
        $strengths = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        $learning_styles = ['A' => 0, 'B' => 0, 'C' => 0];
        $motivation_drive = ['mastery_internal' => 0, 'performance_external' => 0];
        $mindset = ['growth' => 0, 'fixed' => 0];

        // Score the answers based on the provided document
        // Part 1: Strength-Spotter (Questions 1-5)
        for ($i = 0; $i <= 5; $i++) {
            if (isset($answers[$i])) {
                $answer_options = is_array($answers[$i]) ? $answers[$i] : [$answers[$i]];
                foreach ($answer_options as $option) {
                    if (isset($strengths[$option])) {
                        $strengths[$option]++;
                    }
                }
            }
        }

        // Part 2: Learning Style Detector (Questions 6-8)
        for ($i = 6; $i <= 8; $i++) {
            if (isset($answers[$i])) {
                $answer_options = is_array($answers[$i]) ? $answers[$i] : [$answers[$i]];
                foreach ($answer_options as $option) {
                    if (isset($learning_styles[$option])) {
                        $learning_styles[$option]++;
                    }
                }
            }
        }

        // Part 3: Drive Engine (Questions 9-10)
        // Question 9: Motivation source
        if (isset($answers[9])) {
            $option = $answers[9];
            if ($option === 'B' || $option === 'D') { // Mastery or Internal
                $motivation_drive['mastery_internal']++;
            } else { // Performance or External
                $motivation_drive['performance_external']++;
            }
        }

        // Question 10: Mindset
        if (isset($answers[10])) {
            $option = $answers[10];
            if ($option === 'A' || $option === 'C') { // Growth Mindset or Resilience
                $mindset['growth']++;
            } else { // Fixed Mindset or External Blame
                $mindset['fixed']++;
            }
        }

        // Determine the user's profile based on the highest scores
        $strength_key = array_keys($strengths, max($strengths))[0];
        $learning_style_key = array_keys($learning_styles, max($learning_styles))[0];
        $has_mastery_motivation = $motivation_drive['mastery_internal'] >= $motivation_drive['performance_external'];
        $has_growth_mindset = $mindset['growth'] >= $mindset['fixed'];

        // Interpret the results based on the provided document
        $hero_name = '';
        $super_strength = '';
        $learning_mode_description = '';
        $motivation_fuel = '';
        $mission_1 = '';
        $mission_2 = '';

        // Interpret "Super Strength" (Bagian 1) and create Mission 1
        switch ($strength_key) {
            case 'A': // Pembelajar/Pemikir
                $hero_name = 'The Sage (Sang Bijak)';
                $super_strength = 'Rasa Ingin Tahu yang Tak Terkalahkan';
                $mission_1 = 'Gunakan Kekuatan Super Rasa Ingin Tahu-mu: Pilih satu topik pelajaran yang sulit dan dalami hingga tuntas. Ubah catatanmu menjadi sebuah poster atau mind map yang penuh warna dan gambar, lalu jelaskan poster itu ke orang tua atau temanmu!';
                break;
            case 'B': // Kreator/Inovator
                $hero_name = 'The Artisan (Sang Perajin)';
                $super_strength = 'Kreativitas!';
                $mission_1 = 'Manfaatkan Kekuatan Super Kreativitas-mu: Coba buat sebuah proyek mini (gambar, cerita, atau model 3D) yang menjelaskan konsep sulit dari salah satu pelajaran. Tunjukkan karyamu ke guru atau temanmu!';
                break;
            case 'C': // Sosialis/Kolaborator
                $hero_name = 'The Guardian (Sang Penjaga)';
                $super_strength = 'Empati dan Kerja Sama!';
                $mission_1 = 'Aktifkan Kekuatan Super Empati-mu: Ajak satu atau dua teman untuk belajar bersama. Gunakan keahlianmu dalam kerja sama untuk saling membantu memahami materi yang sulit. Tentukan target belajar bersama, misalnya menyelesaikan satu bab dalam seminggu.';
                break;
            case 'D': // Perencana/Strategis
                $hero_name = 'The Architect (Sang Arsitek)';
                $super_strength = 'Ketelitian dan Organisasi!';
                $mission_1 = 'Terapkan Kekuatan Super Perencanaan-mu: Buat rencana belajar mingguan yang terperinci untuk mata pelajaran yang paling kamu butuhkan. Tetapkan target kecil setiap hari dan pastikan kamu mengikutinya dengan konsisten. Catat kemajuanmu!';
                break;
        }

        // Interpret "Learning Mode" (Bagian 2)
        switch ($learning_style_key) {
            case 'A': // Auditory
                $learning_mode_description = 'Auditori (Kamu jago banget kalau belajar pakai mendengarkan, seperti podcast atau rekaman guru!)';
                break;
            case 'B': // Visual
                $learning_mode_description = 'Visual (Kamu jago banget kalau belajar pakai mind map dan gambar!)';
                break;
            case 'C': // Kinesthetic
                $learning_mode_description = 'Kinestetik (Kamu jago banget kalau belajar sambil bergerak atau langsung praktik!)';
                break;
        }

        // Interpret "Motivation Fuel" and create Mission 2 based on Mindset
        if ($has_mastery_motivation) {
            $motivation_fuel = 'Kamu punya motivasi internal yang sangat kuat! Ini keren sekali karena bahan bakarmu datang dari dalam dirimu sendiri.';
        } else {
            $motivation_fuel = 'Kamu termotivasi oleh tujuan dan pengakuan eksternal. Tidak ada salahnya, tapi coba latih juga untuk menemukan kesenangan dalam proses belajarnya sendiri, bukan hanya hasilnya.';
        }

        if ($has_growth_mindset) {
            $mindset_description = 'Mental Baja';
            $mission_2 = 'Asah Mental Baja-mu: Jika nanti dapat nilai yang tidak sesuai harapan, jangan langsung menyerah. Tanyakan pada dirimu: "Apa satu hal yang bisa aku pelajari dari kesalahan ini untuk jadi lebih kuat?" Jadikan kegagalan sebagai batu loncatan.';
        } else {
            $mindset_description = 'Mental Adaptif';
            $mission_2 = 'Latih Pola Pikir Bertumbuh-mu: Ketika menghadapi kesulitan, coba ubah pertanyaanmu dari "Mengapa aku gagal?" menjadi "Apa yang bisa aku lakukan untuk mencoba lagi dengan cara yang berbeda?". Cari tahu di mana letak kesalahanmu dan susun rencana perbaikan yang konkret.';
        }

        // Construct the result array
        $result_array = [
            'title' => 'PROFIL PAHLAWAN',
            'hero_name' => "{$hero_name} dengan {$mindset_description}",
            'super_strength' => $super_strength,
            'learning_mode' => $learning_mode_description,
            'motivation_fuel' => $motivation_fuel,
            'mission_challenge' => [
                'title' => 'MISI TANTANGANMU (untuk minggu ini):',
                'mission_1' => $mission_1,
                'mission_2' => $mission_2,
            ],
            'note' => 'Ingat, ini bukan label, tapi titik awal. Kamu bisa mengembangkan semua kekuatan lainnya seiring waktu! Semangat, Pahlawan!',
        ];

        return $result_array;
    }

    protected function analyzeSecureQuiz($answers)
    {
        if (! is_array($answers) || empty($answers)) {
            return ['error' => 'Input tidak valid. Mohon berikan array berisi jawaban kuis.'];
        }

        // Peta dari opsi jawaban ke kata kunci arketipe
        $archetype_keywords = [
            1 => [
                'A' => 'Achievement',
                'B' => 'Helping Others',
                'C' => 'Freedom',
                'D' => 'Collaboration',
            ],
            2 => [
                'A' => 'Results',
                'B' => 'Making a Difference',
                'C' => 'Innovation',
                'D' => 'Relationships',
            ],
            3 => [
                'A' => 'Expertise',
                'B' => 'Dependability',
                'C' => 'Originality',
                'D' => 'Community',
            ],
            4 => [
                'A' => 'Analytical',
                'B' => 'Communicative',
                'C' => 'Artistic',
                'D' => 'Interpersonal',
            ],
            5 => [
                'A' => 'Analytical', // Futurist, mapping to Analytical
                'B' => 'Communicative', // Influencer, mapping to Communicative
                'C' => 'Artistic', // Creator, mapping to Artistic
                'D' => 'Interpersonal', // Leader, mapping to Interpersonal
            ],
            6 => [
                'A' => 'Fast-Paced',
                'B' => 'Structured',
                'C' => 'Flexible',
                'D' => 'Supportive',
            ],
            7 => [
                'A' => 'Flexible', // Opportunity-Seeker, mapping to Flexible
                'B' => 'Structured', // Stability-Seeker, mapping to Structured
                'C' => 'Flexible', // Adaptor, mapping to Flexible
                'D' => 'Supportive', // Collaborator, mapping to Supportive
            ],
        ];

        // Peta dari kata kunci ke arketipe dominan
        $archetype_map = [
            'strategist' => ['Achievement', 'Results', 'Analytical', 'Fast-Paced'],
            'advocate' => ['Helping Others', 'Making a Difference', 'Communicative', 'Supportive'],
            'innovator' => ['Freedom', 'Innovation', 'Artistic', 'Flexible'],
            'builder' => ['Collaboration', 'Community', 'Interpersonal', 'Structured', 'Supportive'],
        ];

        $counts = [];
        foreach ($archetype_map as $key => $values) {
            $counts[$key] = 0;
        }

        // Menghitung poin berdasarkan jawaban
        foreach ($answers as $q_num => $answer) {
            if (isset($archetype_keywords[$q_num][$answer])) {
                $keyword = $archetype_keywords[$q_num][$answer];
                foreach ($archetype_map as $archetype => $keywords) {
                    if (in_array($keyword, $keywords)) {
                        $counts[$archetype]++;
                    }
                }
            }
        }

        // Menentukan arketipe utama dan pendukung
        arsort($counts);
        $primary_archetype_key = key($counts);
        $primary_archetype_count = current($counts);
        next($counts);
        $secondary_archetype_key = key($counts);
        $secondary_archetype_count = current($counts);

        // Ambil informasi dari dokumen
        $archetypes_info = [
            'strategist' => [
                'name' => 'The Strategist (Ahli Strategi)',
                'values' => 'Tantangan dan Hasil.',
                'skills' => 'Analitis & Logika.',
                'environment' => 'Dinamis, cepat, berorientasi pada target.',
                'description' => 'Kamu adalah pemecah masalah yang ulung dan bersinar dalam lingkungan kompetitif di mana keahlian dan hasil kerjamu dihargai.',
                'paths' => 'Riset, Teknologi, Finans, Konsultasi, Engineering, Entrepreneurship',
            ],
            'advocate' => [
                'name' => 'The Advocate (Pembela/Pendukung)',
                'values' => 'Dampak dan Orang.',
                'skills' => 'Komunikasi & Bahasa, Sosial & Emosional.',
                'environment' => 'Komunal dan suportif.',
                'description' => 'Kamu punya hasrat kuat untuk membuat dunia menjadi lebih baik, dimulai dari membantu orang di sekitarmu.',
                'paths' => 'Psikologi, Pendidikan, Kedokteran, Hukum, NGO, Customer Experience, HR',
            ],
            'innovator' => [
                'name' => 'The Innovator (Inovator)',
                'values' => 'Kebebasan dan Kreasi.',
                'skills' => 'Seni & Praktek.',
                'environment' => 'Fleksibel, tidak kaku, mendukung eksperimen.',
                'description' => 'Kamu adalah pemikir orisinal yang tidak suka dibatasi dan berkembang dalam lingkungan yang menghargai ide-ide baru dan cara-cara yang tidak konvensional.',
                'paths' => 'Seni, Desain (Grafis, UX/UI), Pemrograman Kreatif, Marketing, Penulisan, Peneliti',
            ],
            'builder' => [
                'name' => 'The Builder (Pembangun Komunitas)',
                'values' => 'Hubungan dan Kerjasama.',
                'skills' => 'Sosial & Emosional.',
                'environment' => 'Komunal dan suportif.',
                'description' => 'Kamu adalah perekat yang hebat. Kamu percaya bahwa pencapaian terbesar diraih bersama-sama dan ahli dalam membangun kepercayaan serta sistem yang membuat tim solid.',
                'paths' => 'Manajemen Proyek, Operasional, Event Organizer, Guru, Community Manager',
            ],
        ];

        // Menggabungkan profil utama dan pendukung
        $profile_info = [
            'archetype' => [
                'primary' => $archetypes_info[$primary_archetype_key]['name'],
                'secondary' => '',
                'name' => '',
            ],
            'summary' => [
                'Kompas Nilai' => '',
                'Peralatan Andalan' => '',
                'Medan Ideal' => '',
                'Path Karir' => '',
            ],
            'description' => $archetypes_info[$primary_archetype_key]['description'],
            'mission_challenge' => [
                'title' => 'MISI EKSPLORASIMU (Minggu ini):',
                'mission_1' => '',
                'mission_2' => '',
            ],
            'note' => 'Ingat, ini bukan takdir. Ini adalah peta untuk membantumu mulai menjelajahi opsi yang selaras dengan siapa dirimu. Selamat berpetualang!',
        ];

        // Menentukan campuran arketipe jika ada dua yang dominan
        if ($primary_archetype_count > 0 && $secondary_archetype_count > 0) {
            if ($primary_archetype_count - $secondary_archetype_count <= 1) {
                $profile_info['archetype']['secondary'] = $archetypes_info[$secondary_archetype_key]['name'];
                $profile_info['archetype']['name'] = 'The '.ucfirst($primary_archetype_key).' '.ucfirst($secondary_archetype_key).' (Arketipe Campuran)';
                $profile_info['summary']['Kompas Nilai'] = "{$archetypes_info[$primary_archetype_key]['values']} dan {$archetypes_info[$secondary_archetype_key]['values']}";
                $profile_info['summary']['Peralatan Andalan'] = "{$archetypes_info[$primary_archetype_key]['skills']} dan {$archetypes_info[$secondary_archetype_key]['skills']}";
                $profile_info['summary']['Medan Ideal'] = "{$archetypes_info[$primary_archetype_key]['environment']} dan {$archetypes_info[$secondary_archetype_key]['environment']}";
                $profile_info['summary']['Path Karir'] = "{$archetypes_info[$primary_archetype_key]['paths']}, {$archetypes_info[$secondary_archetype_key]['paths']}";
            } else {
                // Jika hanya satu arketipe yang sangat dominan
                $profile_info['archetype']['name'] = $archetypes_info[$primary_archetype_key]['name'];
                $profile_info['summary']['Kompas Nilai'] = $archetypes_info[$primary_archetype_key]['values'];
                $profile_info['summary']['Peralatan Andalan'] = $archetypes_info[$primary_archetype_key]['skills'];
                $profile_info['summary']['Medan Ideal'] = $archetypes_info[$primary_archetype_key]['environment'];
                $profile_info['summary']['Path Karir'] = $archetypes_info[$primary_archetype_key]['paths'];
            }
        }

        // Menentukan misi eksplorasi berdasarkan arketipe utama
        switch ($primary_archetype_key) {
            case 'strategist':
                $profile_info['mission_challenge']['mission_1'] = 'Jelajahi Jalur: Cari satu orang di LinkedIn atau YouTube yang bekerja di bidang yang kamu minati (misalnya Data Scientist atau Konsultan Strategi). Cari tahu seperti apa keseharian mereka.';
                $profile_info['mission_challenge']['mission_2'] = 'Coba Praktik: Ambil satu masalah kompleks di sekitarmu (contoh: mengapa temanmu sulit mengerti pelajaran tertentu?). Pikirkan satu solusi dengan cara yang paling logis dan terstruktur, lalu coba terapkan.';
                break;
            case 'advocate':
                $profile_info['mission_challenge']['mission_1'] = 'Jelajahi Jalur: Cari satu orang di LinkedIn atau YouTube yang bekerja di Social Enterprise atau posisi HR. Lihat seperti apa keseharian mereka, dan bagaimana mereka membantu orang lain.';
                $profile_info['mission_challenge']['mission_2'] = 'Coba Praktik: Ambil satu masalah kecil di sekitarmu (contoh: ada teman yang kesusahan). Coba tawarkan bantuan untuk mendengarkan ceritanya, lalu pikirkan satu solusi sederhana yang bisa membantunya.';
                break;
            case 'innovator':
                $profile_info['mission_challenge']['mission_1'] = 'Jelajahi Jalur: Cari satu orang di LinkedIn atau YouTube yang bekerja sebagai UX Designer atau Penulis Kreatif. Lihat karya-karya mereka dan bagaimana mereka mengeksplorasi ide-ide baru.';
                $profile_info['mission_challenge']['mission_2'] = "Coba Praktik: Ambil satu benda biasa di sekitarmu (contoh: botol minum). Pikirkan 10 cara baru untuk menggunakannya. Catat semua idemu, tidak peduli seberapa 'liar' itu.";
                break;
            case 'builder':
                $profile_info['mission_challenge']['mission_1'] = 'Jelajahi Jalur: Cari satu orang di LinkedIn atau YouTube yang bekerja sebagai Community Manager atau Project Manager. Lihat bagaimana mereka memimpin tim atau mengelola sebuah komunitas.';
                $profile_info['mission_challenge']['mission_2'] = 'Coba Praktik: Ambil satu proyek kelompok kecil. Tawarkan diri untuk menjadi pemimpinnya. Buat rencana kerja yang jelas, bagikan tugas, dan pastikan semua orang bisa bekerja dengan nyaman. Catat apa yang kamu pelajari dari prosesnya.';
                break;
        }

        return $profile_info;
    }

    protected function convertToAlphabet(string $type, $answers)
    {
        $result = Questionnaire::where('type', $type)->orderBy('order')->get();

        $alphabetAnswers = [];
        foreach ($answers as $key => $item) {
            foreach ($result[$key]->answers as $alpha => $text) {
                if ($text['text'] == $item) {
                    $alphabetAnswers[] = $alpha;
                }
            }
        }

        return $alphabetAnswers;
    }
}
