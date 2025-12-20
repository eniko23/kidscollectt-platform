<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $request = $this->app['request'];
        $appUrl = config('app.url');

        // Determine if we should force HTTPS.
        // We force HTTPS if:
        // 1. Not in local/testing environment (Production, Staging, etc.)
        // 2. APP_URL starts with https://
        // 3. Request claims to be HTTPS (via headers or port)
        $isSecure = ! $this->app->environment(['local', 'testing'])
            || ($appUrl && str_starts_with($appUrl, 'https://'))
            || $request->server('HTTPS') === 'on'
            || $request->server('HTTP_X_FORWARDED_PROTO') === 'https'
            || $request->server('HTTP_X_FORWARDED_SSL') === 'on'
            || $request->server('SERVER_PORT') == 443;

        if ($isSecure) {
            // Force HTTPS Scheme
            URL::forceScheme('https');

            // Force Request object to be secure
            $request->server->set('HTTPS', 'on');
            $_SERVER['HTTPS'] = 'on'; // Fallback for globals

            // Determine the secure Base URL
            $secureUrl = $appUrl;
            if (empty($secureUrl)) {
                $secureUrl = $request->root();
            }

            // Ensure $secureUrl starts with https://
            if (!str_starts_with($secureUrl, 'https://')) {
                $secureUrl = preg_replace('/^http:/', 'https:', $secureUrl);
                if (!str_starts_with($secureUrl, 'https://')) {
                    $secureUrl = 'https://' . ltrim($secureUrl, '/');
                }
            }

            // Force Root URL if APP_URL is set (prevents generation of internal IP links)
            if ($appUrl) {
                URL::forceRootUrl($secureUrl);
            }

            // Explicitly set Livewire Asset URL to HTTPS
            config(['livewire.asset_url' => $secureUrl]);

            // Force Livewire Update Route to be HTTPS
            // This ensures the internal route definition uses HTTPS
            if (class_exists(Livewire::class)) {
                Livewire::setUpdateRoute(function ($handle) {
                    return Route::post('/livewire/update', $handle)
                        ->middleware('web')
                        ->name('livewire.update');
                });

                // Also force script route just in case
                Livewire::setScriptRoute(function ($handle) {
                    return Route::get('/livewire/livewire.js', $handle);
                });
            }
        }
    }
}
