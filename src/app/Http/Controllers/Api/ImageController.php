<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::with(['author', 'category', 'comments'])->latest()->get();

        return response()->json($images);
    }

    public function show($id)
    {
        $image = Image::with([
            'author',
            'category',
            'comments.user',
            'likes',
            'hashtags'
        ])->find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json($image);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'image_path' => 'required|string',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $image = Image::create([
            'author_id'   => Auth::id(),
            //'image_path'  => $request->input('image_path'), 
            'image_path'  => 'src', 
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
        ]);

        return response()->json($image, 201);
    }

    public function imagesByHashtag($hashtagId)
    {
        $images = Image::whereHas('hashtags', function($query) use ($hashtagId) {
            $query->where('id', $hashtagId);
        })->with([
            'author',
            'category',
            'comments.user',
            'likes',
            'hashtags'
        ])->get();

        return response()->json($images);
    }

    public function imagesByCategory($categoryId)
    {
        $images = Image::where('category_id', $categoryId)
            ->with([
                'author',
                'category',
                'comments.user',
                'likes',
                'hashtags'
            ])->get();

        return response()->json($images);
    }

    public function destroy($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $image->deleted_by = Auth::id();
        $image->save();
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
