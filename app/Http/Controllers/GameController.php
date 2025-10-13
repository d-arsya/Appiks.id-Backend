<?php

namespace App\Http\Controllers;

use App\Http\Resources\CloudResource;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GameController extends Controller
{
    use ApiResponder;

    /**
     * Get cirrus profile
     */
    #[Group('Game')]
    public function cirrus()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'student';
        });
        $user = Auth::user();
        $cirrus = $user->cloud;

        return $this->success(new CloudResource($cirrus));
    }

    /**
     * Buy with water
     */
    #[Group('Game')]
    public function buy(Request $request)
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'student';
        });
        $user = Auth::user();
        $cirrus = $user->cloud;
        $request->validate([
            'water' => 'required|integer|max:'.$cirrus->water,
            'exp' => 'required|integer',
            'happiness' => 'required|integer',
        ]);
        $cirrus->update([
            'exp' => $cirrus->exp + $request->exp,
            'water' => $cirrus->water - $request->water,
            'happiness' => $cirrus->happiness + $request->happiness,
        ]);

        return $this->success(new CloudResource($cirrus));
    }

    /**
     * Claim check in
     *
     * Kalau hari ini sudah check in gabisa lagi
     */
    #[Group('Game')]
    public function claim(Request $request)
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'student';
        });
        $user = Auth::user();
        if ($user->cloud->last_in == now()->toDateString()) {
            return $this->error('You have check in today');
        }
        $cirrus = $user->cloud;
        $request->validate([
            'water' => 'required|integer',
        ]);
        $cirrus->update([
            'water' => $cirrus->water + $request->water,
            'streak' => $cirrus->streak + ($cirrus->streak == 7 ? 0 : 1),
        ]);

        return $this->success(new CloudResource($cirrus));
    }
}
