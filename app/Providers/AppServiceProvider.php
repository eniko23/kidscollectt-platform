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
        if (! app()->environment(['local', 'testing'])) {
            URL::forceScheme('https');

            $appUrl = config('app.url');
            if ($appUrl && str_starts_with($appUrl, 'http://')) {
                config(['livewire.asset_url' => str_replace('http://', 'https://', $appUrl)]);
            }
        }
    }
}
