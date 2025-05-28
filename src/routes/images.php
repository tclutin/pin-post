<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ImageController;

Route::get('/images', [ImageController::class, 'index']);
Route::get('/images/{id}', [ImageController::class, 'show']);

Route::middleware(['auth:sanctum', \App\Http\Middleware\BannedMiddleware::class])->group(function () {
    Route::post('/images', [ImageController::class, 'store']);
    Route::delete('/images/{id}', [ImageController::class, 'destroy']);
    Route::match(['put', 'patch'], '/images/{id}', [ImageController::class, 'update']);
    Route::get('/images/user/{userId}', [ImageController::class, 'imagesByUser']);
    Route::get('/images/hashtag/{hashtagId}', [ImageController::class, 'imagesByHashtag']);
    Route::get('/images/category/{categoryId}', [ImageController::class, 'imagesByCategory']);
});