<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\HashtagController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CategoryController;

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/images', [ImageController::class, 'index']);                      // все изображения
    Route::get('/images/{id}', [ImageController::class, 'show']);                  // одно изображение с деталями
    Route::post('/images', [ImageController::class, 'store']);                     // создать изображение
    Route::delete('/images/{id}', [ImageController::class, 'destroy']);            // удалить изображение

    // изображения
    Route::get('/images/hashtag/{hashtagId}', [ImageController::class, 'imagesByHashtag']);    // по хештегу
    Route::get('/images/category/{categoryId}', [ImageController::class, 'imagesByCategory']); // по категории

    // комментарии
    Route::post('/images/{imageId}/comments', [CommentController::class, 'store']); 
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // хештеги
    Route::post('/hashtags', [HashtagController::class, 'create']); // создать
    Route::post('/images/{imageId}/hashtags', [HashtagController::class, 'attachToImage']); // привязать
    Route::delete('/images/{imageId}/hashtags', [HashtagController::class, 'detachFromImage']);// отвязать

    // лайки
    Route::post('/images/{imageId}/likes', [LikeController::class, 'store']);

    // категории
    Route::get('/categories', [CategoryController::class, 'index']);// список категорий
    Route::post('/images/{imageId}/category', [CategoryController::class, 'addToImage']);// привязать категорию
    Route::delete('/images/{imageId}/category', [CategoryController::class, 'removeFromImage']);// отвязать категорию
});