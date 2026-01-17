<?php

namespace App\Providers;

use App\Models\PDDay;
use App\Models\PLDaysSetting;
use App\Models\PLWednesdaySetting;
use App\Models\TTTSetting;
use App\Models\WellnessSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
                'tttActive' => TTTSetting::isActive(),
                'archivedFallPDDays' => PDDay::getArchivedBySeason('fall'),
                'archivedSpringPDDays' => PDDay::getArchivedBySeason('spring'),
            ]);
        });
    }
}
