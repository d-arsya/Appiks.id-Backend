<?php

namespace App\Http\Controllers;

use App\Models\MoodRecord;
use App\Models\Report;
use App\Models\Sharing;
use App\Models\User;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    use ApiResponder;
    /**
     * Dashboard teacher datas
     * 
     * Mendapatkan data hitungan yang diperlukan di dashboard guru wali
     */
    #[Group('Dashboard')]
    public function teacher()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'teacher';
        });
        $student = User::whereRole('student')->whereMentorId(Auth::id());
        $student_count = $student->count();
        $moods = MoodRecord::whereIn('user_id', User::where('mentor_id', Auth::id())->pluck('id'))->where('recorded', Carbon::today());

        $mood_today_count = $moods->count();
        $mood_secure_count = $moods->whereIn('status', ['happy', 'neutral'])->count();
        $mood_insecure_count = $moods->whereIn('status', ['sad', 'angry'])->count();
        return $this->success([
            'student_count'       => (int) $student_count,
            'mood_today_count'    => (int) $mood_today_count,
            'mood_secure_count'   => (int) $mood_secure_count,
            'mood_insecure_count' => (int) $mood_insecure_count,
        ]);
    }
    /**
     * Dashboard counselor datas
     * 
     * Mendapatkan data hitungan yang diperlukan di dashboard guru BK
     */
    #[Group('Dashboard')]
    public function counselor()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'counselor';
        });
        $student = User::whereRole('student')->whereCounselorId(Auth::id());
        $student_count = $student->count();

        $report_today_count = Report::whereCreatedAt(Carbon::today())->whereIn('user_id', $student->pluck('id')->toArray())->count();
        $meet_today_count = Report::where('date', Carbon::today())->whereIn('user_id', $student->pluck('id')->toArray())->count();
        $sharing_today_count = Sharing::whereCreatedAt(Carbon::today())->whereIn('user_id', $student->pluck('id')->toArray())->count();
        return $this->success([
            'student_count'       => (int) $student_count,
            'report_today_count'    => (int) $report_today_count,
            'meet_today_count'   => (int) $meet_today_count,
            'sharing_today_count' => (int) $sharing_today_count,
        ]);
    }
}
