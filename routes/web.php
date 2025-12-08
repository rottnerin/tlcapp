<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WellnessController;
use App\Http\Controllers\PLWednesdayController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WellnessSessionController;
use App\Http\Controllers\Admin\ScheduleItemController;
use App\Http\Controllers\Admin\PLWednesdayController as AdminPLWednesdayController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\PDDayController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\PDDay;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $activePDDay = PDDay::getActive();
    return view('welcome', compact('activePDDay'));
});

// Admin Authentication routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Google OAuth routes (for regular users)
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

// User-only protected routes
Route::middleware(['user.only'])->group(function () {
    // Default landing page - Schedule view
    Route::get('/dashboard', [ScheduleController::class, 'index'])->name('dashboard');
    
    // Schedule (main view with day 1/day 2 tabs)
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/{scheduleItem}', [ScheduleController::class, 'show'])->name('schedule.show');
    
    // Wellness Sessions
    Route::get('/wellness', [WellnessController::class, 'index'])->name('wellness.index');
    Route::get('/wellness/{session}', [WellnessController::class, 'show'])->name('wellness.show');
    Route::post('/wellness/{session}/enroll', [WellnessController::class, 'enroll'])->name('wellness.enroll');
    
    // Professional Learning Wednesday
    Route::get('/professional-learning', [PLWednesdayController::class, 'index'])->name('pl-wednesday.index');
    Route::get('/professional-learning/{session}', [PLWednesdayController::class, 'show'])->name('pl-wednesday.show');
    
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
    Route::post('/users/{user}/update-password', [AdminController::class, 'updatePassword'])->name('users.update-password');
    
    // Wellness Sessions Management
    Route::resource('wellness', WellnessSessionController::class);
    Route::post('/wellness/{wellness}/toggle-status', [WellnessSessionController::class, 'toggleStatus'])->name('wellness.toggle-status');
    Route::post('/wellness/{wellness}/remove-enrollment', [WellnessSessionController::class, 'removeEnrollment'])->name('wellness.remove-enrollment');
    Route::get('/wellness/{wellness}/transfer', [WellnessSessionController::class, 'showTransfer'])->name('wellness.transfer');
    Route::post('/wellness/{wellness}/transfer-user', [WellnessSessionController::class, 'transferUser'])->name('wellness.transfer-user');
    
    // Schedule Items Management
    Route::resource('schedule', ScheduleItemController::class);
    Route::post('/schedule/{schedule}/toggle-status', [ScheduleItemController::class, 'toggleStatus'])->name('schedule.toggle-status');
    Route::post('/schedule/bulk-update', [ScheduleItemController::class, 'bulkUpdate'])->name('schedule.bulk-update');
    Route::get('/schedule-by-pdday', [ScheduleItemController::class, 'byPdDay'])->name('schedule.by-pdday');
    Route::get('/schedule-copy/{pdday}', [ScheduleItemController::class, 'showCopyForm'])->name('schedule.copy-form');
    Route::post('/schedule-copy/{pdday}', [ScheduleItemController::class, 'copySchedule'])->name('schedule.copy');
    Route::post('/schedule-upload/{pdday}', [ScheduleItemController::class, 'uploadCsv'])->name('schedule.upload-csv');
    
    // PD Days Management
    Route::resource('pddays', PDDayController::class)->except(['show']);
    Route::post('/pddays/{pdday}/toggle-active', [PDDayController::class, 'toggleActive'])->name('pddays.toggle-active');
    
    // PL Wednesday Management
    Route::resource('pl-wednesday', AdminPLWednesdayController::class);
    Route::post('/pl-wednesday/toggle-active', [AdminPLWednesdayController::class, 'toggleActive'])->name('pl-wednesday.toggle-active');
    Route::post('/pl-wednesday/{plWednesday}/toggle-status', [AdminPLWednesdayController::class, 'toggleSessionStatus'])->name('pl-wednesday.toggle-status');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/wellness-enrollments', [ReportsController::class, 'wellnessEnrollments'])->name('reports.wellness-enrollments');
    Route::get('/reports/unenrolled-users', [ReportsController::class, 'unenrolledUsers'])->name('reports.unenrolled-users');
    Route::get('/reports/capacity-utilization', [ReportsController::class, 'capacityUtilization'])->name('reports.capacity-utilization');
    Route::get('/reports/division-summary', [ReportsController::class, 'divisionSummary'])->name('reports.division-summary');
    Route::get('/reports/user-activity', [ReportsController::class, 'userActivity'])->name('reports.user-activity');
});
