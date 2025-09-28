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

    protected function analyzeSecureQuiz(array $answers)
    {
        $archetypeMapping = [
            0 => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
            1 => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
            2 => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
            3 => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
            4 => ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'],
            5 => ['A' => 'A', 'B' => 'D', 'C' => 'C', 'D' => 'B'],
            6 => ['A' => 'A', 'B' => 'D', 'C' => 'C', 'D' => 'B'],
        ];

        $archetypesData = [
            'A' => [
                'name' => 'The Strategist',
                'pola_jawaban' => ['Achievement', 'Results', 'Analytical', 'Fast-Paced'],
                'description' => 'Tantangan dan Hasil. Pemecah masalah ulung di lingkungan kompetitif.',
                'carier_path' => ['Riset', 'Teknologi', 'Finans', 'Konsultasi', 'Engineering', 'Entrepreneurship'],
                'message' => 'Dunia need problem-solver sepertimu. Fokuskan energimu pada bidang yang membuat otakmu terbakar.',
                'mission_carier' => 'Jelajahi Jalur: Cari Data Scientist atau Konsultan Manajemen.',
                'mission_practice' => 'Coba Praktik: Optimalkan proses belajar yang tidak efisien dengan solusi berbasis data/logika.',
            ],
            'B' => [
                'name' => 'The Advocate',
                'pola_jawaban' => ['Helping Others', 'Making a Difference', 'Communicative', 'Supportive'],
                'description' => 'Dampak dan Orang. Hasrat kuat membantu orang, dengan keahlian memahami dan berkomunikasi.',
                'carier_path' => ['Psikologi', 'Pendidikan', 'Kedokteran', 'Hukum', 'NGO', 'HR'],
                'message' => 'Kekuatan supermu adalah empati dan pelayanan. Itu adalah hadiah yang langka.',
                'mission_carier' => 'Jelajahi Jalur: Cari Pekerja Sosial (Social Worker) atau Guru.',
                'mission_practice' => 'Coba Praktik: Bantu teman yang stres dengan mendengarkan aktif dan dukungan tulus.',
            ],
            'C' => [
                'name' => 'The Innovator',
                'pola_jawaban' => ['Freedom', 'Innovation', 'Artistic/Hands-on', 'Flexible'],
                'description' => 'Kebebasan dan Kreasi. Pemikir orisinal yang berkembang dalam ide-ide non-konvensional.',
                'carier_path' => ['Seni', 'Desain (Grafis, UX/UI)', 'Pemrograman Kreatif', 'Marketing', 'Penulisan', 'R&D'],
                'message' => 'Jagalah api kreativitasmu. Carilah ruang di mana kamu bisa bereksperimen dan gagal tanpa takut.',
                'mission_carier' => 'Jelajahi Jalur: Cari Desainer Produk atau Content Creator.',
                'mission_practice' => 'Coba Praktik: Ubah penyampaian informasi yang membosankan menjadi desain/video yang unik dan fungsional.',
            ],
            'D' => [
                'name' => 'The Builder',
                'pola_jawaban' => ['Collaboration', 'Community', 'Interpersonal', 'Structured/Stability'],
                'description' => 'Hubungan dan Kerjasama. Perekat tim, ahli dalam membangun kepercayaan dan sistem solid.',
                'carier_path' => ['Manajemen Proyek', 'Operasional', 'Event Organizer', 'Guru', 'Community Manager'],
                'message' => 'Kamu adalah fondasi dari setiap kesuksesan tim. Teruslah latih skill kepemimpinan dan manajemenmu.',
                'mission_carier' => 'Jelajahi Jalur: Cari Project Manager atau Kepala Operasional (COO).',
                'mission_practice' => 'Coba Praktik: Atasi ketidakompakan tim dengan merancang sistem komunikasi dan jadwal yang efektif.',
            ],
        ];

        $questionOptionsMap = [
            0 => ['A' => 'Achievement', 'B' => 'Helping Others', 'C' => 'Freedom & Autonomy', 'D' => 'Collaboration'],
            1 => ['A' => 'Results-Driven', 'B' => 'Making a Difference', 'C' => 'Innovation', 'D' => 'Relationships'],
            2 => ['A' => 'Expertise', 'B' => 'Dependability', 'C' => 'Originality', 'D' => 'Community'],
            3 => ['A' => 'Analitis & Logika', 'B' => 'Komunikasi & Bahasa', 'C' => 'Seni & Praktek', 'D' => 'Sosial & Emosional'],
            4 => ['A' => 'Menganalisis data besar', 'B' => 'Membujuk & Menginspirasi', 'C' => 'Menciptakan produk fisik/digital', 'D' => 'Memimpin tim yang solid'],
            5 => ['A' => 'Fast-Paced', 'B' => 'Structured', 'C' => 'Flexible', 'D' => 'Supportive'],
            6 => ['A' => 'Opportunity-Seeker', 'B' => 'Stability-Seeker', 'C' => 'Adaptor', 'D' => 'Collaborator'],
        ];

        $archetypeCounts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        $valueOptions = [];
        $toolsOptions = [];
        $environmentOptions = [];

        foreach ($answers as $qIndex => $answer) {
            $archetypeKey = $archetypeMapping[$qIndex][$answer];
            $archetypeCounts[$archetypeKey]++;
            $optionDetail = $questionOptionsMap[$qIndex][$answer];
            if ($qIndex <= 2) {
                $valueOptions[] = $optionDetail;
            } elseif ($qIndex <= 4) {
                $toolsOptions[] = $optionDetail;
            } elseif ($qIndex >= 5) {
                $environmentOptions[] = $optionDetail;
            }
        }
        arsort($archetypeCounts);
        $keys = array_keys($archetypeCounts);

        $primaryKey = $keys[0];
        $secondaryKey = $keys[1];
        $primaryArch = $archetypesData[$primaryKey];
        $secondaryArch = $archetypesData[$secondaryKey];
        $archtypeCharacter = implode(', ', array_unique(array_merge($primaryArch['pola_jawaban'], $secondaryArch['pola_jawaban'])));
        $archtypeValueDesc = "Kamu memiliki kombinasi kuat antara {$primaryArch['name']} dan {$secondaryArch['name']}.".
            " Fokus utamamu adalah pada {$primaryArch['pola_jawaban'][0]} dengan dukungan dari {$secondaryArch['pola_jawaban'][0]}.".
            " Kamu mencari peran yang menggabungkan esensi {$primaryArch['description']} dan {$secondaryArch['description']}.";
        $idealFieldDesc = "Lingkungan idealmu memiliki elemen dari {$primaryArch['pola_jawaban'][3]} dan {$secondaryArch['pola_jawaban'][3]} dengan fokus pada ".end($environmentOptions).'.';

        $finalResult = [
            'archtype' => [
                'primary' => $primaryArch['name'],
                'secondary' => $secondaryArch['name'],
                'description' => $archtypeValueDesc,
            ],
            'archtype_character' => $archtypeCharacter,
            'archtype_values' => array_values(array_unique($valueOptions)),
            'tools' => implode(', ', array_values(array_unique($toolsOptions))),
            'ideal_field' => $idealFieldDesc,
            'carier_path' => array_values(array_unique(array_merge($primaryArch['carier_path'], $secondaryArch['carier_path']))),
            'personal_message' => $primaryArch['message']." Pikirkan bagaimana kamu dapat mengintegrasikan kekuatan {$secondaryArch['name']}.",
            'mission' => [
                'carier' => "Jelajahi Jalur: Cari profesi di persimpangan {$primaryArch['carier_path'][0]} dan {$secondaryArch['carier_path'][0]}.",
                'practice' => $primaryArch['mission_practice'],
            ],
        ];

        return $finalResult;
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
