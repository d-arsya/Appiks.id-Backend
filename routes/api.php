<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MoodRecordController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SharingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('coba', [QuestionnaireController::class, 'coba']);
Route::get('/', function () {
    return response()->json('OK');
});
Route::get('user/bulk/template', [UserController::class, 'getTemplate']);
Route::post('login', [AuthController::class, 'login']);
Route::get('tag', [TagController::class, 'index']);
Route::controller(LocationController::class)->group(function () {
    Route::get('province', 'province')->name('province');
    Route::get('city/{province}', 'city')->name('city');
    Route::get('district/{city}', 'district')->name('district');
    Route::get('village/{district}', 'village')->name('village');
});
Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('room-student-count', [RoomController::class, 'roomStudentCount']);
    Route::get('room/school/{school}', [RoomController::class, 'roomOfSchool']);
    Route::get('content', [VideoController::class, 'allContents']);
    Route::get('video/tag/{tag}', [VideoController::class, 'getByaTag']);
    Route::get('video/{video:video_id}', [VideoController::class, 'getVideoDetailId']);
    Route::get('article/tag/{tag}', [ArticleController::class, 'getByTag']);
    Route::get('article/{article}', [ArticleController::class, 'getArticle']);
    Route::get('quote/mood', [QuoteController::class, 'getByType']);
    Route::get('quote/daily', [QuoteController::class, 'getDaily']);
    Route::get('check-username', [AuthController::class, 'checkUsername']);
    Route::get('questionnaire', [QuestionnaireController::class, 'getAllQuestionnaires']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('article-update/{article}', [ArticleController::class, 'update']);
    Route::post('questionnaire/{type}', [QuestionnaireController::class, 'analyzeQuestionnaire'])->whereIn('type', ['secure', 'insecure']);
    Route::controller(MoodRecordController::class)->group(function () {
        Route::get('mood_record/recap/{month}', 'recapPerMonth');
        Route::get('mood_record/check', 'check');
        Route::get('mood_record/today', 'today');
        Route::get('mood_record/streaks', 'streaks');
        Route::get('mood-record/student/{user:username}', 'recordsOfStudent');
        Route::get('mood-trends/{school}/{type}', 'getMoodTrendSchool')->whereIn('type', ['monthly', 'weekly']);
        Route::get('mood-record/pattern/{user:username}/{type}', 'moodHistory')->whereIn('type', ['monthly', 'weekly']);
    });
    Route::controller(UserController::class)->group(function () {
        Route::post('user/bulk', 'bulkCreate');
        Route::post('user/admin', 'adminCreate');
        Route::patch('profile', 'profile');
        Route::patch('edit-user/{user:username}', 'edit');
        Route::patch('edit-profile', 'editProfile');
        Route::delete('user/{user:username}', 'destroy');
    });
    Route::controller(SharingController::class)->group(function () {
        Route::patch('sharing/reply/{sharing}', 'reply');
        Route::post('sharing', 'store');
        Route::get('sharing', 'index');
        Route::get('sharing/student/{user:username}', 'sharingOfStudent');
        Route::get('sharing/{sharing}', 'show');
    });
    Route::controller(ReportController::class)->group(function () {
        Route::patch('report/confirm/{report}', 'confirm');
        Route::patch('report/close/{report}', 'close');
        Route::patch('report/cancel/{report}', 'cancel');
        Route::patch('report/reschedule/{report}', 'reschedule');
        Route::post('report', 'store');
        Route::get('report/student/{user:username}', 'reportOfStudent');
        Route::get('report', 'index');
        Route::get('report/{report}', 'show');
    });
    Route::apiResource('room', RoomController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    Route::apiResource('video', VideoController::class)->except(['show']);
    Route::apiResource('articles', ArticleController::class)->except(['show', 'update']);
    Route::apiResource('quote', QuoteController::class)->except(['update']);
    Route::apiResource('mood_record', MoodRecordController::class)->except(['destroy', 'update']);
    Route::apiResource('school', SchoolController::class);
    Route::prefix('dashboard')->group(function () {
        Route::get('sharing-count', [SharingController::class, 'getSharingCount']);
        Route::get('report-count', [ReportController::class, 'getReportCount']);
        Route::get('report-graph', [ReportController::class, 'getReportGraph']);
        Route::get('room-count', [RoomController::class, 'getRoomCount']);
        Route::get('mood-trends', [MoodRecordController::class, 'getMoodTrend']);
        Route::get('mood-graph', [MoodRecordController::class, 'getMoodGraph']);
        Route::get('mood-statistics', [MoodRecordController::class, 'moodStatistics']);
        Route::get('latest-content', [VideoController::class, 'getLatestContent']);
        Route::get('today-content', [VideoController::class, 'getTodayContent']);
        Route::controller(DashboardController::class)->group(function () {
            Route::get('super', 'super');
            Route::get('headteacher', 'headteacher');
            Route::get('teacher', 'teacher');
            Route::get('admin', 'admin');
            Route::get('counselor', 'counselor');
            Route::get('content', 'content');
            Route::get('content-statistics', 'contentStatistics');
        });
        Route::controller(UserController::class)->group(function () {
            Route::get('student', 'getStudents');
            Route::get('users', 'getUsers');
            Route::get('users/{username}', 'getUserDetail');
            Route::get('users/type/{type}', 'getUsersByType')->whereIn('type', ['student', 'teacher', 'counselor', 'headteacher', 'admin']);
            Route::get('latest-user', 'getLatestUser');
            Route::get('today-user', 'getTodayUser');
            Route::post('users', 'store');
        });
    });

    Route::prefix('notification')->group(function () {
        Route::get('latest-sharing', [SharingController::class, 'latestOfStudent']);
        Route::get('latest-report', [ReportController::class, 'latestOfStudent']);
    });
});
