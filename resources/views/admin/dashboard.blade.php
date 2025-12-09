<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const storedTheme = localStorage.getItem('theme');
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); }
        .stat-card { transition: all 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); }
        .action-btn { transition: all 0.15s ease; }
        .action-btn:hover { transform: scale(1.02); }
    </style>
</head>
<body class="antialiased bg-slate-100 dark:bg-slate-900 min-h-screen">
    <!-- Modern Admin Navigation -->
    <nav class="gradient-header shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <span class="text-white text-lg font-semibold tracking-tight">AES Admin</span>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold bg-amber-400/90 text-amber-950 rounded-md">
                        Admin
                    </span>
                </div>
                
                <div class="flex items-center space-x-1">
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('admin.schedule.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.schedule.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-calendar-alt mr-1.5 text-xs"></i>Schedule
                        </a>
                        <a href="{{ route('admin.wellness.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.wellness.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-heart mr-1.5 text-xs"></i>Wellness
                        </a>
                        <a href="{{ route('admin.pddays.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.pddays.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-calendar-check mr-1.5 text-xs"></i>PL Days
                        </a>
                        <a href="{{ route('admin.pl-wednesday.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.pl-wednesday.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-book mr-1.5 text-xs"></i>PL Wed
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-users mr-1.5 text-xs"></i>Users
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="px-3 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.reports*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-chart-bar mr-1.5 text-xs"></i>Reports
                        </a>
                    </nav>
                    
                    <div class="flex items-center space-x-3 ml-4 pl-4 border-l border-white/20">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/10 transition-all" title="Toggle theme">
                            <i class="fas fa-moon text-sm dark:hidden"></i>
                            <i class="fas fa-sun text-sm hidden dark:block"></i>
                        </button>
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full ring-2 ring-white/20">
                        @else
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-400 rounded-lg hover:bg-white/10 transition-all" title="Logout">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Here's what's happening with your platform today.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl flex items-center">
                <i class="fas fa-check-circle mr-3"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Users</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Schedule Items</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_schedule_items'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-violet-100 dark:bg-violet-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-violet-600 dark:text-violet-400 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Wellness Sessions</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_wellness_sessions'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-heart text-emerald-600 dark:text-emerald-400 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Enrollments</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_enrollments'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-check text-amber-600 dark:text-amber-400 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Feature Settings Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Quick Actions -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a href="{{ route('admin.schedule.create') }}" class="action-btn flex flex-col items-center p-4 bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/30 rounded-xl border border-violet-200 dark:border-violet-800 group">
                        <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-violet-700 dark:text-violet-300">Schedule</span>
                    </a>
                    <a href="{{ route('admin.wellness.create') }}" class="action-btn flex flex-col items-center p-4 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 rounded-xl border border-emerald-200 dark:border-emerald-800 group">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Wellness</span>
                    </a>
                    <a href="{{ route('admin.pddays.create') }}" class="action-btn flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-xl border border-blue-200 dark:border-blue-800 group">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">PL Day</span>
                    </a>
                    <a href="{{ route('admin.pl-wednesday.create') }}" class="action-btn flex flex-col items-center p-4 bg-teal-50 dark:bg-teal-900/20 hover:bg-teal-100 dark:hover:bg-teal-900/30 rounded-xl border border-teal-200 dark:border-teal-800 group">
                        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-teal-700 dark:text-teal-300">PL Wed</span>
                    </a>
                </div>
            </div>

            <!-- Feature Toggles -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Feature Settings</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-heart text-emerald-600 dark:text-emerald-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Wellness</span>
                        </div>
                        <form action="{{ route('admin.toggle-wellness') }}" method="POST">
                            @csrf
                            <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $wellnessSetting && $wellnessSetting->is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform {{ $wellnessSetting && $wellnessSetting->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </form>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-violet-600 dark:text-violet-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">PL Days</span>
                        </div>
                        <form action="{{ route('admin.toggle-pl-days') }}" method="POST">
                            @csrf
                            <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $plDaysSetting && $plDaysSetting->is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform {{ $plDaysSetting && $plDaysSetting->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Management -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.schedule.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:border-violet-300 dark:hover:border-violet-600 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-alt text-violet-600 dark:text-violet-400"></i>
                    </div>
                    <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_schedule_items'] }}</span>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Schedule Items</p>
            </a>
            
            <a href="{{ route('admin.wellness.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:border-emerald-300 dark:hover:border-emerald-600 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-heart text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_wellness_sessions'] }}</span>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Wellness Sessions</p>
            </a>
            
            <a href="{{ route('admin.pddays.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <i class="fas fa-arrow-right text-slate-400 dark:text-slate-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Manage PL Days</p>
            </a>
            
            <a href="{{ route('admin.pl-wednesday.index') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:border-teal-300 dark:hover:border-teal-600 transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-book text-teal-600 dark:text-teal-400"></i>
                    </div>
                    <i class="fas fa-arrow-right text-slate-400 dark:text-slate-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">PL Wednesday</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Registrations -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Registrations</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300">
                        View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($recentUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <div class="w-10 h-10 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($user->division)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-md">{{ $user->division->name }}</span>
                                        @endif
                                        @if($user->is_admin)
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-md">Admin</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-slate-400"></i>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No recent registrations</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Popular Sessions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Popular Wellness Sessions</h2>
                    <a href="{{ route('admin.reports') }}" class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300">
                        Reports <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($popularSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($popularSessions->take(5) as $session)
                                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                                    <div class="flex-1 min-w-0 mr-4">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $session->title }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            {{ $session->start_time->format('M j, g:i A') }}
                                            @if($session->location) • {{ Str::limit($session->location, 25) }} @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-1 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-lg">
                                        <span class="text-lg font-bold text-emerald-700 dark:text-emerald-300">{{ $session->user_sessions_count }}</span>
                                        <i class="fas fa-user text-emerald-600 dark:text-emerald-400 text-xs"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-heart text-slate-400"></i>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No wellness sessions yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Division Breakdown -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Users by Division</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($divisionStats as $division)
                        @php
                            $colors = [
                                'ES' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-300', 'icon' => 'text-green-600 dark:text-green-400'],
                                'MS' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-300', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                'HS' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-700 dark:text-orange-300', 'icon' => 'text-orange-600 dark:text-orange-400'],
                                'ALL' => ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-700 dark:text-purple-300', 'icon' => 'text-purple-600 dark:text-purple-400'],
                            ];
                            $color = $colors[$division->name] ?? $colors['ALL'];
                        @endphp
                        <div class="text-center p-4 {{ $color['bg'] }} rounded-xl">
                            <div class="text-3xl font-bold {{ $color['text'] }}">{{ $division->users_count }}</div>
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-300 mt-1">{{ $division->full_name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $division->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    const html = document.documentElement;
                    const isDark = html.classList.contains('dark');
                    
                    if (isDark) {
                        html.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        html.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }
        });
    </script>
</body>
</html>
