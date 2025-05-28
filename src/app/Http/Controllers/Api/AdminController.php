<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller 
{
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        $user = User::findOrFail($userId);

        if ($user->isAdmin()) {
            return response()->json(['message' => 'это админ, его трогать нельзя'], 403);
        }

        $user->role_id = $request->role_id;
        $user->save();

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function ban(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $logged_user = User::find(Auth::id());

        if ($user->isAdmin()) {
            return response()->json(['message' => 'Cannot ban the Admin'], 403);
        } elseif ($user->isModerator() and $logged_user->isModerator()) {
            return response()->json(['message' => 'Moderator cannot ban another moderator'], 403);
        }
        
        $user->update([
            'is_banned' => true,
            'banned_date' => now()
        ]);

        return response()->json(['message' => 'User banned successfully']);
    }

    public function unban(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $user->update([
            'is_banned' => false,
            'banned_date' => NULL
        ]);

        return response()->json(['message' => 'User unbanned successfully']);
    }

    public function banImage(Request $request, $imageId) 
    {
        $image = Image::find($imageId);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $user = User::findOrFail($image->author_id);

        if($user->isAdmin()) {
            return response()->json(['message' => 'Cannot ban image from Admin'], 403);
        }

        $image->deleted_by = Auth::id();
        $image->save();
        $image->delete();

        return response()->json(['message' => 'Image banned']);
    }

    public function banComment(Request $request, $commentId) 
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $user = User::findOrFail($comment->user_id);

        if($user->isAdmin()) {
            return response()->json(['message' => 'Cannot ban comment from Admin'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment banned']);
    }

    public function getUserStats(Request $request)
    {
        $totalUsers = User::count();

        // количество забаненных пользователей
        $bannedCount = User::where('is_banned', true)->count();

        // количество админов и модераторов
        $adminsCount = User::where('role_id', 3)->count();
        $moderatorsCount = User::where('role_id', 2)->count(); 

        return response()->json([
            'total_users' => $totalUsers,
            'banned' => $bannedCount,
            'admins' => $adminsCount,
            'moderators' => $moderatorsCount]);
    }

    public function getLastWeekStats(Request $request)
    {
        $lastWeek = now()->subWeek();

        // статистика по картинкам
        $totalImages = Image::where('created_at', '>=', $lastWeek)->count();
        $bannedImages = Image::onlyTrashed()->where('deleted_at', '>=', $lastWeek)->count();

        // статистика по комментариям
        $commentsCount = Comment::where('created_at', '>=', $lastWeek)->count();

        // статистика по лайкам
        $likesCount = Likes::where('created_at', '>=', $lastWeek)->count();

        return response()->json([
            'images' => [
                'total' => $totalImages,
                'banned' => $bannedImages
            ],
            'comments_count' => $commentsCount,
            'likes_count' => $likesCount
        ]);
    }

    public function getRegistrationPlot(Request $request) 
    {
        $period = $request->input('period', 'week'); // week/month/year
    
        $query = User::query();
        
        switch($period) {
            case 'month':
                $data = $query->selectRaw("to_char(created_at, 'YYYY-MM-DD') as date, COUNT(*) as count")
                    ->where('created_at', '>=', now()->subMonth())
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
                
            case 'year':
                $data = $query->selectRaw("to_char(created_at, 'YYYY-MM') as date, COUNT(*) as count")
                    ->where('created_at', '>=', now()->subYear())
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
                
            default: // week
                $data = $query->selectRaw("to_char(created_at, 'YYYY-MM-DD') as date, COUNT(*) as count")
                    ->where('created_at', '>=', now()->subWeek())
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
        }
        return response()->json([
            'x' => $data->pluck('date'),
            'y' => $data->pluck('count'),
            'period' => $period
        ]);
    }
}