<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\LikeServiceInterface;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeServiceInterface $likeService)
    {
        $this->likeService = $likeService;
    }

    public function store($imageId)
    {
        return $this->likeService->toggleLike($imageId);
    }
}
