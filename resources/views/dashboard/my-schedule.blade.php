@extends('layouts.user')

@section('title', 'My Schedule - AES Professional Learning Days')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Schedule</h1>
        <p class="text-gray-600">Your personalized schedule for Professional Learning Days</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $enrolledSessions->count() }}</div>
            <div class="text-sm text-gray-600">Enrolled Sessions</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-2xl font-bold text-green-600">{{ $scheduleItems->count() }}</div>
            <div class="text-sm text-gray-600">Available Sessions</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-2xl font-bold text-orange-600">{{ $user->division ? $user->division->name : 'All' }}</div>
            <div class="text-sm text-gray-600">Your Division</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Enrolled Sessions -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">My Enrolled Sessions</h2>

                @if($enrolledSessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($enrolledSessions as $enrollment)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        @if($enrollment->wellnessSession)
                                            <h3 class="font-medium text-gray-900">{{ $enrollment->wellnessSession->title }}</h3>
                                            <p class="text-sm text-gray-600">Wellness Session</p>
                                        @elseif($enrollment->scheduleItem)
                                            <h3 class="font-medium text-gray-900">{{ $enrollment->scheduleItem->title }}</h3>
                                            <p class="text-sm text-gray-600">Schedule Session</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $enrollment->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                           'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
                                    </svg>
                                    @if($enrollment->wellnessSession)
                                        {{ $enrollment->wellnessSession->date->format('M j, Y') }}
                                    @elseif($enrollment->scheduleItem)
                                        {{ $enrollment->scheduleItem->date->format('M j, Y') }}
                                    @endif
                                </div>

                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @if($enrollment->wellnessSession)
                                        {{ $enrollment->wellnessSession->start_time->format('g:i A') }} - {{ $enrollment->wellnessSession->end_time->format('g:i A') }}
                                    @elseif($enrollment->scheduleItem)
                                        {{ $enrollment->scheduleItem->start_time->format('g:i A') }} - {{ $enrollment->scheduleItem->end_time->format('g:i A') }}
                                    @endif
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-600">
                                        @if($enrollment->wellnessSession && $enrollment->wellnessSession->location)
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $enrollment->wellnessSession->location }}
                                        @elseif($enrollment->scheduleItem && $enrollment->scheduleItem->location)
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $enrollment->scheduleItem->location }}
                                        @endif
                                    </div>

                                    @if($enrollment->wellnessSession)
                                        <a href="{{ route('wellness.show', $enrollment->wellnessSession) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details →
                                        </a>
                                    @elseif($enrollment->scheduleItem)
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No enrolled sessions yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Browse available sessions and enroll in the ones that interest you.</p>
                        <div class="mt-4 space-x-4">
                            <a href="{{ route('schedule.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">View Schedule</a>
                            <a href="{{ route('wellness.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Browse Wellness</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Division Schedule -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    {{ $user->division ? $user->division->full_name : 'All Divisions' }} Schedule
                </h2>

                @if($scheduleItems->count() > 0)
                    <div class="space-y-4">
                        @php
                            $groupedItems = $scheduleItems->groupBy(function($item) {
                                return $item->date->format('Y-m-d');
                            });
                        @endphp

                        @foreach($groupedItems as $date => $items)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                </h3>
                                <div class="space-y-2">
                                    @foreach($items as $item)
                                        <div class="bg-gray-50 rounded p-3">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-sm text-gray-900">{{ $item->title }}</h4>
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        {{ $item->start_time->format('g:i A') }} - {{ $item->end_time->format('g:i A') }}
                                                        @if($item->location)
                                                            • {{ $item->location }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No schedule available</h3>
                        <p class="mt-1 text-sm text-gray-500">The schedule for your division hasn't been published yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection