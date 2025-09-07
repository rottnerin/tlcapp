@extends('layouts.user')

@section('title', $scheduleItem->title . ' - AES Professional Learning Days')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('schedule.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Schedule
        </a>
    </div>

    <!-- Session Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $scheduleItem->title }}</h1>

                <!-- Divisions -->
                @if($scheduleItem->divisions->count() > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($scheduleItem->divisions as $division)
                            <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                                {{ strtolower($division->name) === 'es' ? 'bg-green-100 text-green-800' :
                                   (strtolower($division->name) === 'ms' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800') }}">
                                {{ $division->full_name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Date and Time -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-4">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
                        </svg>
                        {{ $scheduleItem->date->format('l, F j, Y') }}
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $scheduleItem->start_time->format('g:i A') }} - {{ $scheduleItem->end_time->format('g:i A') }}
                    </div>
                    @if($scheduleItem->location)
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $scheduleItem->location }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Session Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            @if($scheduleItem->description)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Session</h2>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($scheduleItem->description)) !!}
                    </div>
                </div>
            @endif

            <!-- Learning Objectives -->
            @if($scheduleItem->learning_objectives)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Learning Objectives</h2>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($scheduleItem->learning_objectives)) !!}
                    </div>
                </div>
            @endif

            <!-- Materials/Resources -->
            @if($scheduleItem->materials)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Materials & Resources</h2>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($scheduleItem->materials)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Presenter Information -->
            @if($scheduleItem->presenter_name)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Presenter</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $scheduleItem->presenter_name }}</div>
                            @if($scheduleItem->presenter_title)
                                <div class="text-sm text-gray-600">{{ $scheduleItem->presenter_title }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Session Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Session Actions</h3>
                <div class="space-y-3">
                    <!-- Add to Calendar -->
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-9 0h10m-9 0V7a1 1 0 011-1h6a1 1 0 011 1v1M9 7h6m-6 0v10a2 2 0 002 2h2a2 2 0 002-2V7"></path>
                        </svg>
                        Add to Calendar
                    </button>

                    <!-- Share Session -->
                    <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Share Session
                    </button>
                </div>
            </div>

            <!-- External Links -->
            @if($scheduleItem->hasLink())
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resources</h3>
                    <a href="{{ $scheduleItem->formatted_link_url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        {{ $scheduleItem->link_title ?: 'Learn More' }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection