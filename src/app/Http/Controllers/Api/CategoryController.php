<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // вывести все категории
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    // добавить/изменить категорию изображения
    public function addToImage(Request $request, $imageId)
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

    // удалить категорию у изображения
    public function removeFromImage($imageId)
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