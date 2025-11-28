<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();

        if (!$user || !$user->hasAnyPermission($permissions)) {
            return response()->json([
                'message' => 'Accès refusé : permission manquante.',
                'required_permissions' => $permissions
            ], 403);
        }

        return $next($request);
    }
}