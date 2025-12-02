<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wellness Sessions - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .aes-bg { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .session-card { transition: all 0.3s ease; }
        .session-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .session-full { opacity: 0.6; background-color: #f8f9fa; }
        .session-waitlist { border-left: 4px solid #f59e0b; }
        .session-available { border-left: 4px solid #10b981; }
        .session-full-border { border-left: 4px solid #ef4444; }
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
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                        <a href="{{ route('schedule.index') }}" class="text-gray-600 hover:text-gray-900">Schedule</a>
                        <a href="{{ route('wellness.index') }}" class="text-gray-900 font-medium">Wellness</a>
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
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Wellness Sessions</h1>
            <p class="text-gray-600">Choose from a variety of wellness activities to enhance your professional learning experience.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select id="date" name="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Dates</option>
                        @foreach($availableDates as $d)
                            <option value="{{ $d }}" {{ $date == $d ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($d)->format('M j, Y') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="flex items-center">
                        <input type="checkbox" name="available_only" value="1" {{ $available_only ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Available only</span>
                    </label>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Sessions Grid -->
        @if($sessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sessions as $session)
                    <div class="session-card bg-white rounded-lg shadow-sm border {{ $session->status === 'full' ? 'session-full session-full-border' : ($session->status === 'waitlist' ? 'session-waitlist' : 'session-available') }}">
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $session->title }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $session->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       ($session->status === 'waitlist' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($session->status) }}
                                </span>
                            </div>

                            <!-- Session Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $session->start_time->format('M j, g:i A') }} - {{ $session->end_time->format('g:i A') }}
                                </div>
                                
                                @if($session->location)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $session->location }}
                                    </div>
                                @endif

                                @if($session->presenter_name)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $session->presenter_name }}
                                    </div>
                                @endif
                            </div>

                            <!-- Capacity Info -->
                            <div class="mb-4">
                                @if($session->status === 'available')
                                    <div class="text-sm text-green-600">
                                        {{ $session->available_spots }} spots available
                                    </div>
                                @elseif($session->status === 'waitlist')
                                    <div class="text-sm text-yellow-600">
                                        Full - {{ $session->waitlist_count }} on waitlist
                                    </div>
                                @else
                                    <div class="text-sm text-red-600">
                                        Full - No waitlist
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($session->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $session->description }}</p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('wellness.show', $session) }}" 
                                   class="flex-1 text-center px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50 transition-colors">
                                    View Details
                                </a>
                                
                                @if($session->isAvailableForEnrollment())
                                    @if($session->userSessions->where('user_id', $user->id)->where('status', '!=', 'cancelled')->count() > 0)
                                        <span class="flex-1 text-center px-4 py-2 text-sm font-medium text-gray-500 bg-gray-100 rounded-md">
                                            Enrolled
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('wellness.enroll', $session) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                                {{ $session->status === 'waitlist' ? 'Join Waitlist' : 'Enroll' }}
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="flex-1 text-center px-4 py-2 text-sm font-medium text-gray-500 bg-gray-100 rounded-md">
                                        Not Available
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $sessions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No wellness sessions found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or check back later.</p>
            </div>
        @endif
    </div>
</body>
</html>
