<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        Carbon::setLocale('id');

        // Force HTTPS jika aplikasi berjalan di environment production
        // Ini mengatasi masalah CSS/JS yang tidak terload karena Mixed Content (HTTP di HTTPS)
        if (config('app.env') === 'production' || config('app.env') === 'staging' || env('FORCE_HTTPS', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
