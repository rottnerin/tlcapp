<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\PLWednesdaySession;
use App\Models\PLWednesdaySetting;
use App\Models\WellnessSetting;
use App\Models\PLDaysSetting;

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

        // Share feature settings with all views
        View::composer('*', function ($view) {
            // Initialize settings if they don't exist
            PLWednesdaySetting::initialize();
            WellnessSetting::initialize();
            PLDaysSetting::initialize();

            $view->with([
                'plWednesdayActive' => PLWednesdaySetting::isActive(),
                'wellnessActive' => WellnessSetting::isActive(),
                'plDaysActive' => PLDaysSetting::isActive(),
            ]);
        });
    }
}
