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
     * 
     * Mengecek apakah murid bisa melakukan rekam mood hari ini
     */
    #[Group('Mood Record')]
    public function check()
    {
        $mood = MoodRecord::where('user_id', Auth::id())->where('recorded', Carbon::today())->get();
        return $this->success(["can" => $mood->count() == 0]);
    }

    /**
     * Check user's mood today
     * 
     * Mengecek status mood siswa hari ini
     */
    #[Group('Mood Record')]
    public function today()
    {
        $mood = MoodRecord::where('user_id', Auth::id())->where('recorded', Carbon::today())->first();
        if ($mood) {
            return $this->success(["type" => $mood->status, "status" => in_array($mood->status, ['happy', 'neutral']) ? 'secure' : 'insecure']);
        }
        return $this->error("User doesn't have mood record today", 404, null);
    }

    /**
     * Check user's streak point
     * 
     * Menghitung poin streak
     */
    #[Group('Mood Record')]
    public function streaks()
    {
        $userId = Auth::id();
        $streak = 0;
        $date   = Carbon::today();

        while (
            MoodRecord::where('user_id', $userId)
            ->whereDate('recorded', $date)
            ->exists()
        ) {
            $streak++;
            $date->subDay(); // mundur 1 hari
        }

        return $this->success([
            "streak" => $streak,
        ]);
    }

    /**
     * Get user mood recaps by month
     * 
     * Mendapatkan rekapitulasi rekaman mood milik murid secara bulanan. Hanya bisa diakses oleh murid
     * @param string $month YYYY-MM ex. 2025-09
     */
    #[Group('Mood Record')]
    public function recapPerMonth(string $month)
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'student';
        });
        $mood = MoodRecord::where('user_id', Auth::id())->where('recorded', 'like', "$month-__")->orderBy('recorded')->get();
        return $this->success(MoodRecordResource::collection($mood));
    }

    /**
     * Record mood the authenticated user
     * 
     * Merekam mood siswa pada hari ini dan akan mengembalikan status serta quotes
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
     * Get mood trends a year
     */
    #[Group('Dashboard')]
    public function getMoodTrend()
    {
        Gate::authorize('dashboard-data');

        $moods = MoodRecord::selectRaw('MONTH(recorded) as month, status, COUNT(*) as total')
            ->groupBy('month', 'status')
            ->orderBy('month')
            ->get();

        // group per bulan
        $grouped = $moods->groupBy('month');

        $result = [];

        foreach ($grouped as $month => $items) {
            // ambil status dengan jumlah terbesar
            $top = $items->sortByDesc('total')->first();

            $result[$this->monthName($month)] = [
                'status' => $top->status,
                'total'  => (int) $top->total,
            ];
        }

        return $this->success($result);
    }

    private function monthName($month)
    {
        return \Carbon\Carbon::create()->month($month)->format('F'); // ex: "January"
        // atau 'M' kalau mau singkat: "Jan"
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
            "neutral" => (int) ($moods["neutral"] ?? 0),
            "sad"     => (int) ($moods["sad"] ?? 0),
            "happy"   => (int) ($moods["happy"] ?? 0),
            "angry"   => (int) ($moods["angry"] ?? 0),
        ]);
    }

    /**
     * Get mood history of the student
     * 
     * Mendapatkan rekapitulasi rekam mood siswa berdasarkan username siswa tersebut. Tersedia opsi bulanan dan mingguan (terakhir). Hanya bisa diakses oleh BK maupun Wali dari siswa tersebut
     * @param string $type weekly | monthly
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
    public function moodHistory(Request $request, User $user, string $type)
    {
        Gate::allowIf(function (User $authUser) use ($user) {
            return ($authUser->role == 'counselor' && $authUser->id === $user->counselor_id) || $authUser->role == 'teacher' && $authUser->id === $user->mentor_id;
        });
        $request->validate(["type" => "required|in:weekly,monthly"]);
        $query = MoodRecord::where('user_id', $user->id);

        if ($type === 'monthly') {
            // semua data dalam bulan ini
            $query->whereMonth('recorded', now()->month)
                ->whereYear('recorded', now()->year);
        } elseif ($type === 'weekly') {
            // semua data dalam minggu ini
            $query->whereBetween('recorded', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }

        $moods = $query->orderBy('recorded')->get();
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
