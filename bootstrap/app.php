<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureEmailIsVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
            HandleCors::class,
        ]);

        // Route middleware
        $middleware->alias([
            'auth' => Authenticate::class,
            'auth:sanctum' => EnsureFrontendRequestsAreStateful::class,
            'throttle' => ThrottleRequests::class,
            'bindings' => SubstituteBindings::class,
            'verified' => EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
    
