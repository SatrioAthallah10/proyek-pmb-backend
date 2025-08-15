<?php

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
        // --- [PERBAIKAN FINAL] ---
        // Mendaftarkan alias 'role' di sini adalah cara yang benar untuk Laravel versi baru.
        // File Kernel.php sekarang diabaikan untuk pendaftaran alias middleware.
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Mendaftarkan HandleCors sebagai middleware global (sudah benar)
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
