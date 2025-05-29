<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ImageServiceInterface;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        return $this->imageService->index($request);
    }

    public function show($id)
    {
        return $this->imageService->show($id);
    }

    public function store(Request $request)
    {
        return $this->imageService->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->imageService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->imageService->destroy($id);
    }

    public function imagesByHashtag($hashtagId)
    {
        return $this->imageService->imagesByHashtag($hashtagId);
    }

    public function imagesByCategory($categoryId)
    {
        return $this->imageService->imagesByCategory($categoryId);
    }

    public function imagesByUser($userId)
    {
        return $this->imageService->imagesByUser($userId);
    }
}
