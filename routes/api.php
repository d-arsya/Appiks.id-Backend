<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

include "register.php";


Route::get('/', function () {
    return response()->json("OK");
});
Route::post('login', [AuthController::class, 'login']);
Route::middleware('jwt')->group(function () {
    register();
    if (app()->environment('local')) {
        $emails = [
            "super",
            "admin",
            "teacher",
            "headteacher",
            "student",
            "conselor",
            "super@super.com",
            "admin@school1.com",
            "admin@school2.com",
            "conselor@school1.com",
            "conselor@school2.com",
            "headteacher@school1.com",
            "headteacher@school2.com",
            "teacher@room1.com",
            "teacher@room2.com",
            "teacher@room3.com",
            "teacher@room4.com",
            "student1@room1.com",
            "student2@room1.com",
            "student1@room2.com",
            "student2@room2.com",
            "student1@room3.com",
            "student2@room3.com",
            "student1@room4.com",
            "student2@room4.com",
        ];

        foreach ($emails as $email) {
            Route::prefix("-/{$email}")->group(function () {
                register(); // your reusable route function
            });
        }
    }
});
