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
        // Enforce UTF-8 default charset globally for multi-lingual and Arabic text
        ini_set('default_charset', 'UTF-8');
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8');
        }

        if (config('app.env') === 'production' || (app()->has('request') && request()->header('x-forwarded-proto') === 'https')) {
            URL::forceScheme('https');
        }
    }
}
