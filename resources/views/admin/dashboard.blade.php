<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - TLC Professional Learning</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --tlc-navy: #0d3b66;
            --tlc-cream: #faf0ca;
            --tlc-gold: #f4d35e;
            --tlc-orange: #ee964b;
        }
        body { font-family: 'Lexend', ui-sans-serif, system-ui, sans-serif; }
        .gradient-header { background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%); }
        .stat-card { transition: all 0.2s ease; background: #ffffff; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(13, 59, 102, 0.15); }
        .action-btn { transition: all 0.15s ease; }
        .action-btn:hover { transform: scale(1.02); }
        .card { background: #ffffff; border: 1px solid rgba(13, 59, 102, 0.1); }
        .activity-item { transition: all 0.15s ease; }
        .activity-item:hover { transform: translateX(4px); }
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
                        <a href="{{ route('admin.ccl.index') }}"
                           class="{{ request()->routeIs('admin.ccl.*') ? 'font-medium' : '' }}"
                           style="color: {{ request()->routeIs('admin.ccl.*') ? 'var(--tlc-gold)' : 'var(--tlc-cream)' }};">
                            CCL
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

        <!-- Welcome Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold" style="color: var(--tlc-navy);">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
                    <p style="color: #64748b;" class="mt-1">Here's your platform overview for today.</p>
                </div>
                <div class="mt-4 md:mt-0 text-sm" style="color: #64748b;">
                    <i class="far fa-calendar mr-2"></i>{{ now()->format('l, F j, Y') }}
                </div>
            </div>
        </div>

        <!-- Action Center -->
        <div class="mb-8">
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4" style="background: linear-gradient(135deg, rgba(13, 59, 102, 0.05) 0%, rgba(244, 211, 94, 0.1) 100%); border-bottom: 1px solid rgba(13, 59, 102, 0.1);">
                    <h2 class="text-xl font-semibold" style="color: var(--tlc-navy);">
                        <i class="fas fa-bolt mr-2" style="color: var(--tlc-gold);"></i>Action Center
                    </h2>
                    <p class="text-sm mt-1" style="color: #64748b;">Quick access to common tasks</p>
                </div>

                <div class="p-6">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-4" style="color: var(--tlc-navy);">Quick Actions</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                <a href="{{ route('admin.schedule.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(13, 59, 102, 0.05); border-color: rgba(13, 59, 102, 0.2);">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-navy);">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium" style="color: var(--tlc-navy);">Schedule</span>
                                </a>
                                <a href="{{ route('admin.wellness.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(238, 150, 75, 0.1); border-color: rgba(238, 150, 75, 0.3);">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-orange);">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium" style="color: var(--tlc-orange);">Wellness</span>
                                </a>
                                <a href="{{ route('admin.pddays.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(244, 211, 94, 0.2); border-color: rgba(244, 211, 94, 0.5);">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-gold);">
                                        <i class="fas fa-plus" style="color: var(--tlc-navy);"></i>
                                    </div>
                                    <span class="text-sm font-medium" style="color: var(--tlc-navy);">PL Day</span>
                                </a>
                                <a href="{{ route('admin.ccl.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(238, 150, 75, 0.1); border-color: rgba(238, 150, 75, 0.3);">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-orange);">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium" style="color: var(--tlc-orange);">CCL</span>
                                </a>
                                <a href="{{ route('admin.pl-wednesday.create') }}" class="action-btn flex flex-col items-center p-4 rounded-xl border group" style="background: rgba(13, 59, 102, 0.05); border-color: rgba(13, 59, 102, 0.2);">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background: var(--tlc-navy);">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium" style="color: var(--tlc-navy);">PL Wed</span>
                                </a>
                            </div>

                        <!-- Additional Quick Links -->
                        <div class="mt-4 pt-4" style="border-top: 1px solid rgba(13, 59, 102, 0.1);">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <a href="{{ route('admin.users.index') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-opacity-80 transition-colors" style="background: rgba(13, 59, 102, 0.1); color: var(--tlc-navy);">
                                    <i class="fas fa-users mr-1"></i>Manage Users
                                </a>
                                <a href="{{ route('admin.reports') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-opacity-80 transition-colors" style="background: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);">
                                    <i class="fas fa-chart-bar mr-1"></i>View Reports
                                </a>
                                <a href="{{ route('admin.schedule.index') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-opacity-80 transition-colors" style="background: rgba(238, 150, 75, 0.2); color: var(--tlc-navy);">
                                    <i class="fas fa-calendar-alt mr-1"></i>All Schedules
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- At-a-Glance - Key Stats -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold" style="color: var(--tlc-navy);">
                    <i class="fas fa-chart-line mr-2" style="color: var(--tlc-gold);"></i>At-a-Glance
                </h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
                            <p class="text-sm font-medium" style="color: #64748b;">Total Enrollments</p>
                            <p class="text-3xl font-bold mt-1" style="color: var(--tlc-navy);">{{ $stats['total_enrollments'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(244, 211, 94, 0.3);">
                            <i class="fas fa-user-check text-lg" style="color: var(--tlc-navy);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Center -->
        <div class="mb-8">
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid rgba(13, 59, 102, 0.1); background: linear-gradient(135deg, rgba(13, 59, 102, 0.05) 0%, rgba(244, 211, 94, 0.1) 100%);">
                    <h2 class="text-xl font-semibold" style="color: var(--tlc-navy);">
                        <i class="fas fa-stream mr-2" style="color: var(--tlc-gold);"></i>Activity Feed
                    </h2>
                    <span class="text-xs px-2 py-1 rounded-full" style="background: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);">
                        Recent Activity
                    </span>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @php
                            $allActivities = collect();
                            
                            // Add recent users
                            foreach($recentUsers as $user) {
                                $allActivities->push([
                                    'type' => 'user_registered',
                                    'user' => $user,
                                    'timestamp' => $user->created_at,
                                    'icon' => 'user-plus',
                                    'color' => 'var(--tlc-navy)',
                                    'bg' => 'rgba(13, 59, 102, 0.1)',
                                ]);
                            }
                            
                            // Add recent enrollments
                            foreach($recentEnrollments as $enrollment) {
                                $allActivities->push([
                                    'type' => 'enrollment',
                                    'enrollment' => $enrollment,
                                    'timestamp' => $enrollment->enrolled_at,
                                    'icon' => 'clipboard-check',
                                    'color' => 'var(--tlc-orange)',
                                    'bg' => 'rgba(238, 150, 75, 0.2)',
                                ]);
                            }
                            
                            // Sort by timestamp and take most recent 10
                            $allActivities = $allActivities->sortByDesc('timestamp')->take(10);
                        @endphp

                        @if($allActivities->count() > 0)
                            @foreach($allActivities as $activity)
                                <div class="activity-item flex items-start space-x-4 p-4 rounded-xl" style="background: rgba(250, 240, 202, 0.3);">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: {{ $activity['bg'] }};">
                                        <i class="fas fa-{{ $activity['icon'] }}" style="color: {{ $activity['color'] }};"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if($activity['type'] === 'user_registered')
                                            <p class="text-sm font-medium" style="color: var(--tlc-navy);">
                                                <span class="font-semibold">{{ $activity['user']->name }}</span> registered
                                                @if($activity['user']->division)
                                                    <span class="text-xs px-2 py-0.5 rounded-md ml-2" style="background: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);">
                                                        {{ $activity['user']->division->name }}
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-xs mt-1" style="color: #64748b;">
                                                {{ $activity['user']->email }} • {{ $activity['timestamp']->diffForHumans() }}
                                            </p>
                                        @elseif($activity['type'] === 'enrollment')
                                            <p class="text-sm font-medium" style="color: var(--tlc-navy);">
                                                <span class="font-semibold">{{ $activity['enrollment']->user->name }}</span> enrolled in
                                                @if($activity['enrollment']->wellnessSession)
                                                    <span class="font-semibold">{{ Str::limit($activity['enrollment']->wellnessSession->title, 40) }}</span>
                                                @elseif($activity['enrollment']->scheduleItem)
                                                    <span class="font-semibold">{{ Str::limit($activity['enrollment']->scheduleItem->title, 40) }}</span>
                                                @endif
                                            </p>
                                            <p class="text-xs mt-1" style="color: #64748b;">
                                                {{ $activity['timestamp']->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background: rgba(13, 59, 102, 0.1);">
                                    <i class="fas fa-inbox" style="color: var(--tlc-navy);"></i>
                                </div>
                                <p class="text-sm" style="color: #64748b;">No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed -->

        <!-- Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Division Breakdown -->
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid rgba(13, 59, 102, 0.1); background: linear-gradient(135deg, rgba(13, 59, 102, 0.05) 0%, rgba(244, 211, 94, 0.1) 100%);">
                    <h2 class="text-lg font-semibold" style="color: var(--tlc-navy);">
                        <i class="fas fa-sitemap mr-2" style="color: var(--tlc-gold);"></i>Users by Division
                    </h2>
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

            <!-- Popular Sessions -->
            <div class="card rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid rgba(13, 59, 102, 0.1); background: linear-gradient(135deg, rgba(13, 59, 102, 0.05) 0%, rgba(244, 211, 94, 0.1) 100%);">
                    <h2 class="text-lg font-semibold" style="color: var(--tlc-navy);">
                        <i class="fas fa-fire mr-2" style="color: var(--tlc-orange);"></i>Popular Wellness Sessions
                    </h2>
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
    </div>
</body>
</html>
