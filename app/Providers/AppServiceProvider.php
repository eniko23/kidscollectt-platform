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
        // Force HTTPS scheme
        URL::forceScheme('https');

        // Fix for Livewire mixed content in production
        if ($this->app->environment('production')) {
            $this->app['request']->server->set('HTTPS', 'on');

            // Ensure APP_URL is used as root if it's HTTPS
            $appUrl = config('app.url');
            if ($appUrl && str_starts_with($appUrl, 'https://')) {
               URL::forceRootUrl($appUrl);
            }
        }
    }
}
