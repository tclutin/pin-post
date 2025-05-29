<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HashtagController;
use App\Http\Middleware\BannedMiddleware;

Route::get('/hashtags', [HashtagController::class, 'index']);

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    Route::post('/hashtags', [HashtagController::class, 'create']);
    Route::post('/images/{imageId}/hashtags', [HashtagController::class, 'attachToImage']);
    Route::delete('/images/{imageId}/hashtags', [HashtagController::class, 'detachFromImage']);
});