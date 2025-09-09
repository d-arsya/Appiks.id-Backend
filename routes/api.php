<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodRecordController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SharingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Models\MoodRecord;
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
    Route::get('mood_record/recap/{month}', [MoodRecordController::class, 'recapPerMonth']);
    Route::get('mood_record/check', [MoodRecordController::class, 'check']);
    Route::get('questionnaire', [QuestionnaireController::class, 'getAllQuestionnaires']);
    Route::get('video/tag/{tag}', [VideoController::class, 'getByTag']);
    Route::get('quotes/{type}', [UserController::class, 'quotesOfTheDay'])->whereIn('type', ['secure', 'insecure']);
    Route::post('questionnaire/{type}', [QuestionnaireController::class, 'analyzeQuestionnaire'])->whereIn('type', ['secure', 'insecure']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::patch('profile', [UserController::class, 'profile']);
    Route::patch('edit-profile', [UserController::class, 'editProfile']);
    Route::apiResource('video', VideoController::class);
    Route::apiResource('mood_record', MoodRecordController::class)->except(['destroy', 'update']);
    Route::controller(SharingController::class)->group(function () {
        Route::patch('sharing/reply/{sharing}', 'reply');
        Route::post('sharing', 'store');
        Route::get('sharing', 'index');
        Route::get('sharing/{sharing}', 'show');
    });
    Route::controller(ReportController::class)->group(function () {
        Route::patch('report/confirm/{report}', 'confirm');
        Route::patch('report/close/{report}', 'close');
        Route::patch('report/cancel/{report}', 'cancel');
        Route::post('report', 'store');
        Route::get('report', 'index');
        Route::get('report/{report}', 'view');
    });
    Route::prefix('dashboard')->group(function () {
        Route::get('student-count', [UserController::class, 'getStudentCount']);
        Route::get('report-count', [ReportController::class, 'getReportCount']);
        Route::get('schedule-count', [ReportController::class, 'getScheduleCount']);
        Route::get('sharing-count', [SharingController::class, 'getSharingCount']);
        Route::get('report-graph', [ReportController::class, 'getReportGraph']);
        Route::get('mood-graph', [MoodRecordController::class, 'getMoodGraph']);
    });
    Route::get('mood-record/pattern/{user:username}/{type}', [MoodRecordController::class, 'moodHistory'])->whereIn('type', ['monthly', 'weekly']);
});
Route::get('tag', [TagController::class, 'index']);
