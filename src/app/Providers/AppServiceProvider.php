<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\AdminServiceInterface;
use App\Services\AdminService;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\CategoryService;
use App\Services\Interfaces\CommentServiceInterface;
use App\Services\CommentService;
use App\Services\Interfaces\HashtagServiceInterface;
use App\Services\HashtagService;
use App\Services\Interfaces\ImageServiceInterface;
use App\Services\ImageService;
use App\Services\Interfaces\LikeServiceInterface;
use App\Services\LikeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(HashtagServiceInterface::class, HashtagService::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(LikeServiceInterface::class, LikeService::class);
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
