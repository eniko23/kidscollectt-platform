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
        // Force HTTPS in production or when behind a proxy
        $isHttps = request()->header('X-Forwarded-Proto') === 'https' 
                || request()->header('CF-Visitor') 
                || request()->secure()
                || str_starts_with(config('app.url', ''), 'https://');
        
        if ($this->app->environment('production') || $isHttps) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }
    }
}
