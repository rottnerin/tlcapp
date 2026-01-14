<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - TLC Professional Learning</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --tlc-navy: #0d3b66;
            --tlc-cream: #faf0ca;
            --tlc-gold: #f4d35e;
            --tlc-orange: #ee964b;
        }
        body { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; }
        .gradient-header { background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%); }
        .stat-card { transition: all 0.2s ease; background: #ffffff; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(13, 59, 102, 0.15); }
        .action-btn { transition: all 0.15s ease; }
        .action-btn:hover { transform: scale(1.02); }
        .card { background: #ffffff; border: 1px solid rgba(13, 59, 102, 0.1); }
    </style>
</head>
<body style="background: var(--tlc-cream); min-height: 100vh;">
    <!-- Admin Navigation Bar -->
    <nav class="gradient-header shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="hidden md:flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <img src="https://visitors.aes.ac.in/images/aes.png" alt="TLC Admin Panel" class="h-10 w-auto">
                    </a>
                    <span class="ml-4 px-2.5 py-1 text-xs font-semibold rounded-md" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                        Admin
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        <a href="{{ route('admin.pl-wednesday.index') }}" 
                           class="{{ request()->routeIs('admin.pl-wednesday.*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.pl-wednesday.*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            PL Wednesday
                        </a>
                        <a href="{{ route('admin.pddays.index') }}" 
                           class="{{ request()->routeIs('admin.pddays.*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.pddays.*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            PL Days
                        </a>
                        <a href="{{ route('admin.wellness.index') }}" 
                           class="{{ request()->routeIs('admin.wellness.*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.wellness.*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            Wellness
                        </a>
                        <a href="{{ route('admin.schedule.index') }}" 
                           class="{{ request()->routeIs('admin.schedule.*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.schedule.*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            Schedule
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="{{ request()->routeIs('admin.users.*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.users.*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            Users
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="{{ request()->routeIs('admin.reports*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.reports*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            Reports
                        </a>
                    </nav>

                    <div class="flex items-center space-x-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full ring-2" style="--tw-ring-color: rgba(244, 211, 94, 0.5);">
                        @endif
                        <span class="text-sm hidden lg:inline" style="color: var(--tlc-cream);">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm" style="color: var(--tlc-orange);">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold" style="color: var(--tlc-navy);">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
            <p style="color: #64748b;" class="mt-1">Here's what's happening with your platform today.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: rgba(244, 211, 94, 0.2); border: 1px solid var(--tlc-gold); color: var(--tlc-navy);">
                <i class="fas fa-check-circle mr-3"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                <i class="fas fa-exclamation-circle mr-3"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid rgba(13, 59, 102, 0.1); border-top: 3px solid var(--tlc-navy);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Total Users</p>
                        <p class="text-3xl font-bold mt-1" style="color: var(--tlc-navy);">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(13, 59, 102, 0.1);">
                        <i class="fas fa-users text-lg" style="color: var(--tlc-navy);"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid rgba(13, 59, 102, 0.1); border-top: 3px solid var(--tlc-gold);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Schedule Items</p>
                        <p class="text-3xl font-bold mt-1" style="color: var(--tlc-navy);">{{ $stats['total_schedule_items'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(244, 211, 94, 0.3);">
                        <i class="fas fa-calendar-alt text-lg" style="color: var(--tlc-navy);"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid rgba(13, 59, 102, 0.1); border-top: 3px solid var(--tlc-orange);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Wellness Sessions</p>
                        <p class="text-3xl font-bold mt-1" style="color: var(--tlc-navy);">{{ $stats['total_wellness_sessions'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(238, 150, 75, 0.2);">
                        <i class="fas fa-heart text-lg" style="color: var(--tlc-orange);"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid rgba(13, 59, 102, 0.1); border-top: 3px solid var(--tlc-navy);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Enrollments</p>
                        <p class="text-3xl font-bold mt-1" style="color: var(--tlc-navy);">{{ $stats['total_enrollments'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(244, 211, 94, 0.3);">
                        <i class="fas fa-user-check text-lg" style="color: var(--tlc-navy);"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Feature Settings Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Quick Actions -->
            <div class="lg:col-span-2 card rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--tlc-navy);">Quick Actions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a href="{{ route('admin.schedule.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(13, 59, 102, 0.05); border-color: rgba(13, 59, 102, 0.2);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-navy);">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: var(--tlc-navy);">Schedule</span>
                    </a>
                    <a href="{{ route('admin.wellness.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(238, 150, 75, 0.1); border-color: rgba(238, 150, 75, 0.3);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-orange);">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: var(--tlc-orange);">Wellness</span>
                    </a>
                    <a href="{{ route('admin.pddays.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(244, 211, 94, 0.2); border-color: rgba(244, 211, 94, 0.5);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-gold);">
                            <i class="fas fa-plus" style="color: var(--tlc-navy);"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: var(--tlc-navy);">PL Day</span>
                    </a>
                    <a href="{{ route('admin.pl-wednesday.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(13, 59, 102, 0.05); border-color: rgba(13, 59, 102, 0.2);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-navy);">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: var(--tlc-navy);">PL Wed</span>
                    </a>
                </div>
            </div>

            <!-- Feature Toggles -->
            <div class="card rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold" style="color: var(--tlc-navy);">Feature Settings</h2>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full" style="background: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);">
                        <i class="fas fa-eye-slash mr-1"></i>Controls Visibility
                    </span>
                </div>
                <p class="text-xs mb-4" style="color: #64748b;">
                    Toggle features on/off. Disabled features will be hidden from the user dashboard.
                </p>
                <div class="space-y-3">
                    <!-- Wellness Toggle -->
                    <div class="p-4 rounded-xl" style="background: rgba(250, 240, 202, 0.5); border: 1px solid rgba(13, 59, 102, 0.1);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $wellnessSetting && $wellnessSetting->is_active ? 'rgba(238, 150, 75, 0.2)' : '#f1f5f9' }};">
                                    <i class="fas fa-heart" style="color: {{ $wellnessSetting && $wellnessSetting->is_active ? 'var(--tlc-orange)' : '#94a3b8' }};"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold block" style="color: var(--tlc-navy);">Wellness Sessions</span>
                                    <span class="text-xs" style="color: {{ $wellnessSetting && $wellnessSetting->is_active ? 'var(--tlc-navy)' : '#ef4444' }};">
                                        {{ $wellnessSetting && $wellnessSetting->is_active ? '● Visible to users' : '○ Hidden from users' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('admin.toggle-wellness') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        style="background: {{ $wellnessSetting && $wellnessSetting->is_active ? 'var(--tlc-orange)' : '#d1d5db' }};"
                                        role="switch"
                                        aria-checked="{{ $wellnessSetting && $wellnessSetting->is_active ? 'true' : 'false' }}">
                                    <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                          style="transform: translateX({{ $wellnessSetting && $wellnessSetting->is_active ? '1.25rem' : '0' }});"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- PL Days Toggle -->
                    <div class="p-4 rounded-xl" style="background: rgba(250, 240, 202, 0.5); border: 1px solid rgba(13, 59, 102, 0.1);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $plDaysSetting && $plDaysSetting->is_active ? 'rgba(244, 211, 94, 0.3)' : '#f1f5f9' }};">
                                    <i class="fas fa-calendar-alt" style="color: {{ $plDaysSetting && $plDaysSetting->is_active ? 'var(--tlc-navy)' : '#94a3b8' }};"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold block" style="color: var(--tlc-navy);">PL Days</span>
                                    <span class="text-xs" style="color: {{ $plDaysSetting && $plDaysSetting->is_active ? 'var(--tlc-navy)' : '#ef4444' }};">
                                        {{ $plDaysSetting && $plDaysSetting->is_active ? '● Visible to users' : '○ Hidden from users' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('admin.toggle-pl-days') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        style="background: {{ $plDaysSetting && $plDaysSetting->is_active ? 'var(--tlc-gold)' : '#d1d5db' }};"
                                        role="switch"
                                        aria-checked="{{ $plDaysSetting && $plDaysSetting->is_active ? 'true' : 'false' }}">
                                    <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                          style="transform: translateX({{ $plDaysSetting && $plDaysSetting->is_active ? '1.25rem' : '0' }});"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Management -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.schedule.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: rgba(13, 59, 102, 0.1);">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: rgba(13, 59, 102, 0.1);">
                        <i class="fas fa-calendar-alt" style="color: var(--tlc-navy);"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: var(--tlc-navy);">{{ $stats['total_schedule_items'] }}</span>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">Schedule Items</p>
            </a>
            
            <a href="{{ route('admin.wellness.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: rgba(13, 59, 102, 0.1);">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: rgba(238, 150, 75, 0.2);">
                        <i class="fas fa-heart" style="color: var(--tlc-orange);"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: var(--tlc-navy);">{{ $stats['total_wellness_sessions'] }}</span>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">Wellness Sessions</p>
            </a>
            
            <a href="{{ route('admin.pddays.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: rgba(13, 59, 102, 0.1);">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: rgba(244, 211, 94, 0.3);">
                        <i class="fas fa-calendar-check" style="color: var(--tlc-navy);"></i>
                    </div>
                    <i class="fas fa-arrow-right" style="color: var(--tlc-gold);"></i>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">Manage PL Days</p>
            </a>
            
            <a href="{{ route('admin.pl-wednesday.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: rgba(13, 59, 102, 0.1);">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: rgba(13, 59, 102, 0.1);">
                        <i class="fas fa-book" style="color: var(--tlc-navy);"></i>
                    </div>
                    <i class="fas fa-arrow-right" style="color: var(--tlc-gold);"></i>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">PL Wednesday</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Registrations -->
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid rgba(13, 59, 102, 0.1);">
                    <h2 class="text-lg font-semibold" style="color: var(--tlc-navy);">Recent Registrations</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium" style="color: var(--tlc-orange);">
                        View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($recentUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between p-3 rounded-xl" style="background: rgba(250, 240, 202, 0.3);">
                                    <div class="flex items-center space-x-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(13, 59, 102, 0.1);">
                                                <span class="text-sm font-medium" style="color: var(--tlc-navy);">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium" style="color: var(--tlc-navy);">{{ $user->name }}</p>
                                            <p class="text-xs" style="color: #64748b;">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($user->division)
                                            <span class="px-2 py-1 text-xs font-medium rounded-md" style="background: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);">{{ $user->division->name }}</span>
                                        @endif
                                        @if($user->is_admin)
                                            <span class="px-2 py-1 text-xs font-medium rounded-md" style="background: rgba(238, 150, 75, 0.2); color: var(--tlc-navy);">Admin</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background: rgba(13, 59, 102, 0.1);">
                                <i class="fas fa-users" style="color: var(--tlc-navy);"></i>
                            </div>
                            <p class="text-sm" style="color: #64748b;">No recent registrations</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Popular Sessions -->
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid rgba(13, 59, 102, 0.1);">
                    <h2 class="text-lg font-semibold" style="color: var(--tlc-navy);">Popular Wellness Sessions</h2>
                    <a href="{{ route('admin.reports') }}" class="text-sm font-medium" style="color: var(--tlc-orange);">
                        Reports <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($popularSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($popularSessions->take(5) as $session)
                                <div class="flex items-center justify-between p-3 rounded-xl" style="background: rgba(250, 240, 202, 0.3);">
                                    <div class="flex-1 min-w-0 mr-4">
                                        <p class="text-sm font-medium truncate" style="color: var(--tlc-navy);">{{ $session->title }}</p>
                                        <p class="text-xs truncate" style="color: #64748b;">
                                            {{ $session->start_time->format('M j, g:i A') }}
                                            @if($session->location) • {{ Str::limit($session->location, 25) }} @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-1 px-2.5 py-1 rounded-lg" style="background: rgba(244, 211, 94, 0.3);">
                                        <span class="text-lg font-bold" style="color: var(--tlc-navy);">{{ $session->user_sessions_count }}</span>
                                        <i class="fas fa-user text-xs" style="color: var(--tlc-orange);"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background: rgba(238, 150, 75, 0.2);">
                                <i class="fas fa-heart" style="color: var(--tlc-orange);"></i>
                            </div>
                            <p class="text-sm" style="color: #64748b;">No wellness sessions yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Division Breakdown -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4" style="border-bottom: 1px solid rgba(13, 59, 102, 0.1);">
                <h2 class="text-lg font-semibold" style="color: var(--tlc-navy);">Users by Division</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($divisionStats as $division)
                        @php
                            $colors = [
                                'ES' => ['bg' => 'rgba(244, 211, 94, 0.3)', 'text' => 'var(--tlc-navy)'],
                                'MS' => ['bg' => 'rgba(238, 150, 75, 0.2)', 'text' => 'var(--tlc-navy)'],
                                'HS' => ['bg' => 'rgba(13, 59, 102, 0.1)', 'text' => 'var(--tlc-navy)'],
                                'ALL' => ['bg' => 'rgba(250, 240, 202, 0.5)', 'text' => 'var(--tlc-navy)'],
                            ];
                            $color = $colors[$division->name] ?? $colors['ALL'];
                        @endphp
                        <div class="text-center p-4 rounded-xl" style="background: {{ $color['bg'] }};">
                            <div class="text-3xl font-bold" style="color: {{ $color['text'] }};">{{ $division->users_count }}</div>
                            <div class="text-sm font-medium mt-1" style="color: var(--tlc-navy);">{{ $division->full_name }}</div>
                            <div class="text-xs" style="color: #64748b;">{{ $division->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
