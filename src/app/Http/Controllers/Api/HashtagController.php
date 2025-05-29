<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\HashtagServiceInterface;

class HashtagController extends Controller
{
    protected $hashtagService;

    public function __construct(HashtagServiceInterface $hashtagService)
    {
        $this->hashtagService = $hashtagService;
    }

    public function index()
    {
        return $this->hashtagService->index();
    }

    public function create(Request $request)
    {
        return $this->hashtagService->create($request);
    }

    public function attachToImage(Request $request, $imageId)
    {
        return $this->hashtagService->attachToImage($request, $imageId);
    }

    public function detachFromImage(Request $request, $imageId)
    {
        return $this->hashtagService->detachFromImage($request, $imageId);
    }
}
