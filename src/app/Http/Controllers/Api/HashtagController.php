<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HashtagController extends Controller
{
    // создать новый хештег
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:hashtags,name',
        ]);

        $hashtag = Hashtag::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($hashtag, 201);
    }

    // привязать существующий хештег к изображению
    public function attachToImage(Request $request, $imageId)
    {
        $request->validate([
            'hashtag_id' => 'required|exists:hashtags,id',
        ]);

        $image = Image::find($imageId);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $image->hashtags()->syncWithoutDetaching([$request->input('hashtag_id')]);

        return response()->json(['message' => 'Hashtag attached']);
    }

    // удалить конкретный хештег у изображения
    public function detachFromImage(Request $request, $imageId)
    {
        $request->validate([
            'hashtag_id' => 'required|exists:hashtags,id',
        ]);

        $image = Image::find($imageId);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $image->hashtags()->detach($request->input('hashtag_id'));

        return response()->json(['message' => 'Hashtag detached']);
    }
}