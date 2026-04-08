<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WellnessController;
use App\Http\Controllers\PLWednesdayController;
use App\Http\Controllers\MyPLController;
use App\Http\Controllers\CCLController;
use App\Http\Controllers\NTSController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WellnessSessionController;
use App\Http\Controllers\Admin\ScheduleItemController;
use App\Http\Controllers\Admin\PLWednesdayController as AdminPLWednesdayController;
use App\Http\Controllers\Admin\CCLController as AdminCCLController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\PDDayController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ClaudeSettingsController;
use App\Http\Controllers\EarthDayController;
use App\Http\Controllers\Admin\EarthDayController as AdminEarthDayController;
use App\Models\PDDay;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    $activePDDay = PDDay::getActive();
    return view('login', compact('activePDDay'));
})->name('login');

// Typography exploration (temporary for design review)
Route::get('/typography-preview', function () {
    return view('typography-preview');
})->name('typography.preview');

// TEMPORARY: Test login route for browser automation testing
Route::get('/test-login', function () {
    $user = \App\Models\User::where('email', 'testuser@hs.aes.ac.in')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/spring-pl-days/ccl');
    }
    return 'User not found';
});

// TEMPORARY: Test login for NTS user (bypass Google auth)
Route::get('/test-login-nts', function () {
    $user = \App\Models\User::where('email', 'ntstest@aes.ac.in')->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('spring.nts');
    }
    return 'NTS test user not found. Run: php artisan db:seed --class=ReportsTestDataSeeder';
});

