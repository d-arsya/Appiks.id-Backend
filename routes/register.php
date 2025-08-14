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

function register()
{
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::apiResource('school', SchoolController::class);
    Route::apiResource('room', RoomController::class);
    Route::apiResource('article', ArticleController::class);
    Route::apiResource('video', VideoController::class);
    Route::apiResource('mood_record', MoodRecordController::class)->except(['destroy', 'update']);
    Route::apiResource('anomaly', AnomalyController::class)->except(['destroy']);
    Route::apiResource('user', UserController::class);
    Route::apiResource('schedule', ScheduleController::class)->only(['index', 'update']);
    Route::apiResource('meet', MeetController::class);
    Route::apiResource('questionnaire', QuestionnaireController::class)->only(['index', 'show']);
    Route::apiResource('answer', QuestionnaireAnswerController::class)->only(['index', 'show', 'store']);
}
