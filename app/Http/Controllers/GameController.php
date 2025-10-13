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
            return $user->role === 'student';
        });

        $user = Auth::user();
        $cirrus = $user->cloud;
        if ($cirrus->last_in && $cirrus->last_in == now()->toDateString()) {
            return $this->error('You have checked in today.');
        }
        $request->validate([
            'water' => 'required|integer',
        ]);
        $yesterday = now()->subDay()->toDateString();

        if ($cirrus->last_in === $yesterday) {
            $newStreak = $cirrus->streak == 7 ? 7 : $cirrus->streak + 1;
        } else {
            $newStreak = 1;
        }
        $cirrus->update([
            'water' => $cirrus->water + $request->water,
            'streak' => $newStreak,
            'last_in' => now()->toDateString(),
        ]);

        return $this->success(new CloudResource($cirrus));
    }
}
