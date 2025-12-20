<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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

        // 1. Detect if we should be running in HTTPS
        // We check for:
        // - Standard HTTPS server var
        // - Proxy headers (Cloudflare, Load Balancers)
        // - Production environment (as a fallback safety)
        // - Explicitly configured HTTPS APP_URL
        $isSecure = $request->server('HTTPS') === 'on'
            || $request->server('HTTP_X_FORWARDED_PROTO') === 'https'
            || $request->server('HTTP_X_FORWARDED_SSL') === 'on'
            || $this->app->environment('production')
            || ($appUrl && str_starts_with($appUrl, 'https://'));

        if ($isSecure) {
            // Fix Request object for subsequent calls (isSecure() will now return true)
            $request->server->set('HTTPS', 'on');

            // Fix URL generation to always produce https:// links
            URL::forceScheme('https');

            // 3. Fix Livewire Configuration for Mixed Content
            // Livewire uses this config to generate the JS object on the frontend.
            // We ensure it points to the HTTPS version of the APP_URL to prevent
            // http:// upload URLs when behind a proxy.

            $livewireUrl = $appUrl;

            // If APP_URL is not set or empty, use the current root
            if (empty($livewireUrl)) {
                $livewireUrl = $request->root();
            }

            // Ensure the URL used for Livewire assets is HTTPS
            if (!str_starts_with($livewireUrl, 'https://')) {
                // Replace http: with https:
                $livewireUrl = preg_replace('/^http:/', 'https:', $livewireUrl);

                // If it still doesn't start with https (e.g. was just "localhost"), prepend it
                if (!str_starts_with($livewireUrl, 'https://')) {
                    $livewireUrl = 'https://' . ltrim($livewireUrl, '/');
                }
            }

            // Set the config for Livewire
            config(['livewire.asset_url' => $livewireUrl]);
        }

        // If APP_URL is explicitly set, we should use it as the root URL
        // This ensures generated links match the configured domain.
        if ($appUrl) {
             URL::forceRootUrl($appUrl);
        }
    }
}
