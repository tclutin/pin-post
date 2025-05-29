<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Interfaces\CategoryServiceInterface;

class CategoryService implements CategoryServiceInterface
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function addCategoryToImage(Request $request, $imageId)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $image = Image::find($imageId);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $image->category_id = $request->input('category_id');
        $image->save();

        return response()->json($image);
    }

    public function removeCategoryFromImage($imageId)
    {
        $image = Image::find($imageId);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $image->category_id = null;
        $image->save();

        return response()->json(['message' => 'Category removed from image']);
    }
}