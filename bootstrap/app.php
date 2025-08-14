<?php

use App\Exceptions\GateRequest;
use App\Exceptions\NotFound;
use App\Exceptions\UniqueValueContraint;
use App\Exceptions\ValidationError;
use App\Http\Middleware\AutoLoginMiddleware;
use App\Http\Middleware\JwtTokenExistMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt' => JwtTokenExistMiddleware::class,
            'autologin' => AutoLoginMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(new NotFound());
        $exceptions->renderable(new GateRequest());
        $exceptions->renderable(new UniqueValueContraint());
        $exceptions->renderable(new ValidationError());
    })->create();
