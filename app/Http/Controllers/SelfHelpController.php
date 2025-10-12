<?php

namespace App\Http\Controllers;

use App\Models\SelfHelp;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SelfHelpController extends Controller
{
    use ApiResponder;

    /**
     * Get self help response of student
     *
     * Hanya bisa diakses oleh super admin
     *
     * @param  string  $type  Daily Journaling | Gratitude Journal | Grounding Technique | Sensory Relaxation
     */
    #[Group('Self Help')]
    public function getByType(Request $request, string $type, User $user)
    {
        Gate::allowIf(function ($auth) use ($user) {
            return $auth->role == 'teacher' && $auth->id == $user->mentor_id;
        });
        $responses = SelfHelp::whereType($type)->whereUserId($user->id)->get();

        return $this->success($responses);
    }

    /**
     * Create daily journaling
     */
    #[Group('Self Help')]
    public function createDaily(Request $request)
    {
        Gate::allowIf(function ($user) {
            return $user->role == 'student';
        });
        $request->validate([
            'mind' => 'required|string',
            'story' => 'required|string',
            'category' => 'required|string',
            'emotions' => 'required|array',
            'emotions.*' => 'required|string',
        ]);
        $data = SelfHelp::create(['type' => 'Daily Journaling', 'user_id' => Auth::id(), 'content' => $request->all()]);

        return $this->success($data);
    }

    /**
     * Create gratitude journal
     */
    #[Group('Self Help')]
    public function createGratitude(Request $request)
    {
        Gate::allowIf(function ($user) {
            return $user->role == 'student';
        });
        $request->validate([
            'progress' => 'required|array',
            'progress.*' => 'required|string',
            'achievement' => 'required|array',
            'achievement.*' => 'required|string',
            'apreciation' => 'required|string',
        ]);
        $data = SelfHelp::create(['type' => 'Gratitude Journal', 'user_id' => Auth::id(), 'content' => $request->all()]);

        return $this->success($data);
    }

    /**
     * Create grounding technique
     */
    #[Group('Self Help')]
    public function createGrounding(Request $request)
    {
        Gate::allowIf(function ($user) {
            return $user->role == 'student';
        });
        $request->validate([
            'one' => 'required|array|size:1',
            'one.*' => 'required|string',
            'two' => 'required|array|size:2',
            'two.*' => 'required|string',
            'three' => 'required|array|size:3',
            'three.*' => 'required|string',
            'four' => 'required|array|size:4',
            'four.*' => 'required|string',
            'five' => 'required|array|size:5',
            'five.*' => 'required|string',
        ]);
        $data = SelfHelp::create(['type' => 'Grounding Technique', 'user_id' => Auth::id(), 'content' => $request->all()]);

        return $this->success($data);
    }

    /**
     * Create sensory relaxation
     */
    #[Group('Self Help')]
    public function createSensory(Request $request)
    {
        Gate::allowIf(function ($user) {
            return $user->role == 'student';
        });
        $request->validate([
            'activity' => 'required|array',
            'activity.*' => 'required|string',
            'reflection' => 'required|string',
        ]);
        $data = SelfHelp::create(['type' => 'Sensory Relaxation', 'user_id' => Auth::id(), 'content' => $request->all()]);

        return $this->success($data);
    }
}
