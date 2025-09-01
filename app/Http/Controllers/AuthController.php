<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckUsernameRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;

class AuthController extends Controller
{
    use ApiResponder;
    /**
     * Get JWT token(login)
     */
    #[Group('Authentication')]
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::with(['school', 'room', 'mentor'])->where('username', $credentials["username"])->first();
        if (! $user) {
            return $this->error('Unauthorized', 401, null);
        }
        if (! $token = Auth::claims([
            'name' => $user->name,
            'username' => $user->username,
            'identifier' => $user->identifier,
            'role' => $user->role,
            'verified' => $user->verified,
            'room' => $user->room->name ?? null,
            'mentor' => $user->mentor->name ?? null,
            'school' => $user->school->name
        ])->attempt($credentials)) {
            return $this->error('Unauthorized', 401, null);
        }
        return $this->success([
            "token" => $token,
            "expiresIn" => now()
                ->addMinutes(Auth::factory()->getTTL())
                ->setTimezone(config("app.timezone"))
                ->toIso8601String()
        ]);
    }

    /**
     * Get the autheticated user profile
     */
    #[Group('User')]
    public function me()
    {
        $user = Auth::user();
        $data = User::with(['school', 'room', 'mentor'])->where('id', $user->id)->first();
        return $this->success(new UserResource($data));
    }

    /**
     * Invalidate the JWT (logout)
     */
    #[Group('Authentication')]
    public function logout()
    {
        Auth::logout(true);
        return $this->success(null, 'Success logout');
    }

    /**
     * Get JWT refreshed token
     */
    #[Group('Authentication')]
    public function refresh()
    {
        $token = Auth::refresh();
        return $this->success([
            "token" => $token,
            "expiresIn" => now()
                ->addMinutes(Auth::factory()->getTTL())
                ->setTimezone(config("app.timezone"))
                ->toIso8601String()
        ]);
    }

    /**
     * Is username used
     */
    #[Group('User')]
    public function checkUsername(CheckUsernameRequest $request)
    {
        return $this->success(["username" => true], "Username not exist");
    }
}
