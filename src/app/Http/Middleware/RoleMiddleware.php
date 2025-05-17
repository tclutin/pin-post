<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    protected array $roleMap = [
        1 => 'user',
        2 => 'moderator',
        3 => 'admin',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $role = $request->user()->role->role_name;

        if (!in_array($role, $roles)) {
            return response()->json(['message' => 'access denied'], 403);
        }

        return $next($request);
    }
}
