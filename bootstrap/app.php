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
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user.only' => \App\Http\Middleware\UserOnly::class,
            'no.admin' => \App\Http\Middleware\NoAdminAccess::class,
        ]);
        
        // Redirect unauthenticated admin requests to the admin login page
        $middleware->redirectGuestsTo(fn ($request) => 
            str_starts_with($request->path(), 'admin') 
                ? route('admin.login') 
                : route('google.login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
