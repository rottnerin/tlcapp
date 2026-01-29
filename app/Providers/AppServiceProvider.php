<?php

namespace App\Providers;

use App\Models\PDDay;
use App\Models\PLDaysSetting;
use App\Models\PLWednesdaySetting;
use App\Models\CCLSetting;
use App\Models\WellnessSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Configure API rate limiting
        $this->configureRateLimiting();

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
                'cclActive' => CCLSetting::isActive(),
                'archivedFallPDDays' => PDDay::getArchivedBySeason('fall'),
                'archivedSpringPDDays' => PDDay::getArchivedBySeason('spring'),
            ]);
        });
    }

    /**
     * Configure the application's rate limiters.
     */
    protected function configureRateLimiting(): void
    {
        // Default API rate limiter (60 requests per minute for authenticated users)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Stricter rate limit for authentication endpoints (10 requests per minute)
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
