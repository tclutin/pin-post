<?php
namespace App\Services;

use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Likes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminService implements AdminServiceInterface
{
    public function assignRole(int $userId, int $roleId): array
    {
        $user = User::findOrFail($userId);

        if ($user->isAdmin()) {
            return ['message' => 'это админ, его трогать нельзя', 'status' => 403];
        }

        $user->role_id = $roleId;
        $user->save();

        return ['message' => 'Role assigned successfully'];
    }

    public function getUsers(): array
    {
        return User::select(['id', 'name', 'email', 'role_id', 'is_banned', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function banUser(int $userId, int $loggedUserId): array
    {
        $user = User::findOrFail($userId);
        $loggedUser = User::find($loggedUserId);

        if ($user->isAdmin()) {
            return ['message' => 'Cannot ban the Admin', 'status' => 403];
        } elseif ($user->isModerator() && $loggedUser->isModerator()) {
            return ['message' => 'Moderator cannot ban another moderator', 'status' => 403];
        }
        
        $user->update([
            'is_banned' => true,
            'banned_date' => now()
        ]);

        return ['message' => 'User banned successfully'];
    }

    public function unbanUser(int $userId): array
    {
        $user = User::findOrFail($userId);
        
        $user->update([
            'is_banned' => false,
            'banned_date' => null
        ]);

        return ['message' => 'User unbanned successfully'];
    }

    public function banImage(int $imageId, int $deletedBy): array
    {
        $image = Image::find($imageId);

        if (!$image) {
            return ['message' => 'Image not found', 'status' => 404];
        }

        $user = User::findOrFail($image->author_id);

        if($user->isAdmin()) {
            return ['message' => 'Cannot ban image from Admin', 'status' => 403];
        }

        $image->deleted_by = $deletedBy;
        $image->save();
        $image->delete();

        return ['message' => 'Image banned'];
    }

    public function banComment(int $commentId): array
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return ['message' => 'Comment not found', 'status' => 404];
        }

        $user = User::findOrFail($comment->user_id);

        if($user->isAdmin()) {
            return ['message' => 'Cannot ban comment from Admin', 'status' => 403];
        }

        $comment->delete();

        return ['message' => 'Comment banned'];
    }

    public function getUserStats(): array
    {
        return [
            'total_users' => User::count(),
            'banned' => User::where('is_banned', true)->count(),
            'admins' => User::where('role_id', 3)->count(),
            'moderators' => User::where('role_id', 2)->count()
        ];
    }

    public function getLastWeekStats(): array
    {
        $lastWeek = Carbon::now()->subWeek();

        return [
            'images' => [
                'total' => Image::where('created_at', '>=', $lastWeek)->count(),
                'banned' => Image::onlyTrashed()->where('deleted_at', '>=', $lastWeek)->count()
            ],
            'comments_count' => Comment::where('created_at', '>=', $lastWeek)->count(),
            'likes_count' => Likes::where('created_at', '>=', $lastWeek)->count()
        ];
    }

    public function getRegistrationPlot(string $period): array
    {
        $query = User::query();
        $now = Carbon::now();
        
        switch($period) {
            case 'month':
                $query->where('created_at', '>=', $now->subMonth())
                    ->selectRaw("to_char(created_at, 'YYYY-MM-DD') as date, COUNT(*) as count")
                    ->groupBy('date');
                break;
                
            case 'year':
                $query->where('created_at', '>=', $now->subYear())
                    ->selectRaw("to_char(created_at, 'YYYY-MM') as date, COUNT(*) as count")
                    ->groupBy('date');
                break;
                
            default: // week
                $query->where('created_at', '>=', $now->subWeek())
                    ->selectRaw("to_char(created_at, 'YYYY-MM-DD') as date, COUNT(*) as count")
                    ->groupBy('date');
        }

        $data = $query->orderBy('date')->get();

        return [
            'x' => $data->pluck('date')->toArray(),
            'y' => $data->pluck('count')->toArray(),
            'period' => $period
        ];
    }
}