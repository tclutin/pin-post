<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface CategoryServiceInterface
{
    public function getAllCategories();
    public function addCategoryToImage(Request $request, $imageId);
    public function removeCategoryFromImage($imageId);
}