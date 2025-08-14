<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ApiResponderTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtTokenExistMiddleware
{
    use ApiResponderTrait;
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/-/*') && app()->environment('local')) {
            $segments = explode('/', $request->path());
            $role = $segments[2] ?? null;

            if ($role) {
                $user = User::where('email', 'like', "{$role}%")->first();
                if ($user) {
                    Auth::login($user);
                }
            }
            return $next($request);
        }

        try {
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (\Throwable $th) {
            return $this->error('Invalid token', 401);
        }
    }
}
