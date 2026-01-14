<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - TLC Professional Learning</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --tlc-navy: #0d3b66;
            --tlc-cream: #faf0ca;
            --tlc-gold: #f4d35e;
            --tlc-orange: #ee964b;
        }
        body { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; }
        .division-es { border-left: 4px solid var(--tlc-gold); }
        .division-ms { border-left: 4px solid var(--tlc-orange); }
        .division-hs { border-left: 4px solid var(--tlc-navy); }
        .tlc-bg { background-color: var(--tlc-cream); }
    </style>
</head>
<body class="antialiased tlc-bg">
    <!-- Navigation -->
    <nav class="shadow-lg border-b" style="background-color: var(--tlc-navy); border-color: rgba(244, 211, 94, 0.3);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold" style="color: var(--tlc-cream);">TLC Professional Learning</h1>
                    @if($user->division)
                        <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                            {{ $user->division->full_name }}
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        <a href="{{ route('dashboard') }}" class="font-medium" style="color: var(--tlc-gold);">Dashboard</a>
                        <a href="{{ route('schedule.index') }}" style="color: var(--tlc-cream);" onmouseover="this.style.color='#f4d35e'" onmouseout="this.style.color='#faf0ca'">Schedule</a>
                        <a href="{{ route('wellness.index') }}" style="color: var(--tlc-cream);" onmouseover="this.style.color='#f4d35e'" onmouseout="this.style.color='#faf0ca'">Wellness</a>
                        <a href="{{ route('my-schedule') }}" style="color: var(--tlc-cream);" onmouseover="this.style.color='#f4d35e'" onmouseout="this.style.color='#faf0ca'">My Schedule</a>
                    </nav>
                    
                    <div class="flex items-center space-x-2">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full ring-2" style="--tw-ring-color: rgba(244, 211, 94, 0.5);">
                        @endif
                        <span class="text-sm" style="color: var(--tlc-cream);">{{ $user->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm" style="color: var(--tlc-orange);" onmouseover="this.style.color='#f4d35e'" onmouseout="this.style.color='#ee964b'">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6" style="border-left: 4px solid var(--tlc-gold);">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold mb-2" style="color: var(--tlc-navy);">
                        Welcome back, {{ $user->name }}! 👋
                    </h2>
                    @if($activePDDay)
                        <p style="color: #4a5568;">Ready for an amazing Professional Learning experience on {{ $activePDDay->date_range }}?</p>
                    @else
                        <p style="color: #4a5568;">No active Professional Learning event at the moment.</p>
                    @endif
                </div>
            </div>
            
            @if (session('success'))
                <div class="mt-4 p-4 rounded-lg" style="background-color: rgba(244, 211, 94, 0.2); border: 1px solid var(--tlc-gold); color: var(--tlc-navy);">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4" style="border-top: 3px solid var(--tlc-navy);">
                <div class="text-2xl font-bold" style="color: var(--tlc-navy);">{{ $userEnrollments->count() }}</div>
                <div class="text-sm" style="color: #4a5568;">Enrolled Sessions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4" style="border-top: 3px solid var(--tlc-gold);">
                <div class="text-2xl font-bold" style="color: var(--tlc-gold);">{{ $upcomingWellness->count() }}</div>
                <div class="text-sm" style="color: #4a5568;">Available Wellness</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4" style="border-top: 3px solid var(--tlc-orange);">
                <div class="text-2xl font-bold" style="color: var(--tlc-orange);">2</div>
                <div class="text-sm" style="color: #4a5568;">Event Days</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4" style="border-top: 3px solid var(--tlc-navy);">
                <div class="text-2xl font-bold" style="color: var(--tlc-navy);">{{ $user->division ? $user->division->name : 'All' }}</div>
                <div class="text-sm" style="color: #4a5568;">Your Division</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Schedule Overview -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold" style="color: var(--tlc-navy);">Your Schedule Overview</h3>
                        <a href="{{ route('schedule.index') }}" class="text-sm" style="color: var(--tlc-orange);" onmouseover="this.style.color='#0d3b66'" onmouseout="this.style.color='#ee964b'">View Full Schedule →</a>
                    </div>
                    
                    @if($scheduleItems->count() > 0)
                        @foreach($scheduleItems as $date => $dayItems)
                            <div class="mb-6">
                                <h4 class="font-medium mb-2" style="color: var(--tlc-navy);">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h4>
                                <div class="space-y-2">
                                    @foreach($dayItems->take(3) as $item)
                                        <div class="border-l-4 pl-4 py-2" style="{{ $user->division_id && $item->divisions->contains($user->division_id) ? 'border-color: var(--tlc-gold); background-color: rgba(244, 211, 94, 0.1);' : 'border-color: #e5e7eb; background-color: #f9fafb;' }}">
                                            <div class="font-medium text-sm" style="color: var(--tlc-navy);">{{ $item->title }}</div>
                                            <div class="text-xs" style="color: #4a5568;">
                                                {{ $item->start_time->format('g:i A') }} - {{ $item->end_time->format('g:i A') }}
                                                @if($item->location)
                                                    • {{ $item->location }}
                                                @endif
                                            </div>
                                            @if($item->hasLink())
                                                <div class="mt-1">
                                                    <a href="{{ $item->formatted_link_url }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="inline-flex items-center text-xs hover:underline"
                                                       style="color: var(--tlc-orange);">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                        {{ $item->link_title }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($dayItems->count() > 3)
                                        <div class="text-sm pl-4" style="color: #718096;">
                                            + {{ $dayItems->count() - 3 }} more sessions
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center py-8" style="color: #718096;">No schedule items available yet.</p>
                    @endif
                </div>
            </div>

            <!-- Wellness Sessions -->
            <div class="space-y-6">
                <!-- Enrolled Sessions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--tlc-navy);">Your Wellness Sessions</h3>
                    @if($userEnrollments->count() > 0)
                        <div class="space-y-3">
                            @foreach($userEnrollments->take(3) as $enrollment)
                                @if($enrollment->wellnessSession)
                                    <div class="border rounded p-3" style="border-color: rgba(13, 59, 102, 0.2);">
                                        <div class="font-medium text-sm" style="color: var(--tlc-navy);">{{ $enrollment->wellnessSession->title }}</div>
                                        <div class="text-xs" style="color: #4a5568;">
                                            {{ $enrollment->wellnessSession->start_time->format('M j, g:i A') }}
                                        </div>
                                        <span class="inline-block mt-1 px-2 py-1 text-xs rounded" style="{{ $enrollment->status === 'confirmed' ? 'background-color: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);' : 'background-color: rgba(238, 150, 75, 0.2); color: var(--tlc-navy);' }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <a href="{{ route('my-schedule') }}" class="block mt-4 text-center text-sm" style="color: var(--tlc-orange);">View All →</a>
                    @else
                        <p class="text-sm" style="color: #718096;">No wellness sessions enrolled yet.</p>
                        <a href="{{ route('wellness.index') }}" class="block mt-2 text-center text-white px-4 py-2 rounded text-sm transition-colors" style="background-color: var(--tlc-orange);" onmouseover="this.style.backgroundColor='#0d3b66'" onmouseout="this.style.backgroundColor='#ee964b'">
                            Browse Wellness Sessions
                        </a>
                    @endif
                </div>

                <!-- Available Wellness -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--tlc-navy);">Available Wellness Sessions</h3>
                    @if($upcomingWellness->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingWellness->take(3) as $session)
                                <div class="border rounded p-3 transition-colors {{ $session->status === 'full' ? 'opacity-60' : '' }}" style="border-color: rgba(13, 59, 102, 0.2);">
                                    <div class="flex justify-between items-start">
                                        <div class="font-medium text-sm" style="color: var(--tlc-navy);">{{ $session->title }}</div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full"
                                           style="{{ $session->status === 'available' ? 'background-color: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);' : 'background-color: rgba(239, 68, 68, 0.1); color: #dc2626;' }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </div>
                                    <div class="text-xs" style="color: #4a5568;">
                                        {{ $session->start_time->format('M j, g:i A') }}
                                        @if($session->location)
                                            • {{ $session->location }}
                                        @endif
                                    </div>
                                    <div class="text-xs mt-1"
                                        style="{{ $session->status === 'available' ? 'color: var(--tlc-navy);' : 'color: #dc2626;' }}">
                                        @if($session->status === 'available')
                                            {{ $session->available_spots }} spots available
                                        @else
                                            Full
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('wellness.index') }}" class="block mt-4 text-center text-sm" style="color: var(--tlc-orange);">View All →</a>
                    @else
                        <p class="text-sm" style="color: #718096;">No wellness sessions available yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Division Filter -->
        @if($divisions->count() > 0)
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--tlc-navy);">Filter by Division</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($divisions as $division)
                        <a href="{{ route('dashboard', ['divisions' => [$division->id]]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium transition-colors"
                           style="{{ in_array($division->id, $selectedDivisions) ? 'background-color: var(--tlc-navy); color: var(--tlc-cream);' : 'background-color: rgba(13, 59, 102, 0.1); color: var(--tlc-navy);' }}">
                            {{ $division->full_name }}
                        </a>
                    @endforeach
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 rounded-full text-sm font-medium transition-colors"
                       style="{{ empty($selectedDivisions) ? 'background-color: var(--tlc-navy); color: var(--tlc-cream);' : 'background-color: rgba(13, 59, 102, 0.1); color: var(--tlc-navy);' }}">
                        All Divisions
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
