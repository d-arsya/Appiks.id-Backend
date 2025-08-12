<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponderTrait;

class AuthController extends Controller
{
    use ApiResponderTrait;

    public function register(UserRegisterRequest $request)
    {
        $payload = $request->validated();
        $user = User::create($payload);
        return $this->success(new UserResource($user), "Success", 201);
    }
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials["email"])->first();
        if (! $token = Auth::claims($user->toArray())->attempt($credentials)) {
            return $this->error('Unautorized', 401, null);
        }
        return $this->success([
            "token" => $token,
            "expiresIn" => now()
                ->addMinutes(Auth::factory()->getTTL())
                ->setTimezone(config("app.timezone"))
                ->format('D, d M Y H:i:s') . ' ' . config("app.timezone")
        ]);
    }

    public function me()
    {
        $user = Auth::user();
        return $this->success(new UserResource($user));
    }

    public function logout()
    {
        Auth::logout(true);
        return $this->success(null, 'Success logout');
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return $this->success([
            "token" => $token,
            "expiresIn" => now()
                ->addMinutes(Auth::factory()->getTTL())
                ->setTimezone(config("app.timezone"))
                ->format('D, d M Y H:i:s') . ' ' . config("app.timezone")
        ]);
    }
}
