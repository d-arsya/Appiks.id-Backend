<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDOException;

class AuthController extends Controller
{
    use ApiResponderTrait;

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|string",
            "password" => "required|string",
        ]);
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);
            return $this->success(new UserResource($user), "Success", 201);
        } catch (\Throwable $th) {
            if ($th instanceof PDOException && $th->getCode() == "23000") {
                return $this->error("Email {$request->email} already used", 400);
            }
            return $this->error($th->getMessage(), 400);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email|string",
            "password" => "required|string",
        ]);
        $credentials = request(['email', 'password']);
        $user = User::where('email', request('email'))->first();
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
        try {
            $token = Auth::refresh();
            return $this->success([
                "token" => $token,
                "expiresIn" => now()
                    ->addMinutes(Auth::factory()->getTTL())
                    ->setTimezone(config("app.timezone"))
                    ->format('D, d M Y H:i:s') . ' ' . config("app.timezone")
            ]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 401, null);
        }
    }
}
