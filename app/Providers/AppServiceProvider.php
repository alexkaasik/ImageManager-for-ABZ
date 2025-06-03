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
        // Force HTTPS on system wide, by checking App_url
        if ( parse_url( env('APP_URL'))['scheme'] == 'https' ) {
            URL::forceScheme('https');
        }
    }
}
