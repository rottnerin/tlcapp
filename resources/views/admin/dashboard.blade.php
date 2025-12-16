<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); }
        .stat-card { transition: all 0.2s ease; background: #ffffff; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
        .action-btn { transition: all 0.15s ease; }
        .action-btn:hover { transform: scale(1.02); }
        .card { background: #ffffff; border: 1px solid #e5e7eb; }
    </style>
</head>
<body style="background: #f1f5f9; min-height: 100vh;">
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
                    <span class="px-2.5 py-1 text-xs font-semibold bg-amber-400 text-amber-900 rounded-md">
                        Admin
                    </span>
                </div>
                
                <div class="flex items-center space-x-1">
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('admin.schedule.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.schedule.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-calendar-alt mr-1.5 text-xs"></i>Schedule
                        </a>
                        <a href="{{ route('admin.wellness.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.wellness.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-heart mr-1.5 text-xs"></i>Wellness
                        </a>
                        <a href="{{ route('admin.pddays.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.pddays.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-calendar-check mr-1.5 text-xs"></i>PL Days
                        </a>
                        <a href="{{ route('admin.pl-wednesday.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.pl-wednesday.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-book mr-1.5 text-xs"></i>PL Wed
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-users mr-1.5 text-xs"></i>Users
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.reports*') ? 'text-white bg-white/10' : '' }}">
                            <i class="fas fa-chart-bar mr-1.5 text-xs"></i>Reports
                        </a>
                    </nav>
                    
                    <div class="flex items-center space-x-3 ml-4 pl-4 border-l border-white/20">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full ring-2 ring-white/20">
                        @else
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 rounded-lg hover:bg-white/10 transition-all" title="Logout">
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
            <h1 class="text-2xl font-bold" style="color: #1e293b;">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
            <p style="color: #64748b;" class="mt-1">Here's what's happening with your platform today.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
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
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Total Users</p>
                        <p class="text-3xl font-bold mt-1" style="color: #1e293b;">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #dbeafe;">
                        <i class="fas fa-users text-lg" style="color: #2563eb;"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Schedule Items</p>
                        <p class="text-3xl font-bold mt-1" style="color: #1e293b;">{{ $stats['total_schedule_items'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #ede9fe;">
                        <i class="fas fa-calendar-alt text-lg" style="color: #7c3aed;"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Wellness Sessions</p>
                        <p class="text-3xl font-bold mt-1" style="color: #1e293b;">{{ $stats['total_wellness_sessions'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #d1fae5;">
                        <i class="fas fa-heart text-lg" style="color: #059669;"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-5 shadow-sm" style="border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" style="color: #64748b;">Enrollments</p>
                        <p class="text-3xl font-bold mt-1" style="color: #1e293b;">{{ $stats['total_enrollments'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #fef3c7;">
                        <i class="fas fa-user-check text-lg" style="color: #d97706;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Feature Settings Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Quick Actions -->
            <div class="lg:col-span-2 card rounded-2xl p-6 shadow-sm">
                <h2 class="text-lg font-semibold mb-4" style="color: #1e293b;">Quick Actions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a href="{{ route('admin.schedule.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: #f5f3ff; border-color: #c4b5fd;">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: #7c3aed;">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: #6d28d9;">Schedule</span>
                    </a>
                    <a href="{{ route('admin.wellness.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: #ecfdf5; border-color: #6ee7b7;">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: #059669;">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: #047857;">Wellness</span>
                    </a>
                    <a href="{{ route('admin.pddays.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: #eff6ff; border-color: #93c5fd;">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: #2563eb;">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: #1d4ed8;">PL Day</span>
                    </a>
                    <a href="{{ route('admin.pl-wednesday.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: #f0fdfa; border-color: #5eead4;">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: #0d9488;">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <span class="text-sm font-medium" style="color: #0f766e;">PL Wed</span>
                    </a>
                </div>
            </div>

            <!-- Feature Toggles -->
            <div class="card rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">Feature Settings</h2>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full" style="background: #fef3c7; color: #b45309;">
                        <i class="fas fa-eye-slash mr-1"></i>Controls Visibility
                    </span>
                </div>
                <p class="text-xs mb-4" style="color: #64748b;">
                    Toggle features on/off. Disabled features will be hidden from the user dashboard.
                </p>
                <div class="space-y-3">
                    <!-- Wellness Toggle -->
                    <div class="p-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $wellnessSetting && $wellnessSetting->is_active ? '#d1fae5' : '#f1f5f9' }};">
                                    <i class="fas fa-heart" style="color: {{ $wellnessSetting && $wellnessSetting->is_active ? '#059669' : '#94a3b8' }};"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold block" style="color: #1e293b;">Wellness Sessions</span>
                                    <span class="text-xs" style="color: {{ $wellnessSetting && $wellnessSetting->is_active ? '#059669' : '#ef4444' }};">
                                        {{ $wellnessSetting && $wellnessSetting->is_active ? '● Visible to users' : '○ Hidden from users' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('admin.toggle-wellness') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        style="background: {{ $wellnessSetting && $wellnessSetting->is_active ? '#10b981' : '#d1d5db' }}; focus:ring-color: #10b981;"
                                        role="switch"
                                        aria-checked="{{ $wellnessSetting && $wellnessSetting->is_active ? 'true' : 'false' }}">
                                    <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                          style="transform: translateX({{ $wellnessSetting && $wellnessSetting->is_active ? '1.25rem' : '0' }});"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- PL Days Toggle -->
                    <div class="p-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $plDaysSetting && $plDaysSetting->is_active ? '#ede9fe' : '#f1f5f9' }};">
                                    <i class="fas fa-calendar-alt" style="color: {{ $plDaysSetting && $plDaysSetting->is_active ? '#7c3aed' : '#94a3b8' }};"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold block" style="color: #1e293b;">PL Days</span>
                                    <span class="text-xs" style="color: {{ $plDaysSetting && $plDaysSetting->is_active ? '#059669' : '#ef4444' }};">
                                        {{ $plDaysSetting && $plDaysSetting->is_active ? '● Visible to users' : '○ Hidden from users' }}
                                    </span>
                                </div>
                            </div>
                            <form action="{{ route('admin.toggle-pl-days') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        style="background: {{ $plDaysSetting && $plDaysSetting->is_active ? '#10b981' : '#d1d5db' }}; focus:ring-color: #10b981;"
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
            <a href="{{ route('admin.schedule.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: #e2e8f0;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: #ede9fe;">
                        <i class="fas fa-calendar-alt" style="color: #7c3aed;"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: #1e293b;">{{ $stats['total_schedule_items'] }}</span>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">Schedule Items</p>
            </a>
            
            <a href="{{ route('admin.wellness.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: #e2e8f0;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: #d1fae5;">
                        <i class="fas fa-heart" style="color: #059669;"></i>
                    </div>
                    <span class="text-2xl font-bold" style="color: #1e293b;">{{ $stats['total_wellness_sessions'] }}</span>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">Wellness Sessions</p>
            </a>
            
            <a href="{{ route('admin.pddays.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: #e2e8f0;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: #dbeafe;">
                        <i class="fas fa-calendar-check" style="color: #2563eb;"></i>
                    </div>
                    <i class="fas fa-arrow-right" style="color: #94a3b8;"></i>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">Manage PL Days</p>
            </a>
            
            <a href="{{ route('admin.pl-wednesday.index') }}" class="card rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group" style="border-color: #e2e8f0;">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background: #ccfbf1;">
                        <i class="fas fa-book" style="color: #0d9488;"></i>
                    </div>
                    <i class="fas fa-arrow-right" style="color: #94a3b8;"></i>
                </div>
                <p class="text-sm font-medium" style="color: #64748b;">PL Wednesday</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Registrations -->
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #e2e8f0;">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">Recent Registrations</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium" style="color: #7c3aed;">
                        View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($recentUsers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between p-3 rounded-xl" style="background: #f8fafc;">
                                    <div class="flex items-center space-x-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: #e2e8f0;">
                                                <span class="text-sm font-medium" style="color: #64748b;">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium" style="color: #1e293b;">{{ $user->name }}</p>
                                            <p class="text-xs" style="color: #64748b;">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($user->division)
                                            <span class="px-2 py-1 text-xs font-medium rounded-md" style="background: #dbeafe; color: #1d4ed8;">{{ $user->division->name }}</span>
                                        @endif
                                        @if($user->is_admin)
                                            <span class="px-2 py-1 text-xs font-medium rounded-md" style="background: #fee2e2; color: #991b1b;">Admin</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background: #f1f5f9;">
                                <i class="fas fa-users" style="color: #94a3b8;"></i>
                            </div>
                            <p class="text-sm" style="color: #64748b;">No recent registrations</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Popular Sessions -->
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid #e2e8f0;">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">Popular Wellness Sessions</h2>
                    <a href="{{ route('admin.reports') }}" class="text-sm font-medium" style="color: #7c3aed;">
                        Reports <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($popularSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($popularSessions->take(5) as $session)
                                <div class="flex items-center justify-between p-3 rounded-xl" style="background: #f8fafc;">
                                    <div class="flex-1 min-w-0 mr-4">
                                        <p class="text-sm font-medium truncate" style="color: #1e293b;">{{ $session->title }}</p>
                                        <p class="text-xs truncate" style="color: #64748b;">
                                            {{ $session->start_time->format('M j, g:i A') }}
                                            @if($session->location) • {{ Str::limit($session->location, 25) }} @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-1 px-2.5 py-1 rounded-lg" style="background: #d1fae5;">
                                        <span class="text-lg font-bold" style="color: #047857;">{{ $session->user_sessions_count }}</span>
                                        <i class="fas fa-user text-xs" style="color: #059669;"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background: #f1f5f9;">
                                <i class="fas fa-heart" style="color: #94a3b8;"></i>
                            </div>
                            <p class="text-sm" style="color: #64748b;">No wellness sessions yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Division Breakdown -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4" style="border-bottom: 1px solid #e2e8f0;">
                <h2 class="text-lg font-semibold" style="color: #1e293b;">Users by Division</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($divisionStats as $division)
                        @php
                            $colors = [
                                'ES' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                'MS' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                'HS' => ['bg' => '#ffedd5', 'text' => '#c2410c'],
                                'ALL' => ['bg' => '#f3e8ff', 'text' => '#7e22ce'],
                            ];
                            $color = $colors[$division->name] ?? $colors['ALL'];
                        @endphp
                        <div class="text-center p-4 rounded-xl" style="background: {{ $color['bg'] }};">
                            <div class="text-3xl font-bold" style="color: {{ $color['text'] }};">{{ $division->users_count }}</div>
                            <div class="text-sm font-medium mt-1" style="color: #475569;">{{ $division->full_name }}</div>
                            <div class="text-xs" style="color: #64748b;">{{ $division->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
