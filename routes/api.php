<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodRecordController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json("OK");
});
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('check-username', [AuthController::class, 'checkUsername']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::patch('profile', [UserController::class, 'profile']);
    Route::get('mood_record/check', [MoodRecordController::class, 'check']);
    Route::apiResource('mood_record', MoodRecordController::class)->except(['destroy', 'update']);
});
Route::get('questionnaire/tes', [QuestionnaireController::class, 'tes']);
Route::get('questionnaire/her', [QuestionnaireController::class, 'her']);
Route::post('questionnaire/{type}', [QuestionnaireController::class, 'analyzeQuestionnaire'])->whereIn('type', ['secure', 'insecure']);
Route::get('questionnaire/{type}', [QuestionnaireController::class, 'getAllQuestionnaires']);
Route::get('questionnaire/{type}/{order}', [QuestionnaireController::class, 'getOneQuestionnaire']);
