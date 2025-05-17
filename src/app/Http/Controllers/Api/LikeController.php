<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Likes;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // поставить/убрать лайк
    public function store($imageId)
    {
        $image = Image::find($imageId);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $userId = Auth::id();

        // проверяем, есть ли уже лайк от пользователя
        $existingLike = Likes::where('image_id', $imageId)
                            ->where('user_id', $userId)
                            ->first();

        if ($existingLike) {
            // если есть - удаляем
            Likes::where('image_id', $imageId)
                    ->where('user_id', $userId)
                    ->delete();

            return response()->json(['message' => 'Like removed']);
        } else {
            // если нет - создаём
            $like = Likes::create([
                'image_id' => $imageId,
                'user_id' => $userId,
            ]);

            return response()->json(['message' => 'Like added', 'like' => $like], 201);
        }
    }
}
