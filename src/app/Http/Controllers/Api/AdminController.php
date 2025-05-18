<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}