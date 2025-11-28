<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions  → peut recevoir 1 ou plusieurs permissions
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        // Récupère l'utilisateur authentifié (via Sanctum ou autre guard)
        $user = $request->user();

        // Si pas connecté → 401
        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié.',
            ], 401);
        }

        // Si l'utilisateur n'a AUCUNE des permissions demandées → 403
        if (!$user->hasAnyPermission($permissions)) {
            return response()->json([
                'message'              => 'Accès refusé : permission insuffisante.',
                'required_permissions' => $permissions,
                'user_permissions'     => $user->getAllPermissions()->pluck('name')->toArray(),
            ], 403);
        }

        // Tout est OK → on passe à la suite (contrôleur)
        return $next($request);
    }
}