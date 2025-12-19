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
    ->withMiddleware(function (Middleware $middleware) {
        // Reverse Proxy: Trust all proxies.
        // Laravel 11/12 automatically handles the correct headers (Proto, Host, Port, For)
        // Request::HEADER_X_FORWARDED_ALL was removed in Symfony 7, so we use defaults.
        $middleware->trustProxies(at: '*');

        // ❗ CSRF Hariç Bırakma
        $middleware->validateCsrfTokens(except: [
            'payment/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
