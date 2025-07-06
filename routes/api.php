<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\ScreenTimeController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\AIInsightController;
use App\Http\Controllers\ScreenDistanceAlertController;
use App\Http\Controllers\AppLimitRequestController;
use App\Http\Controllers\StreakController;

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/forgot-password', [AuthenticationController::class, 'sendResetCode']);
Route::post('/verify-reset-code', [AuthenticationController::class, 'verifyResetCode']);
Route::post('/reset-password', [AuthenticationController::class, 'resetPassword']);
  Route::get('/app-limits/approve/{id}', [AppLimitRequestController::class, 'approveLimit']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::delete('/account/delete', [AuthenticationController::class, 'deleteAccount']);
    Route::put('/account/update', [AuthenticationController::class, 'updateAccount']);
    Route::post('/record-screen-time', [ScreenTimeController::class, 'recordScreenTime']);
    Route::get('/getTodayScreenTime', [ScreenTimeController::class, 'getTodayScreenTime']);
    Route::post('/send-daily-report', [DailyReportController::class, 'sendDailyReport']);
    Route::get('/reports', [DailyReportController::class, 'getReports']);
    Route::post('/ai/generate-question', [AIInsightController::class, 'generateReflection']);
    Route::post('/ai/analyze-emotion', [AIInsightController::class, 'analyzeEmotion']);
    Route::post('/ai/suggest-activity', [AIInsightController::class, 'suggestActivity']);
    Route::post('/ai-insight/store', [AIInsightController::class, 'generateAndStoreInsight']);   
    Route::post('/app-limits/request', [AppLimitRequestController::class, 'requestLimit']);
    Route::get('/app-limit-request/status/{id}', [AppLimitRequestController::class, 'checkStatus']);
  
    Route::get('/streak/{userId}', [StreakController::class, 'show']);
    Route::post('/screen-distance/alert', [ScreenDistanceAlertController::class, 'logAlert']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
