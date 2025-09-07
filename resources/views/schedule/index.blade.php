@extends('layouts.user')

@section('title', 'Schedule - AES Professional Learning Days')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Professional Learning Schedule</h1>
        <p class="text-gray-600">Explore the complete schedule for September 25-26, 2025</p>
    </div>

    <!-- Division Filter -->
    @if($divisions->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter by Division</h3>
            <form method="GET" class="flex flex-wrap gap-2">
                @foreach($divisions as $division)
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                               name="divisions[]"
                               value="{{ $division->id }}"
                               {{ in_array($division->id, $selectedDivisions) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 px-4 py-2 rounded-full text-sm font-medium
                            {{ in_array($division->id, $selectedDivisions) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $division->full_name }}
                        </span>
                    </label>
                @endforeach
                <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Apply Filters
                </button>
            </form>
        </div>
    @endif

    <!-- Schedule Display -->
    @if($scheduleItems->count() > 0)
        @foreach($scheduleItems as $date => $dayItems)
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                    </h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach($dayItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                                <!-- Time and Location -->
                                <div class="flex-shrink-0 mb-4 lg:mb-0 lg:mr-6">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->start_time->format('g:i A') }} - {{ $item->end_time->format('g:i A') }}
                                    </div>
                                    @if($item->location)
                                        <div class="text-sm text-gray-600 mt-1">
                                            📍 {{ $item->location }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Session Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                                {{ $item->title }}
                                            </h3>

                                            @if($item->description)
                                                <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                    {{ $item->description }}
                                                </p>
                                            @endif

                                            <!-- Divisions -->
                                            @if($item->divisions->count() > 0)
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    @foreach($item->divisions as $division)
                                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                                            {{ strtolower($division->name) === 'es' ? 'bg-green-100 text-green-800' :
                                                               (strtolower($division->name) === 'ms' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                                            {{ $division->full_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Presenter -->
                                            @if($item->presenter_name)
                                                <div class="text-sm text-gray-600 mb-2">
                                                    👤 {{ $item->presenter_name }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="mt-4 sm:mt-0 sm:ml-4 flex-shrink-0">
                                            <a href="{{ route('schedule.show', $item) }}"
                                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                                View Details
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Links -->
                                    @if($item->hasLink())
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <a href="{{ $item->formatted_link_url }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                {{ $item->link_title ?: 'Learn More' }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No schedule items found</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($selectedDivisions)
                    No sessions found for the selected divisions. Try selecting different divisions.
                @else
                    The schedule hasn't been published yet. Check back later.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection