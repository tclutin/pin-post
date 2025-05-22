<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\BannedMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\HashtagController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    Route::get('/who', [AuthController::class, 'who']);
    Route::post('/logout', [AuthController::class, 'logout']);

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

    //админка
    Route::middleware([RoleMiddleware::class.':admin'])->group(function () {
        Route::post("/admin/users/{id}/assign-role", [AdminController::class, 'assignRole']);  // назначить роль
    });
    Route::middleware([RoleMiddleware::class.':admin,moderator'])->group(function () {
        Route::post("/admin/users/{id}/ban", [AdminController::class, 'ban']);                 // забанить пользователя
        Route::post("/admin/users/{id}/unban", [AdminController::class, 'unban']);             // разбанить пользователя
        Route::delete("/admin/images/{id}", [AdminController::class, 'banImage']);             // забанить (удалить) фото
    });
});
