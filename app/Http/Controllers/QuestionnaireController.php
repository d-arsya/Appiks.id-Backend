<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalyzeQuestionnaireRequest;
use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use App\Models\User;
use App\Traits\ApiResponder;
use App\Traits\GeminiTrait;
use App\Traits\QuestionnaireTrait;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuestionnaireController extends Controller
{
    use ApiResponder, GeminiTrait, QuestionnaireTrait;

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
        $answers = $request->validated();
        $answers = $this->convertToAlphabet($type, $request['answers']);
        if ($type == 'insecure') {
            $result = $this->analyzeInsecureQuiz($answers);
        } else {
            $result = $this->analyzeSecureQuiz($answers);
        }

        return $this->success($result);
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

    protected function analyzeInsecureQuiz($answers)
    {
        $first = $this->firstInsecureTest(array_slice($answers, 0, 5));
        $second = $this->secondInsecureTest(array_slice($answers, 5, 3));
        $third = $this->thirdInsecureTest(array_slice($answers, 8, 2));
        $archetype = [
            'type' => [
                'main' => $first->main_archtype,
                'secondary' => $first->secondary_archtype,
            ],
            'character' => $first->archtype_character,
            'habits' => $first->archtype_habits,
            'description' => $first->archtype_description,
            'power' => $first->archtype_power,
        ];

        $mission = $this->missionInsecureTest([$archetype, $second, $third]);

        return ['archtype' => $archetype, 'learn' => $second, 'fuel' => $third, 'mission' => $mission];
    }
}