// Claude Code Settings Interface
Route::prefix('claude-settings')->name('claude.settings.')->group(function () {
    Route::get('/', [ClaudeSettingsController::class, 'index'])->name('index');
    Route::get('/api/settings', [ClaudeSettingsController::class, 'getSettings'])->name('get');
    Route::post('/api/settings', [ClaudeSettingsController::class, 'saveSettings'])->name('save');
    Route::get('/api/sounds', [ClaudeSettingsController::class, 'listSounds'])->name('sounds');
    Route::post('/api/sounds/play', [ClaudeSettingsController::class, 'playSound'])->name('play');
    Route::post('/api/sounds/upload', [ClaudeSettingsController::class, 'uploadSound'])->name('upload');
    Route::delete('/api/sounds/delete', [ClaudeSettingsController::class, 'deleteSound'])->name('delete');
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
    // Default landing page for end users - Spring Wellness (shows full sub-nav)
    Route::get('/dashboard', function () {
        return redirect()->route('spring.wellness');
    })->name('dashboard');
    
    // My PL Routes
    Route::get('/my-pl', [MyPLController::class, 'index'])->name('my-pl.index');
    Route::post('/my-pl/toggle', [MyPLController::class, 'toggle'])->name('my-pl.toggle');
    Route::get('/my-pl/print', [MyPLController::class, 'print'])->name('my-pl.print');

    // Fall PL Day Routes
    Route::prefix('fall-pl-day')->group(function () {
        Route::get('/schedule/print', [ScheduleController::class, 'printSchedule'])->name('fall.schedule.print');
        Route::get('/schedule/{pdday?}', [ScheduleController::class, 'fallIndex'])->name('fall.schedule');
        Route::get('/schedule-item/{scheduleItem}', [ScheduleController::class, 'show'])->name('fall.schedule.show');
        Route::get('/wellness', [WellnessController::class, 'fallIndex'])->name('fall.wellness');
        Route::get('/wellness/{session}', [WellnessController::class, 'show'])->name('fall.wellness.show');
        Route::post('/wellness/{session}/enroll', [WellnessController::class, 'enroll'])->name('fall.wellness.enroll');
        Route::post('/wellness/{session}/unjoin', [WellnessController::class, 'unjoin'])->name('fall.wellness.unjoin');
    });

    // Spring PL Days Routes
    Route::prefix('spring-pl-days')->group(function () {
        Route::get('/schedule/print', [ScheduleController::class, 'printSchedule'])->name('spring.schedule.print');
        Route::get('/schedule/{pdday?}', [ScheduleController::class, 'springIndex'])->name('spring.schedule');
        Route::get('/schedule-item/{scheduleItem}', [ScheduleController::class, 'show'])->name('spring.schedule.show');
        Route::get('/wellness', [WellnessController::class, 'springIndex'])->name('spring.wellness');
        Route::get('/wellness/{session}', [WellnessController::class, 'show'])->name('spring.wellness.show');
        Route::post('/wellness/{session}/enroll', [WellnessController::class, 'enroll'])->name('spring.wellness.enroll');
        Route::post('/wellness/{session}/unjoin', [WellnessController::class, 'unjoin'])->name('spring.wellness.unjoin');
        Route::get('/ccl', [CCLController::class, 'index'])->name('spring.ccl');
        Route::get('/ccl/{ccl}', [CCLController::class, 'show'])->name('spring.ccl.show');
        Route::post('/ccl/{ccl}/join', [CCLController::class, 'join'])->name('spring.ccl.join');
        Route::post('/ccl/{ccl}/unjoin', [CCLController::class, 'unjoin'])->name('spring.ccl.unjoin');

        Route::get('/nts', [NTSController::class, 'index'])->name('spring.nts');
        Route::get('/nts/schedule-item/{scheduleItem}', [NTSController::class, 'show'])->name('spring.nts.schedule.show');
        Route::post('/nts/optional-signup/{scheduleItem}/join', [NTSController::class, 'joinOptionalSignup'])->name('spring.nts.optional.join');
        Route::post('/nts/optional-signup/{scheduleItem}/unjoin', [NTSController::class, 'unjoinOptionalSignup'])->name('spring.nts.optional.unjoin');
    });

    // Legacy Schedule routes (for backwards compatibility)
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/{scheduleItem}', [ScheduleController::class, 'show'])->name('schedule.show');
    
    // Legacy Wellness routes (for backwards compatibility)
    Route::get('/wellness', [WellnessController::class, 'index'])->name('wellness.index');
    Route::get('/wellness/{session}', [WellnessController::class, 'show'])->name('wellness.show');
    Route::post('/wellness/{session}/enroll', [WellnessController::class, 'enroll'])->name('wellness.enroll');
    Route::post('/wellness/{session}/unjoin', [WellnessController::class, 'unjoin'])->name('wellness.unjoin');
    
    // Professional Learning Wednesday
    Route::get('/professional-learning', [PLWednesdayController::class, 'index'])->name('pl-wednesday.index');
    Route::get('/professional-learning/{session}', [PLWednesdayController::class, 'show'])->name('pl-wednesday.show');
    
    // Earth Day Mini-PL Workshops
    Route::prefix('earth-day')->name('earth-day.')->group(function () {
        Route::get('/', [EarthDayController::class, 'index'])->name('index');
        Route::post('/{workshop}/enroll', [EarthDayController::class, 'enroll'])->name('enroll');
    });

    // Archive (hidden, accessible via direct URL)
    Route::get('/archive', [ScheduleController::class, 'archive'])->name('archive.index');
    
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
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{user}/update-password', [AdminController::class, 'updatePassword'])->name('users.update-password');
    
    // Wellness Sessions Management
    Route::get('/wellness/export', [WellnessSessionController::class, 'exportAll'])->name('wellness.export');
    Route::resource('wellness', WellnessSessionController::class);
    Route::get('/wellness/{wellness}/export-participants', [WellnessSessionController::class, 'exportParticipants'])->name('wellness.export-participants');
    Route::post('/wellness/{wellness}/toggle-status', [WellnessSessionController::class, 'toggleStatus'])->name('wellness.toggle-status');
    Route::post('/wellness/{wellness}/remove-enrollment', [WellnessSessionController::class, 'removeEnrollment'])->name('wellness.remove-enrollment');
    Route::get('/wellness/{wellness}/transfer', [WellnessSessionController::class, 'showTransfer'])->name('wellness.transfer');
    Route::post('/wellness/{wellness}/transfer-user', [WellnessSessionController::class, 'transferUser'])->name('wellness.transfer-user');
    
    // Schedule Items Management
    Route::resource('schedule', ScheduleItemController::class);
    Route::post('/schedule/{schedule}/toggle-status', [ScheduleItemController::class, 'toggleStatus'])->name('schedule.toggle-status');
    Route::post('/schedule/{schedule}/remove-enrollment', [ScheduleItemController::class, 'removeEnrollment'])->name('schedule.remove-enrollment');
    Route::post('/schedule/bulk-update', [ScheduleItemController::class, 'bulkUpdate'])->name('schedule.bulk-update');
    Route::get('/schedule-by-pdday', [ScheduleItemController::class, 'byPdDay'])->name('schedule.by-pdday');
    Route::get('/schedule-copy/{pdday}', [ScheduleItemController::class, 'showCopyForm'])->name('schedule.copy-form');
    Route::post('/schedule-copy/{pdday}', [ScheduleItemController::class, 'copySchedule'])->name('schedule.copy');
    Route::post('/schedule-upload/{pdday}', [ScheduleItemController::class, 'uploadCsv'])->name('schedule.upload-csv');
    
    // PL Days Management
    Route::resource('pddays', PDDayController::class)->except(['show']);
    Route::post('/pddays/{pdday}/toggle-active', [PDDayController::class, 'toggleActive'])->name('pddays.toggle-active');
    Route::post('/pddays/{pdday}/archive', [PDDayController::class, 'archive'])->name('pddays.archive');
    Route::post('/pddays/{pdday}/unarchive', [PDDayController::class, 'unarchive'])->name('pddays.unarchive');
    
    // PL Wednesday Management
    Route::post('/pl-wednesday/toggle-active', [AdminPLWednesdayController::class, 'toggleActive'])->name('pl-wednesday.toggle-active');
    Route::post('/pl-wednesday/{plWednesday}/toggle-status', [AdminPLWednesdayController::class, 'toggleSessionStatus'])->name('pl-wednesday.toggle-status');
    Route::resource('pl-wednesday', AdminPLWednesdayController::class);
    
    // CCL (Collaborative Community Learning Sessions) Management
    Route::get('/ccl/export', [AdminCCLController::class, 'export'])->name('ccl.export');
    Route::post('/ccl/toggle-active', [AdminCCLController::class, 'toggleActive'])->name('ccl.toggle-active');
    Route::post('/ccl/{ccl}/toggle-status', [AdminCCLController::class, 'toggleSessionStatus'])->name('ccl.toggle-status');
    Route::post('/ccl/{ccl}/remove-enrollment', [AdminCCLController::class, 'removeEnrollment'])->name('ccl.remove-enrollment');
    Route::resource('ccl', AdminCCLController::class);
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/wellness-enrollments', [ReportsController::class, 'wellnessEnrollments'])->name('reports.wellness-enrollments');
    Route::get('/reports/unenrolled-users', [ReportsController::class, 'unenrolledUsers'])->name('reports.unenrolled-users');
    Route::get('/reports/capacity-utilization', [ReportsController::class, 'capacityUtilization'])->name('reports.capacity-utilization');
    Route::get('/reports/division-summary', [ReportsController::class, 'divisionSummary'])->name('reports.division-summary');
    Route::get('/reports/user-activity', [ReportsController::class, 'userActivity'])->name('reports.user-activity');
    Route::get('/reports/ccl-enrollments', [ReportsController::class, 'cclEnrollments'])->name('reports.ccl-enrollments');
    Route::get('/reports/session-participant-lists', [ReportsController::class, 'sessionParticipantLists'])->name('reports.session-participant-lists');

    // Earth Day PL Management
    Route::get('/earth-day/export', [AdminEarthDayController::class, 'export'])->name('earth-day.export');
    Route::post('/earth-day/toggle-active', [AdminEarthDayController::class, 'toggleActive'])->name('earth-day.toggle-active');
    Route::post('/earth-day/{earthDay}/toggle-status', [AdminEarthDayController::class, 'toggleStatus'])->name('earth-day.toggle-status');
    Route::post('/earth-day/{earthDay}/remove-enrollment', [AdminEarthDayController::class, 'removeEnrollment'])->name('earth-day.remove-enrollment');
    Route::resource('earth-day', AdminEarthDayController::class);

    // Feature Toggles
    Route::post('/toggle-wellness', [AdminController::class, 'toggleWellness'])->name('toggle-wellness');
    Route::post('/toggle-pl-days', [AdminController::class, 'togglePLDays'])->name('toggle-pl-days');
});
