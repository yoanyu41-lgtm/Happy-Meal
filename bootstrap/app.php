<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Register admin-only middleware alias
        $middleware->alias([
            'admin.only' => \App\Http\Middleware\EnsureIsAdmin::class,
        ]);

        // Redirect unauthenticated users trying to access admin to admin login
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin*')) {
                return route('admin.login');
            }
            return route('customer.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
