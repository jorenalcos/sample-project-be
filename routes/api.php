<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\LikeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This API uses token-based authentication (Laravel Sanctum).
|
*/

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('jobs', JobController::class);

    Route::get('jobs/{job}/comments', [CommentController::class, 'index']);
    Route::post('jobs/{job}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

    Route::post('jobs/{job}/like', [LikeController::class, 'store']);
    Route::delete('jobs/{job}/like', [LikeController::class, 'destroy']);

    Route::get('applications', [ApplicationController::class, 'index']);
    Route::post('jobs/{job}/apply', [ApplicationController::class, 'store'])->middleware('role:user');
    Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->middleware('role:company');
});

