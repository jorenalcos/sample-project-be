<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This API uses session-based authentication (cookie + server session).
| CSRF is disabled for /api/* in bootstrap/app.php for simplicity.
|
*/

Route::middleware(['web'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');
        Route::get('me', [AuthController::class, 'me'])->middleware('auth');
    });

    Route::middleware('auth')->group(function () {
        Route::apiResource('jobs', JobController::class);
    });
});

