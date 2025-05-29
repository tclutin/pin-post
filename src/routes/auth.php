<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', \App\Http\Middleware\BannedMiddleware::class])->group(function () {
    Route::get('/who', [AuthController::class, 'who']);
    Route::post('/logout', [AuthController::class, 'logout']);
});