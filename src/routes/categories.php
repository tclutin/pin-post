<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Middleware\BannedMiddleware;

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    Route::post('/images/{imageId}/category', [CategoryController::class, 'addToImage']);
    Route::delete('/images/{imageId}/category', [CategoryController::class, 'removeFromImage']);
});