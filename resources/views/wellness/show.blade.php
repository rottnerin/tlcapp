<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $session->title }} - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .aes-bg { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
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

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('wellness.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Wellness Sessions
            </a>
        </div>

        <!-- Session Details -->
        <div class="bg-white rounded-lg shadow-sm border {{ $session->status === 'full' ? 'session-full session-full-border' : ($session->status === 'waitlist' ? 'session-waitlist' : 'session-available') }}">
            <div class="p-8">
                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $session->title }}</h1>
                        @if($session->category)
                            <span class="inline-block px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $session->category }}
                            </span>
                        @endif
                    </div>
                    <span class="px-4 py-2 text-sm font-medium rounded-full
                        {{ $session->status === 'available' ? 'bg-green-100 text-green-800' : 
                           ($session->status === 'waitlist' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-red-100 text-red-800') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>

                <!-- Session Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Date & Time</div>
                                <div class="text-sm text-gray-600">
                                    {{ $session->start_time->format('l, F j, Y') }}<br>
                                    {{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }}
                                </div>
                            </div>
                        </div>

                        @if($session->location)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Location</div>
                                    <div class="text-sm text-gray-600">{{ $session->location }}</div>
                                </div>
                            </div>
                        @endif

                        @if($session->presenter_name)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Presenter</div>
                                    <div class="text-sm text-gray-600">{{ $session->presenter_name }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-900 mb-2">Capacity</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $session->current_enrollment }} / {{ $session->max_participants }}</div>
                            @if($session->status === 'available')
                                <div class="text-sm text-green-600 mt-1">{{ $session->available_spots }} spots available</div>
                            @elseif($session->status === 'waitlist')
                                <div class="text-sm text-yellow-600 mt-1">{{ $session->waitlist_count }} on waitlist</div>
                            @else
                                <div class="text-sm text-red-600 mt-1">Session is full</div>
                            @endif
                        </div>

                        @if($session->equipment_needed)
                            <div>
                                <div class="text-sm font-medium text-gray-900 mb-2">Equipment Needed</div>
                                <div class="text-sm text-gray-600">{{ $session->equipment_needed }}</div>
                            </div>
                        @endif

                        @if($session->special_requirements)
                            <div>
                                <div class="text-sm font-medium text-gray-900 mb-2">Special Requirements</div>
                                <div class="text-sm text-gray-600">{{ $session->special_requirements }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($session->description)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                        <div class="prose max-w-none text-gray-600">
                            {{ $session->description }}
                        </div>
                    </div>
                @endif

                <!-- Presenter Bio -->
                @if($session->presenter_bio)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">About the Presenter</h3>
                        <div class="prose max-w-none text-gray-600">
                            {{ $session->presenter_bio }}
                        </div>
                    </div>
                @endif

                <!-- Preparation Notes -->
                @if($session->preparation_notes)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Preparation Notes</h3>
                        <div class="prose max-w-none text-gray-600">
                            {{ $session->preparation_notes }}
                        </div>
                    </div>
                @endif

                <!-- Participants -->
                @if($participants->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Participants ({{ $participants->count() }})</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($participants as $participant)
                                <div class="flex items-center space-x-2">
                                    @if($participant->avatar)
                                        <img src="{{ $participant->avatar }}" alt="{{ $participant->name }}" class="w-8 h-8 rounded-full">
                                    @else
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600">{{ substr($participant->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <span class="text-sm text-gray-600">{{ $participant->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    @if($session->isAvailableForEnrollment())
                        @if($userEnrollment)
                            <form method="POST" action="{{ route('wellness.cancel', $session) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-6 py-3 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                    Cancel Enrollment
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('wellness.enroll', $session) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                    {{ $session->status === 'waitlist' ? 'Join Waitlist' : 'Enroll Now' }}
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="flex-1 text-center px-6 py-3 text-sm font-medium text-gray-500 bg-gray-100 rounded-md">
                            Not Available for Enrollment
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
