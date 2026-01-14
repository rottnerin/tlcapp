@extends('layouts.user')

@section('title', 'My Schedule - TLC Professional Learning')

@push('styles')
<style>
    :root {
        --tlc-navy: #0d3b66;
        --tlc-cream: #faf0ca;
        --tlc-gold: #f4d35e;
        --tlc-orange: #ee964b;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2" style="color: var(--tlc-navy);">My Schedule</h1>
        <p style="color: #4a5568;">Your personalized schedule for Professional Learning</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6" style="border-top: 3px solid var(--tlc-navy);">
            <div class="text-2xl font-bold" style="color: var(--tlc-navy);">{{ $enrolledSessions->count() }}</div>
            <div class="text-sm" style="color: #4a5568;">Enrolled Sessions</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6" style="border-top: 3px solid var(--tlc-gold);">
            <div class="text-2xl font-bold" style="color: var(--tlc-gold);">{{ $scheduleItems->count() }}</div>
            <div class="text-sm" style="color: #4a5568;">Available Sessions</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6" style="border-top: 3px solid var(--tlc-orange);">
            <div class="text-2xl font-bold" style="color: var(--tlc-orange);">{{ $user->division ? $user->division->name : 'All' }}</div>
            <div class="text-sm" style="color: #4a5568;">Your Division</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Enrolled Sessions -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4" style="color: var(--tlc-navy);">My Enrolled Sessions</h2>

                @if($enrolledSessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($enrolledSessions as $enrollment)
                            <div class="border rounded-lg p-4 transition-colors" style="border-color: rgba(13, 59, 102, 0.2); background-color: rgba(250, 240, 202, 0.2);">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        @if($enrollment->wellnessSession)
                                            <h3 class="font-medium" style="color: var(--tlc-navy);">{{ $enrollment->wellnessSession->title }}</h3>
                                            <p class="text-sm" style="color: #4a5568;">Wellness Session</p>
                                        @elseif($enrollment->scheduleItem)
                                            <h3 class="font-medium" style="color: var(--tlc-navy);">{{ $enrollment->scheduleItem->title }}</h3>
                                            <p class="text-sm" style="color: #4a5568;">Schedule Session</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        style="{{ $enrollment->status === 'confirmed' ? 'background-color: rgba(244, 211, 94, 0.3); color: var(--tlc-navy);' : 'background-color: rgba(13, 59, 102, 0.1); color: var(--tlc-navy);' }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center text-sm mb-2" style="color: #4a5568;">
                                    <svg class="w-4 h-4 mr-2" style="color: var(--tlc-orange);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
                                    </svg>
                                    @if($enrollment->wellnessSession)
                                        {{ $enrollment->wellnessSession->date->format('M j, Y') }}
                                    @elseif($enrollment->scheduleItem)
                                        {{ $enrollment->scheduleItem->date->format('M j, Y') }}
                                    @endif
                                </div>

                                <div class="flex items-center text-sm mb-3" style="color: #4a5568;">
                                    <svg class="w-4 h-4 mr-2" style="color: var(--tlc-orange);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @if($enrollment->wellnessSession)
                                        {{ $enrollment->wellnessSession->start_time->format('g:i A') }} - {{ $enrollment->wellnessSession->end_time->format('g:i A') }}
                                    @elseif($enrollment->scheduleItem)
                                        {{ $enrollment->scheduleItem->start_time->format('g:i A') }} - {{ $enrollment->scheduleItem->end_time->format('g:i A') }}
                                    @endif
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm" style="color: #4a5568;">
                                        @if($enrollment->wellnessSession && $enrollment->wellnessSession->location)
                                            <svg class="w-4 h-4 mr-2" style="color: var(--tlc-orange);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $enrollment->wellnessSession->location }}
                                        @elseif($enrollment->scheduleItem && $enrollment->scheduleItem->location)
                                            <svg class="w-4 h-4 mr-2" style="color: var(--tlc-orange);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $enrollment->scheduleItem->location }}
                                        @endif
                                    </div>

                                    @if($enrollment->wellnessSession)
                                        <a href="{{ route('wellness.show', $enrollment->wellnessSession) }}"
                                           class="text-sm font-medium" style="color: var(--tlc-orange);">
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
                        <svg class="mx-auto h-12 w-12" style="color: rgba(13, 59, 102, 0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium" style="color: var(--tlc-navy);">No enrolled sessions yet</h3>
                        <p class="mt-1 text-sm" style="color: #718096;">Browse available sessions and enroll in the ones that interest you.</p>
                        <div class="mt-4 space-x-4">
                            <a href="{{ route('schedule.index') }}" class="font-medium" style="color: var(--tlc-orange);">View Schedule</a>
                            <a href="{{ route('wellness.index') }}" class="font-medium" style="color: var(--tlc-orange);">Browse Wellness</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Division Schedule -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4" style="color: var(--tlc-navy);">
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
                            <div class="border-l-4 pl-4" style="border-color: var(--tlc-gold);">
                                <h3 class="font-medium mb-2" style="color: var(--tlc-navy);">
                                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                </h3>
                                <div class="space-y-2">
                                    @foreach($items as $item)
                                        <div class="rounded p-3" style="background-color: rgba(250, 240, 202, 0.3);">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-sm" style="color: var(--tlc-navy);">{{ $item->title }}</h4>
                                                    <p class="text-xs mt-1" style="color: #4a5568;">
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
                        <svg class="mx-auto h-12 w-12" style="color: rgba(13, 59, 102, 0.3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium" style="color: var(--tlc-navy);">No schedule available</h3>
                        <p class="mt-1 text-sm" style="color: #718096;">The schedule for your division hasn't been published yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection