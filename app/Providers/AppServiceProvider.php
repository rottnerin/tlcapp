<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\PLWednesdaySession;

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
        // Route model binding for PL Wednesday sessions (admin routes use 'pl_wednesday')
        Route::bind('pl_wednesday', function ($value) {
            return PLWednesdaySession::findOrFail($value);
        });
    }
}
