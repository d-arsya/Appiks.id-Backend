<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponderTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtTokenExistMiddleware
{
    use ApiResponderTrait;
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (\Throwable $th) {
            return $this->error('Invalid token', 401);
        }
    }
}
