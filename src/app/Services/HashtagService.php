<?php

namespace App\Services;

use App\Models\Hashtag;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Services\Interfaces\HashtagServiceInterface;

class HashtagService implements HashtagServiceInterface
{
    public function index()
    {
        $hashtags = Hashtag::orderBy('id', 'desc')->limit(5)->get();
        return response()->json($hashtags);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $existing = Hashtag::where('name', $request->input('name'))->first();

        if ($existing) {
            return response()->json([
                'id' => $existing->id,
                'name' => $existing->name,
                'existing' => true
            ], 200);
        }

        $hashtag = Hashtag::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'id' => $hashtag->id,
            'name' => $hashtag->name,
            'existing' => false
        ], 201);
    }

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
