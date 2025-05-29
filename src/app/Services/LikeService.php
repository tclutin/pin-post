<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Likes;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\LikeServiceInterface;

class LikeService implements LikeServiceInterface
{
    public function toggleLike($imageId)
    {
        $image = Image::find($imageId);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $userId = Auth::id();

        $existingLike = Likes::where('image_id', $imageId)
                            ->where('user_id', $userId)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Like removed']);
        } else {
            $like = Likes::create([
                'image_id' => $imageId,
                'user_id' => $userId,
            ]);
            return response()->json(['message' => 'Like added', 'like' => $like], 201);
        }
    }
}
