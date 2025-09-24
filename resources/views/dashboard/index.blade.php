<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .division-es { border-left: 4px solid #4CAF50; }
        .division-ms { border-left: 4px solid #2196F3; }
        .division-hs { border-left: 4px solid #FF9800; }
        .aes-bg { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
    </style>
</head>
<body class="antialiased bg-gray-50 aes-bg">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">AES Professional Learning Days</h1>
                    @if($user->division)
                        <span class="ml-4 px-3 py-1 text-xs font-medium bg-{{ strtolower($user->division->name) === 'es' ? 'green' : (strtolower($user->division->name) === 'ms' ? 'blue' : 'orange') }}-100 text-{{ strtolower($user->division->name) === 'es' ? 'green' : (strtolower($user->division->name) === 'ms' ? 'blue' : 'orange') }}-800 rounded-full">
                            {{ $user->division->full_name }}
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-900 font-medium">Dashboard</a>
                        <a href="{{ route('schedule.index') }}" class="text-gray-600 hover:text-gray-900">Schedule</a>
                        <a href="{{ route('wellness.index') }}" class="text-gray-600 hover:text-gray-900">Wellness</a>
                        <a href="{{ route('my-schedule') }}" class="text-gray-600 hover:text-gray-900">My Schedule</a>
                    </nav>
                    
                    <div class="flex items-center space-x-2">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                        @endif
                        <span class="text-sm text-gray-700">{{ $user->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Welcome back, {{ $user->name }}! 👋
                    </h2>
                    <p class="text-gray-600">Ready for an amazing Professional Learning Days experience on September 25-26, 2025?</p>
                </div>
            </div>
            
            @if (session('success'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $userEnrollments->count() }}</div>
                <div class="text-sm text-gray-600">Enrolled Sessions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-green-600">{{ $upcomingWellness->count() }}</div>
                <div class="text-sm text-gray-600">Available Wellness</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-orange-600">2</div>
                <div class="text-sm text-gray-600">Event Days</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $user->division ? $user->division->name : 'All' }}</div>
                <div class="text-sm text-gray-600">Your Division</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Schedule Overview -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Your Schedule Overview</h3>
                        <a href="{{ route('schedule.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View Full Schedule →</a>
                    </div>
                    
                    @if($scheduleItems->count() > 0)
                        @foreach($scheduleItems as $date => $dayItems)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-2">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h4>
                                <div class="space-y-2">
                                    @foreach($dayItems->take(3) as $item)
                                        <div class="border-l-4 {{ $user->division_id && $item->divisions->contains($user->division_id) ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50' }} pl-4 py-2">
                                            <div class="font-medium text-sm">{{ $item->title }}</div>
                                            <div class="text-xs text-gray-600">
                                                {{ $item->start_time->format('g:i A') }} - {{ $item->end_time->format('g:i A') }}
                                                @if($item->location)
                                                    • {{ $item->location }}
                                                @endif
                                            </div>
                                            @if($item->hasLinks())
                                                <div class="mt-1 space-y-1">
                                                    @foreach($item->links as $link)
                                                        <a href="{{ $link->formatted_url }}" 
                                                           target="_blank" 
                                                           rel="noopener noreferrer"
                                                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                            {{ $link->title }}
                                                        </a>
                                                        @if(!$loop->last)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($dayItems->count() > 3)
                                        <div class="text-sm text-gray-500 pl-4">
                                            + {{ $dayItems->count() - 3 }} more sessions
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-8">No schedule items available yet.</p>
                    @endif
                </div>
            </div>

            <!-- Wellness Sessions -->
            <div class="space-y-6">
                <!-- Enrolled Sessions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Wellness Sessions</h3>
                    @if($userEnrollments->count() > 0)
                        <div class="space-y-3">
                            @foreach($userEnrollments->take(3) as $enrollment)
                                @if($enrollment->wellnessSession)
                                    <div class="border border-gray-200 rounded p-3">
                                        <div class="font-medium text-sm">{{ $enrollment->wellnessSession->title }}</div>
                                        <div class="text-xs text-gray-600">
                                            {{ $enrollment->wellnessSession->start_time->format('M j, g:i A') }}
                                        </div>
                                        <span class="inline-block mt-1 px-2 py-1 text-xs rounded {{ $enrollment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('my-schedule') }}" class="block mt-4 text-center text-blue-600 hover:text-blue-800 text-sm">View All →</a>
                    @else
                        <p class="text-gray-500 text-sm">No wellness sessions enrolled yet.</p>
                        <a href="{{ route('wellness.index') }}" class="block mt-2 text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            Browse Wellness Sessions
                        </a>
                    @endif
                </div>

                <!-- Available Wellness -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Wellness Sessions</h3>
                    @if($upcomingWellness->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingWellness->take(3) as $session)
                                <div class="border border-gray-200 rounded p-3 hover:bg-gray-50 {{ $session->status === 'full' ? 'opacity-60 bg-gray-50' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="font-medium text-sm">{{ $session->title }}</div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                           {{ $session->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $session->start_time->format('M j, g:i A') }}
                                        @if($session->location)
                                            • {{ $session->location }}
                                        @endif
                                    </div>
                                    <div class="text-xs mt-1
                                        {{ $session->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                                        @if($session->status === 'available')
                                            {{ $session->available_spots }} spots available
                                        @else
                                            Full
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('wellness.index') }}" class="block mt-4 text-center text-blue-600 hover:text-blue-800 text-sm">View All →</a>
                    @else
                        <p class="text-gray-500 text-sm">No wellness sessions available yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Division Filter -->
        @if($divisions->count() > 0)
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter by Division</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($divisions as $division)
                        <a href="{{ route('dashboard', ['divisions' => [$division->id]]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium {{ in_array($division->id, $selectedDivisions) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $division->full_name }}
                        </a>
                    @endforeach
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 rounded-full text-sm font-medium {{ empty($selectedDivisions) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Divisions
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
