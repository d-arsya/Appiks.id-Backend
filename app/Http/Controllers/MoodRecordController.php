<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoodRecordSendRequest;
use App\Http\Resources\MoodRecordResource;
use App\Models\MoodRecord;
use App\Models\User;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MoodRecordController extends Controller
{
    use ApiResponder;
    /**
     * Is user can record today's mood
     */
    #[Group('Mood Record')]
    public function check()
    {
        $mood = MoodRecord::where('user_id', Auth::id())->where('recorded', Carbon::today())->get();
        return $this->success(["can" => $mood->count() == 0]);
    }
    /**
     * Get user mood recaps by month
     * @param string $month YYYY-MM ex. 2025-09
     */
    #[Group('Mood Record')]
    public function recapPerMonth(string $month)
    {
        request()->validate([
            'month' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
        ]);
        $mood = MoodRecord::where('user_id', Auth::id())->where('recorded', 'like', "$month-__")->get();
        return $this->success(MoodRecordResource::collection($mood));
    }

    /**
     * Record mood the authenticated user
     */
    #[Group('Mood Record')]
    public function store(MoodRecordSendRequest $request)
    {

        $moodResponses = [
            'Aman' => [
                "Terima kasih sudah berbagi mood hari ini, semoga harimu semakin menyenangkan 🌸",
                "Senang mendengar kamu merasa baik, terus jaga semangat positifmu ya ✨",
                "Kamu luar biasa! Semoga energi positifmu menular ke sekitarmu 💡",
                "Hebat, kamu sudah meluangkan waktu mengenali perasaanmu 🙌",
                "Terus pertahankan mood baikmu, tapi ingat tidak apa-apa kalau suatu saat merasa berbeda 🌈",
                "Bagus sekali! Kamu sudah menjaga diri dengan baik 🤍",
                "Hari yang indah dimulai dari perasaan yang baik, semoga harimu penuh kebahagiaan 🌞",
                "Kamu keren! Terus semangat untuk menjadi versi terbaik dari dirimu 💪",
                "Terima kasih sudah jujur pada perasaanmu, itu tanda kamu peduli pada dirimu sendiri 🌼",
                "Semoga rasa baikmu hari ini membawa kebaikan juga untuk orang-orang di sekitarmu 💖",
            ],
            'Tidak Aman' => [
                "Terima kasih sudah jujur membagikan perasaanmu, ingat kamu tidak sendirian 🤗",
                "Wajar kok merasa seperti itu, semoga segera ada hal baik yang membuatmu tenang 🌿",
                "Perasaanmu penting. Jika ingin bercerita, ada teman atau guru yang siap mendengarkan 🤍",
                "Kamu sudah hebat bisa mengenali perasaan ini, itu langkah awal untuk menjadi lebih kuat 💪",
                "Tidak apa-apa merasa sedih atau marah, itu manusiawi. Yang penting jangan memendam sendiri 🌧️",
                "Hari ini mungkin berat, tapi percayalah kamu bisa melewati ini 🌟",
                "Perasaanmu valid. Jangan takut untuk mencari dukungan bila diperlukan 🫂",
                "Kamu tidak harus selalu kuat sendirian, ada banyak orang yang peduli padamu 💌",
                "Terima kasih sudah mau berbagi. Semoga esok lebih baik untukmu 🌅",
                "Kamu berharga, apa pun mood-mu hari ini. Jangan lupa istirahat dan jaga dirimu 🌷",
            ],
        ];

        MoodRecord::create($request->all());
        $status = in_array($request->status, ["happy", "neutral"]) ? "Aman" : "Tidak Aman";
        $message = $moodResponses[$status][array_rand($moodResponses[$status])];
        return $this->created(["status" => $status, "pesan" => $message], 'Success record mood');
    }

    /**
     * Get mood count graph
     */
    #[Group('Dashboard')]
    public function getMoodGraph()
    {
        Gate::authorize('dashboard-data');
        $moods = MoodRecord::whereRecorded(now()->toDateString())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return $this->success([
            "neutral" => (int) $moods["neutral"],
            "sad" => (int) $moods["sad"],
            "happy" => (int) $moods["happy"],
            "angry" => (int) $moods["angry"],
        ]);
    }

    /**
     * Get mood history of the student
     * @response array{
     *   data: array{
     *     recap: array{
     *       happy: int,
     *       angry: int,
     *       sad: int,
     *       neutral: int
     *     },
     *     mean: "secure"|"insecure",
     *     moods: array<array{
     *       recorded: "2025-08-12",
     *       status: "happy"|"neutral"|"sad"|"angry"
     *     }>,
     *     user: array{
     *       name: string,
     *       phone: string
     *     }
     *   }
     * }
     */
    #[Group('Mood Record')]
    public function moodHistory(Request $request, User $user)
    {
        Gate::allowIf(function (User $authUser) use ($user) {
            return $authUser->role == 'counselor' && $authUser->id === $user->counselor_id;
        });
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        // take last month’s mood records
        $moods = $user->mood()
            ->whereRaw('DATE_FORMAT(recorded, "%Y-%m") = ?', [$lastMonth])
            ->get();

        // count by status
        $recap = $moods
            ->groupBy('status')
            ->map(fn($items) => $items->count());

        // secure = neutral + happy
        $secure   = ($recap['neutral'] ?? 0) + ($recap['happy'] ?? 0);
        $insecure = ($recap['angry'] ?? 0) + ($recap['sad'] ?? 0);

        // tentukan mean status
        $mean = $secure > $insecure ? 'secure' : 'insecure';

        return $this->success(compact('recap', 'mean', 'moods', 'user'));
    }
}
