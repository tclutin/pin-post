<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LikeController;
use App\Http\Middleware\BannedMiddleware;

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    Route::post('/images/{imageId}/likes', [LikeController::class, 'store']);
});