<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\Interfaces\ImageServiceInterface;

class ImageService implements ImageServiceInterface
{
    public function index(Request $request)
    {
        $request->validate([
            'category' => 'sometimes|exists:categories,id',
            'hashtag' => 'sometimes|exists:hashtags,id',
            'author' => 'sometimes|string',
            'description' => 'sometimes|string',
        ]);

        $query = Image::with(['author', 'category', 'comments.user', 'likes', 'hashtags'])
            ->withCount('likes')
            ->withCount('comments');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('hashtag')) {
            $query->whereHas('hashtags', fn($q) => $q->where('id', $request->hashtag));
        }

        if ($request->has('author')) {
            $query->whereHas('author', fn($q) => $q->where('name', 'like', '%' . $request->author . '%'));
        }

        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        return $query->get()->map(function ($image) {
            $image->image_url = Storage::disk('minio')->url($image->image_path);
            return $image;
        });
    }

    public function show($id)
    {
        $image = Image::with(['author', 'category', 'comments.user', 'likes', 'hashtags'])->findOrFail($id);

        return response()->json([
            ...$image->toArray(),
            'image_url' => Storage::disk('minio')->url($image->image_path),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'description' => 'nullable|string|max:2000',
            'category_id' => 'nullable|exists:categories,id',
            'hashtags' => 'nullable|array',
            'hashtags.*' => 'exists:hashtags,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('image');
        $filename = 'img_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/images', $filename, 'minio');

        $image = Image::create([
            'author_id' => Auth::id(),
            'image_path' => $path,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('hashtags')) {
            $image->hashtags()->sync($request->hashtags);
        }

        $image->load(['author', 'category', 'hashtags']);
        $image->image_url = Storage::disk('minio')->url($image->image_path);

        return response()->json($image, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:2000',
            'category_id' => 'nullable|exists:categories,id',
            'hashtags' => 'nullable|array',
            'hashtags.*' => 'exists:hashtags,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $image = Image::findOrFail($id);

        if ($image->author_id !== Auth::id() && !Auth::user()->hasRole(['admin', 'moderator'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->has('description')) {
            $image->description = $request->description;
        }

        if ($request->has('category_id')) {
            $image->category_id = $request->category_id;
        }

        $image->save();

        if ($request->has('hashtags')) {
            $image->hashtags()->sync($request->hashtags);
        }

        $image->load(['author', 'category', 'hashtags']);
        $image->image_url = Storage::disk('minio')->url($image->image_path);

        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        if ($image->author_id !== Auth::id() && !Auth::user()->hasRole(['admin', 'moderator'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('minio')->delete($image->image_path);

        $image->deleted_by = Auth::id();
        $image->save();
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }

    public function imagesByHashtag($hashtagId)
    {
        $images = Image::whereHas('hashtags', fn($q) => $q->where('id', $hashtagId))
            ->with(['author', 'category', 'comments.user', 'likes', 'hashtags'])
            ->paginate(15);

        return response()->json($images);
    }

    public function imagesByCategory($categoryId)
    {
        $images = Image::where('category_id', $categoryId)
            ->with(['author', 'category', 'comments.user', 'likes', 'hashtags'])
            ->paginate(15);

        return response()->json($images);
    }

    public function imagesByUser($userId)
    {
        $images = Image::where('author_id', $userId)
            ->with(['author:id,name', 'category', 'comments.user', 'likes', 'hashtags'])
            ->get()
            ->map(function ($image) {
                $image->image_url = Storage::disk('minio')->url($image->image_path);
                return $image;
            });

        return response()->json($images);
    }
}
