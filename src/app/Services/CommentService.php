<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\CommentServiceInterface;

class CommentService implements CommentServiceInterface
{
    public function store(Request $request, $imageId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'image_id' => $imageId,
            'text' => $request->input('text'),
        ]);

        $comment->load('user');

        return response()->json($comment, 201);
    }

    public function destroy($id)
    {
        $comment = Comment::with('image')->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $user = Auth::user();

        if ($comment->user_id !== $user->id && $comment->image->author_id !== $user->id) {
            return response()->json(['message' => 'Forbidden. You can only delete your own comments or comments to your photo.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}
