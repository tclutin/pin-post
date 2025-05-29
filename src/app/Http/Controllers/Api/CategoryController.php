<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return response()->json($this->categoryService->getAllCategories());
    }

    public function addToImage(Request $request, $imageId)
    {
        return $this->categoryService->addCategoryToImage($request, $imageId);
    }

    public function removeFromImage($imageId)
    {
        return $this->categoryService->removeCategoryFromImage($imageId);
    }
}