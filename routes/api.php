<?php

use App\Http\Controllers\AnomalyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\MoodRecordController;
use App\Http\Controllers\QuestionnaireAnswerController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json("OK");
});
Route::post('login', [AuthController::class, 'login']);
Route::middleware('jwt')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
Route::apiResource('user', UserController::class);
Route::apiResource('school', SchoolController::class);
Route::apiResource('room', RoomController::class);
Route::apiResource('article', ArticleController::class);
Route::apiResource('video', VideoController::class);
Route::apiResource('mood_records', MoodRecordController::class);
Route::apiResource('anomaly', AnomalyController::class);
Route::apiResource('schedule', ScheduleController::class);
Route::apiResource('meet', MeetController::class);
Route::apiResource('questionnaire', QuestionnaireController::class);
Route::apiResource('answer', QuestionnaireAnswerController::class);
