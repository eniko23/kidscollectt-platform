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
        $appUrl = config('app.url');

        // If APP_URL is configured with HTTPS, enforce it everywhere.
        // This allows the user to control HTTPS usage via .env (APP_URL=https://...)
        if ($appUrl && str_starts_with($appUrl, 'https://')) {
            // Force HTTPS scheme for asset() and route()
            URL::forceScheme('https');

            // Force Root URL to ensure we stick to the configured domain
            URL::forceRootUrl($appUrl);

            // Trick the Request object into thinking it's secure (fixes Livewire uploads behind proxies)
            $this->app['request']->server->set('HTTPS', 'on');
        } elseif ($this->app->environment('production')) {
            // Fallback: Always enforce HTTPS in production even if APP_URL is mistyped or missing
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}
