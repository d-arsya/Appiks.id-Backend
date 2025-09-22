<?php

namespace App\Http\Controllers;

use App\Models\MoodRecord;
use App\Models\User;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    use ApiResponder;
    public function teacher()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'teacher';
        });
        $student = User::whereRole('student')->whereMentorId(Auth::id());
        $student_count = $student->count();
        $moods = MoodRecord::whereIn('user_id', User::where('mentor_id', Auth::id())->pluck('id'))->where('recorded', Carbon::today());
        $graph = $moods
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $mood_today_count = $moods->count();
        $mood_mean = null;
        $mood_secure_count = $moods->whereIn('status', ['happy', 'neutral'])->count();
        $mood_insecure_count = $moods->whereIn('status', ['sad', 'angry'])->count();
        $mood_mean = null;
        return $this->success([
            'student_count'       => (int) $student_count,
            'mood_today_count'    => (int) $mood_today_count,
            'mood_mean'           => $mood_mean,
            'mood_secure_count'   => (int) $mood_secure_count,
            'mood_insecure_count' => (int) $mood_insecure_count,
            'mood_today_graph'    => [
                "neutral" => (int) ($graph["neutral"] ?? 0),
                "sad"     => (int) ($graph["sad"] ?? 0),
                "happy"   => (int) ($graph["happy"] ?? 0),
                "angry"   => (int) ($graph["angry"] ?? 0),
            ]
        ]);
    }
}
