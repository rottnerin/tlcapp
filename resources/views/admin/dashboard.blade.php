<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Dark Mode Script -->
    <script>
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const storedTheme = localStorage.getItem('theme');
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <style>
        .admin-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Admin Navigation -->
    <nav class="bg-indigo-800 dark:bg-indigo-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-white text-lg font-bold">🛠️ AES Admin Panel</span>
                    <span class="ml-4 px-3 py-1 text-xs font-medium bg-yellow-400 text-yellow-900 rounded-full">
                        Administrator
                    </span>
                </div>
                
                <div class="flex items-center space-x-2 flex-1 justify-end">
                    <nav class="flex items-center space-x-2 flex-wrap">
                        <a href="{{ route('admin.pl-wednesday.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.pl-wednesday.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            PL Wednesday
                        </a>
                        <a href="{{ route('admin.pddays.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.pddays.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            PL Days
                        </a>
                        <a href="{{ route('admin.wellness.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.wellness.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Wellness
                        </a>
                        <a href="{{ route('admin.schedule.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.schedule.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Schedule
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.reports*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6 border dark:border-gray-700">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage the AES Professional Learning Days platform</p>
            
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

        <!-- Feature Settings -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6 border dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Feature Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Wellness Feature Toggle -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Wellness Feature</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Control visibility of Wellness sessions for regular users
                            </p>
                        </div>
                        <form action="{{ route('admin.toggle-wellness') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $wellnessSetting && $wellnessSetting->is_active ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">
                                {{ $wellnessSetting && $wellnessSetting->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- PL Days Feature Toggle -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">PL Days Feature</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Control visibility of Schedule/PL Days for regular users
                            </p>
                        </div>
                        <form action="{{ route('admin.toggle-pl-days') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 rounded-lg font-medium transition-colors {{ $plDaysSetting && $plDaysSetting->is_active ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">
                                {{ $plDaysSetting && $plDaysSetting->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions - Direct Create Links -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6 border dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>Quick Actions
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                <a href="{{ route('admin.schedule.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-lg text-center transition duration-200 group">
                    <div class="text-2xl mb-1">📅</div>
                    <div class="text-sm font-medium">+ Schedule Item</div>
                </a>
                <a href="{{ route('admin.wellness.create') }}" class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg text-center transition duration-200 group">
                    <div class="text-2xl mb-1">🧘</div>
                    <div class="text-sm font-medium">+ Wellness Session</div>
                </a>
                <a href="{{ route('admin.pddays.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-lg text-center transition duration-200 group">
                    <div class="text-2xl mb-1">📆</div>
                    <div class="text-sm font-medium">+ PL Day</div>
                </a>
                <a href="{{ route('admin.pl-wednesday.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white p-3 rounded-lg text-center transition duration-200 group">
                    <div class="text-2xl mb-1">📚</div>
                    <div class="text-sm font-medium">+ PL Wednesday</div>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg text-center transition duration-200 group">
                    <div class="text-2xl mb-1">👥</div>
                    <div class="text-sm font-medium">Manage Users</div>
                </a>
                <a href="{{ route('admin.reports') }}" class="bg-orange-600 hover:bg-orange-700 text-white p-3 rounded-lg text-center transition duration-200 group">
                    <div class="text-2xl mb-1">📊</div>
                    <div class="text-sm font-medium">View Reports</div>
                </a>
            </div>
        </div>

        <!-- Management Sections with Inline Add Buttons -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6 border dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Manage Content</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-purple-300 dark:hover:border-purple-600 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">📅</span>
                        <a href="{{ route('admin.schedule.create') }}" class="text-xs bg-purple-100 dark:bg-purple-900/30 hover:bg-purple-200 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 px-2 py-1 rounded transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add
                        </a>
                    </div>
                    <a href="{{ route('admin.schedule.index') }}" class="block">
                        <div class="font-medium text-gray-900 dark:text-gray-100">Schedule Items</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $stats['total_schedule_items'] }} items</div>
                    </a>
                </div>
                
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-green-300 dark:hover:border-green-600 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🧘</span>
                        <a href="{{ route('admin.wellness.create') }}" class="text-xs bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 px-2 py-1 rounded transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add
                        </a>
                    </div>
                    <a href="{{ route('admin.wellness.index') }}" class="block">
                        <div class="font-medium text-gray-900 dark:text-gray-100">Wellness Sessions</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $stats['total_wellness_sessions'] }} sessions</div>
                    </a>
                </div>
                
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">📆</span>
                        <a href="{{ route('admin.pddays.create') }}" class="text-xs bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-200 dark:hover:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add
                        </a>
                    </div>
                    <a href="{{ route('admin.pddays.index') }}" class="block">
                        <div class="font-medium text-gray-900 dark:text-gray-100">PL Days</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Manage PL days</div>
                    </a>
                </div>
                
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-teal-300 dark:hover:border-teal-600 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">📚</span>
                        <a href="{{ route('admin.pl-wednesday.create') }}" class="text-xs bg-teal-100 dark:bg-teal-900/30 hover:bg-teal-200 dark:hover:bg-teal-900/50 text-teal-700 dark:text-teal-300 px-2 py-1 rounded transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add
                        </a>
                    </div>
                    <a href="{{ route('admin.pl-wednesday.index') }}" class="block">
                        <div class="font-medium text-gray-900 dark:text-gray-100">PL Wednesday</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Manage sessions</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_users'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Users</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['total_schedule_items'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Schedule Items</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total_wellness_sessions'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Wellness Sessions</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['total_enrollments'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Confirmed Enrollments</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
            </div>
        </div>

        

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Registrations</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">View All →</a>
                </div>
                
                @if($recentUsers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded">
                                <div class="flex items-center space-x-3">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                                    @else
                                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($user->division)
                                        <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded">{{ $user->division->name }}</span>
                                    @endif
                                    @if($user->is_admin)
                                        <span class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded ml-1">Admin</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No recent registrations</p>
                @endif
            </div>

            <!-- Popular Sessions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Popular Wellness Sessions</h3>
                    <a href="{{ route('admin.reports') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">View Reports →</a>
                </div>
                
                @if($popularSessions->count() > 0)
                    <div class="space-y-3">
                        @foreach($popularSessions as $session)
                            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded">
                                <div>
                                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $session->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $session->start_time->format('M j, g:i A') }}
                                        @if($session->location)
                                            • {{ $session->location }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $session->user_sessions_count }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">enrollments</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No wellness sessions yet</p>
                @endif
            </div>
        </div>

        <!-- Division Breakdown -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Division Breakdown</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($divisionStats as $division)
                    <div class="text-center p-4 border border-gray-200 dark:border-gray-700 rounded">
                        <div class="text-2xl font-bold text-{{ $division->name === 'ES' ? 'green' : ($division->name === 'MS' ? 'blue' : 'orange') }}-600 dark:text-{{ $division->name === 'ES' ? 'green' : ($division->name === 'MS' ? 'blue' : 'orange') }}-400">
                            {{ $division->users_count }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $division->full_name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-500">{{ $division->name }} Division</div>
                    </div>
                @endforeach
            </div>
        </div>

        
    </div>
</body>
</html>
