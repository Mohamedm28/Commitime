<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppLimitRequestController;


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
Route::get('/app-limits/approve/{id}', [AppLimitRequestController::class, 'approveLimit']);

