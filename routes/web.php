<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WellnessController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WellnessSessionController;
use App\Http\Controllers\Admin\ScheduleItemController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

// User-only protected routes
Route::middleware(['user.only'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-schedule', [DashboardController::class, 'mySchedule'])->name('my-schedule');
    
    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/{scheduleItem}', [ScheduleController::class, 'show'])->name('schedule.show');
    
    // Wellness Sessions
    Route::get('/wellness', [WellnessController::class, 'index'])->name('wellness.index');
    Route::get('/wellness/{session}', [WellnessController::class, 'show'])->name('wellness.show');
    Route::post('/wellness/{session}/enroll', [WellnessController::class, 'enroll'])->name('wellness.enroll');
    Route::delete('/wellness/{session}/cancel', [WellnessController::class, 'cancel'])->name('wellness.cancel');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    
    // Wellness Sessions Management
    Route::resource('wellness', WellnessSessionController::class);
    Route::post('/wellness/{wellness}/toggle-status', [WellnessSessionController::class, 'toggleStatus'])->name('wellness.toggle-status');
    
    // Schedule Items Management
    Route::resource('schedule', ScheduleItemController::class);
    Route::post('/schedule/{schedule}/toggle-status', [ScheduleItemController::class, 'toggleStatus'])->name('schedule.toggle-status');
    Route::post('/schedule/bulk-update', [ScheduleItemController::class, 'bulkUpdate'])->name('schedule.bulk-update');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/enrollments', [AdminController::class, 'enrollmentReport'])->name('reports.enrollments');
});
