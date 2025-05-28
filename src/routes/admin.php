<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Middleware\BannedMiddleware;
use App\Http\Middleware\RoleMiddleware;

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    
    // Только для админа
    Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
        Route::post('/admin/users/{id}/assign-role', [AdminController::class, 'assignRole']);
        Route::get('/admin/stats/users', [AdminController::class, 'getUserStats']);
        Route::get('/admin/stats/week_stats', [AdminController::class, 'getLastWeekStats']);
        Route::get('/admin/stats/regs', [AdminController::class, 'getRegistrationPlot']);
        Route::get('/admin/users', [AdminController::class, 'getUsers']);
    });

    // Для админа и модератора
    Route::middleware([RoleMiddleware::class . ':admin,moderator'])->group(function () {
        Route::post('/admin/users/{id}/ban', [AdminController::class, 'ban']);
        Route::post('/admin/users/{id}/unban', [AdminController::class, 'unban']);
        Route::delete('/admin/images/{id}', [AdminController::class, 'banImage']);
        Route::delete('/admin/comments/{id}', [AdminController::class, 'banComment']);
        Route::get('/admin/users', [AdminController::class, 'getUsers']); // продублировано, можно оставить одно
    });
});