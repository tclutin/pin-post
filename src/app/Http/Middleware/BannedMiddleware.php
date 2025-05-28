<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if ($user && $user->isBanned()) {
            $user->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Ваш аккаунт заблокирован. Дата блокировки: ' . $user->banned_date
            ], 403);
        }

        return $next($request);
    }
}