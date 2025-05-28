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

// с параметрами запроса: ?author=string&description=string&category=int&hashtag=int
Route::get('/images', [ImageController::class, 'index']);

Route::get('/images/{id}', [ImageController::class, 'show']); // открыть
Route::get('/hashtags', [HashtagController::class, 'index']); // список хештегов
Route::get('/categories', [CategoryController::class, 'index']);// список категорий

Route::middleware(['auth:sanctum', BannedMiddleware::class])->group(function () {
    Route::get('/who', [AuthController::class, 'who']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // изображения
    Route::get('/images/hashtag/{hashtagId}', [ImageController::class, 'imagesByHashtag']);    // по хештегу
    Route::get('/images/category/{categoryId}', [ImageController::class, 'imagesByCategory']); // по категории
    Route::post('/images', [ImageController::class, 'store']); // загрузить
    Route::delete('/images/{id}', [ImageController::class, 'destroy']); // удалить
    Route::get('/images/user/{userId}', [ImageController::class, 'imagesByUser']); // по пользователю
    Route::match(['put', 'patch'], '/images/{id}', [ImageController::class, 'update']); // реадактирование изображения

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
    Route::post('/images/{imageId}/category', [CategoryController::class, 'addToImage']);// привязать категорию
    Route::delete('/images/{imageId}/category', [CategoryController::class, 'removeFromImage']);// отвязать категорию

    //админка
    Route::middleware([RoleMiddleware::class.':admin'])->group(function () {
        Route::post("/admin/users/{id}/assign-role", [AdminController::class, 'assignRole']);  // назначить роль
        Route::get("/admin/stats/users", [AdminController::class, 'getUserStats']);            // получить статы по пользователям
        Route::get("/admin/stats/week_stats", [AdminController::class, 'getLastWeekStats']);   // получить статы за неделю
        Route::get("/admin/stats/regs", [AdminController::class, 'getRegistrationPlot']);      // получить график по регам
    });
    Route::middleware([RoleMiddleware::class.':admin,moderator'])->group(function () {
        Route::post("/admin/users/{id}/ban", [AdminController::class, 'ban']);                 // забанить пользователя
        Route::post("/admin/users/{id}/unban", [AdminController::class, 'unban']);             // разбанить пользователя
        Route::delete("/admin/images/{id}", [AdminController::class, 'banImage']);             // забанить (удалить) фото
        Route::delete("/admin/comments/{id}", [AdminController::class, 'banComment']);         // забанить (удалить) коммент
        Route::get("/admin/users", [AdminController::class, 'getUsers']);                      // получить список пользователей
    });
});
