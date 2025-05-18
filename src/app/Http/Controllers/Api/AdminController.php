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
}