<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoodRecordSendRequest;
use App\Models\MoodRecord;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $status = in_array($request->status, ["happy", "netral"]) ? "Aman" : "Tidak Aman";
        $message = $moodResponses[$status][array_rand($moodResponses[$status])];
        return $this->created(["status" => $status, "pesan" => $message], 'Success record mood');
    }
}
