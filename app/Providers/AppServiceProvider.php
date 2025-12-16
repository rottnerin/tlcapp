<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
