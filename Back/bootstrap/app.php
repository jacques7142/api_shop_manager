<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// IMPORT OBLIGATOIRE POUR SANCTUM
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))

    // ACTIVATION DES ROUTES API (très important !)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',           // ← AJOUTÉ
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',                            // ← tes routes seront /api/login, /api/products, etc.
    )

    // ENREGISTREMENT DES MIDDLEWARES (Sanctum + tes middlewares personnalisés)
    ->withMiddleware(function (Middleware $middleware): void {

        // SANCTUM : obligatoire pour que les tokens fonctionnent depuis Flutter/mobile
        $middleware->append(EnsureFrontendRequestsAreStateful::class);

        // Groupe API : on remet Sanctum en premier + throttle
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Enregistrement de tes middleware personnalisés avec un alias propre
        $middleware->alias([
            'role'       => \App\Http\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);

        // Optionnel : tu peux ajouter d'autres alias plus tard
        // 'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();