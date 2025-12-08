<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .admin-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <!-- Admin Navigation -->
    <nav class="bg-indigo-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-white text-lg font-bold">🛠️ AES Admin Panel</span>
                    <span class="ml-4 px-3 py-1 text-xs font-medium bg-yellow-400 text-yellow-900 rounded-full">
                        Administrator
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        <a href="{{ route('admin.pl-wednesday.index') }}" 
                           class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.pl-wednesday.*') ? 'text-white font-medium' : '' }}">
                            PL Wednesday
                        </a>
                        <a href="{{ route('admin.pddays.index') }}" 
                           class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.pddays.*') ? 'text-white font-medium' : '' }}">
                            PL Days
                        </a>
                        <a href="{{ route('admin.wellness.index') }}" 
                           class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.wellness.*') ? 'text-white font-medium' : '' }}">
                            Wellness
                        </a>
                        <a href="{{ route('admin.schedule.index') }}" 
                           class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.schedule.*') ? 'text-white font-medium' : '' }}">
                            Schedule
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.users.*') ? 'text-white font-medium' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.reports*') ? 'text-white font-medium' : '' }}">
                            Reports
                        </a>
                    </nav>
                    
                    <div class="flex items-center space-x-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                        @endif
                        <span class="text-sm text-indigo-200">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-300 hover:text-red-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">Manage the AES Professional Learning Days platform</p>
            
            @if (session('success'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition duration-200">
                    <div class="text-lg font-medium">👥 Manage Users</div>
                    <div class="text-sm opacity-90">View and edit user accounts</div>
                </a>
                <a href="{{ route('admin.wellness.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition duration-200">
                    <div class="text-lg font-medium">🧘 Wellness Sessions</div>
                    <div class="text-sm opacity-90">Manage wellness offerings</div>
                </a>
                <a href="{{ route('admin.schedule.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition duration-200">
                    <div class="text-lg font-medium">📅 Schedule Items</div>
                    <div class="text-sm opacity-90">Manage event schedule</div>
                </a>
                <a href="{{ route('admin.reports') }}" class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-lg text-center transition duration-200">
                    <div class="text-lg font-medium">📊 Reports</div>
                    <div class="text-sm opacity-90">View analytics & reports</div>
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</div>
                <div class="text-sm text-gray-600">Total Users</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-3xl font-bold text-green-600">{{ $stats['total_schedule_items'] }}</div>
                <div class="text-sm text-gray-600">Schedule Items</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['total_wellness_sessions'] }}</div>
                <div class="text-sm text-gray-600">Wellness Sessions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['total_enrollments'] }}</div>
                <div class="text-sm text-gray-600">Confirmed Enrollments</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
            </div>
        </div>

        

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Registrations</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View All →</a>
                </div>
                
                @if($recentUsers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded">
                                <div class="flex items-center space-x-3">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                                    @else
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-sm">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($user->division)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $user->division->name }}</span>
                                    @endif
                                    @if($user->is_admin)
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded ml-1">Admin</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No recent registrations</p>
                @endif
            </div>

            <!-- Popular Sessions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Popular Wellness Sessions</h3>
                    <a href="{{ route('admin.reports') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View Reports →</a>
                </div>
                
                @if($popularSessions->count() > 0)
                    <div class="space-y-3">
                        @foreach($popularSessions as $session)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded">
                                <div>
                                    <div class="font-medium text-sm">{{ $session->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $session->start_time->format('M j, g:i A') }}
                                        @if($session->location)
                                            • {{ $session->location }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-green-600">{{ $session->user_sessions_count }}</div>
                                    <div class="text-xs text-gray-500">enrollments</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No wellness sessions yet</p>
                @endif
            </div>
        </div>

        <!-- Division Breakdown -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Division Breakdown</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($divisionStats as $division)
                    <div class="text-center p-4 border border-gray-200 rounded">
                        <div class="text-2xl font-bold text-{{ $division->name === 'ES' ? 'green' : ($division->name === 'MS' ? 'blue' : 'orange') }}-600">
                            {{ $division->users_count }}
                        </div>
                        <div class="text-sm text-gray-600">{{ $division->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $division->name }} Division</div>
                    </div>
                @endforeach
            </div>
        </div>

        
    </div>
</body>
</html>
