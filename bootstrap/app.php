<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DosenMiddleware;
use App\Http\Middleware\MahasiswaMiddleware;
use App\Http\Middleware\OrMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'staffMiddleware' => StaffMiddleware::class,
            'adminMiddleware' => AdminMiddleware::class,
            'userMiddleware' => UserMiddleware::class,
            'orMiddleware' => OrMiddleware::class,
            'mahasiswaMiddleware' => MahasiswaMiddleware::class,
            'dosenMiddleware' => DosenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
