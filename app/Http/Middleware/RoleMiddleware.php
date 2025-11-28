<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !$user->hasAnyRole($roles)) {
            return response()->json([
                'message' => 'Accès refusé : rôle requis.',
                'required_roles' => $roles
            ], 403);
        }

        return $next($request);
    }
}