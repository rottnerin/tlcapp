<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Version: v1
| Authentication: Laravel Sanctum (token-based)
| Rate Limiting: Configured in AppServiceProvider
|
| All routes are automatically prefixed with /api
|
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Authentication Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['throttle:auth'])->group(function () {
        // Admin login (email/password)
        Route::post('/auth/login', function () {
            return response()->json(['message' => 'Login endpoint - to be implemented']);
        });

        // Google OAuth mobile flow
        Route::get('/auth/google/redirect', function () {
            return response()->json(['message' => 'Google OAuth redirect - to be implemented']);
        });

        Route::get('/auth/google/callback', function () {
            return response()->json(['message' => 'Google OAuth callback - to be implemented']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Routes (Require Sanctum Token)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth:sanctum'])->group(function () {

        // Token management
        Route::post('/auth/refresh', function () {
            return response()->json(['message' => 'Token refresh - to be implemented']);
        });

        Route::post('/auth/logout', function () {
            return response()->json(['message' => 'Logout - to be implemented']);
        });

        Route::delete('/auth/logout-all', function () {
            return response()->json(['message' => 'Logout all devices - to be implemented']);
        });

        Route::get('/auth/devices', function () {
            return response()->json(['message' => 'List devices - to be implemented']);
        });

        Route::delete('/auth/devices/{tokenId}', function () {
            return response()->json(['message' => 'Revoke device - to be implemented']);
        });

        // Current user
        Route::get('/me', function () {
            return response()->json(auth()->user());
        });

        // Feature toggles
        Route::get('/features', function () {
            return response()->json(['message' => 'Feature toggles - to be implemented']);
        });

        // Placeholder routes for future implementation
        Route::get('/pddays', function () {
            return response()->json(['message' => 'PD Days list - to be implemented']);
        });

        Route::get('/schedule', function () {
            return response()->json(['message' => 'Schedule items - to be implemented']);
        });

        Route::get('/wellness', function () {
            return response()->json(['message' => 'Wellness sessions - to be implemented']);
        });

        Route::get('/my-pl', function () {
            return response()->json(['message' => 'My PL - to be implemented']);
        });

        /*
        |--------------------------------------------------------------------------
        | Admin Routes (Require is_admin = true)
        |--------------------------------------------------------------------------
        */

        Route::middleware(['admin'])->prefix('admin')->group(function () {
            Route::get('/dashboard', function () {
                return response()->json(['message' => 'Admin dashboard - to be implemented']);
            });

            // More admin routes will be added here
        });
    });
});
