<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodRecordController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json("OK");
});
Route::get('user/bulk/template', [UserController::class, 'getTemplate']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('user/bulk', [UserController::class, 'bulkCreate']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('check-username', [AuthController::class, 'checkUsername']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::patch('profile', [UserController::class, 'profile']);
    Route::get('mood_record/recap/{month}', [MoodRecordController::class, 'recapPerMonth']);
    Route::get('mood_record/check', [MoodRecordController::class, 'check']);
    Route::apiResource('mood_record', MoodRecordController::class)->except(['destroy', 'update']);
    Route::post('questionnaire/{type}', [QuestionnaireController::class, 'analyzeQuestionnaire'])->whereIn('type', ['secure', 'insecure']);
    Route::get('questionnaire/{type}', [QuestionnaireController::class, 'getAllQuestionnaires']);
    Route::get('questionnaire/{type}/{order}', [QuestionnaireController::class, 'getOneQuestionnaire']);
    Route::get('video/tag/{tag}', [VideoController::class, 'getByTag']);
    Route::get('quotes/{type}', [UserController::class, 'quotesOfTheDay'])->whereIn('type', ['secure', 'insecure']);
    Route::apiResource('video', VideoController::class);
});
Route::get('tag', [TagController::class, 'index']);
