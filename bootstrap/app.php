<?php

use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        // CSRF exceptions
        $middleware->validateCsrfTokens(except: [
            'tenant/payment/callback',
            'api/*',
        ]);

        // Redirect guests based on route
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('login'); // Admin login route
            }
            return route('tenant.login'); // Tenant login route
        });

        // Global middleware
        $middleware->append(SecurityHeaders::class);

        // Middleware aliases
        $middleware->alias([
            'admin' => EnsureIsAdmin::class,
        ]);

        // API throttle
        $middleware->throttleApi('60,1');
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();