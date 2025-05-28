<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;
use App\Http\Middleware\BannedMiddleware;

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    Route::post('/images/{imageId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});