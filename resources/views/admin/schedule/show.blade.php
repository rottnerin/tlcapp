@extends('layouts.app')

@section('title', 'Schedule Item Details')

@section('content')
<div class="min-h-screen bg-content py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('admin.schedule.index') }}" 
                   class="text-aes-blue hover:text-blue-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Schedule Items
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.schedule.edit', $schedule) }}" 
                       class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    @if($schedule->user_sessions_count == 0)
                        <form action="{{ route('admin.schedule.destroy', $schedule) }}" method="POST" 
                              class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $schedule->title }}</h1>
            <p class="text-gray-600 mt-1">Schedule Item Details</p>
        </div>

        <!-- Status Banner -->
        @if(!$schedule->is_active)
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    This schedule item is currently inactive
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Primary Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Details -->
                <div class="bg-white rounded-lg shadow-card border p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Title</label>
                            <p class="text-gray-900">{{ $schedule->title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Session Type</label>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($schedule->session_type) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Location</label>
                            <p class="text-gray-900">
                                @if($schedule->location)
                                    <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>{{ $schedule->location }}
                                @else
                                    <span class="text-gray-500">Not specified</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                                        {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($schedule->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $schedule->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Date and Time -->
                <div class="bg-white rounded-lg shadow-card border p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Schedule</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date</label>
                            <p class="text-gray-900">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($schedule->date)->format('l, F j, Y') }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Start Time</label>
                            <p class="text-gray-900">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">End Time</label>
                            <p class="text-gray-900">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            Duration: {{ \Carbon\Carbon::parse($schedule->start_time)->diffForHumans(\Carbon\Carbon::parse($schedule->end_time), true) }}
                        </p>
                    </div>
                </div>

                <!-- Presenters -->
                @if($schedule->presenter_primary || $schedule->presenter_secondary || $schedule->presenter_bio)
                    <div class="bg-white rounded-lg shadow-card border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Presenters</h2>
                        <div class="space-y-4">
                            @if($schedule->presenter_primary)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Primary Presenter</label>
                                    <p class="text-gray-900">{{ $schedule->presenter_primary }}</p>
                                </div>
                            @endif
                            
                            @if($schedule->presenter_secondary)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Secondary Presenter</label>
                                    <p class="text-gray-900">{{ $schedule->presenter_secondary }}</p>
                                </div>
                            @endif
                            
                            @if($schedule->presenter_bio)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Presenter Bio</label>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $schedule->presenter_bio }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Additional Information -->
                @if($schedule->equipment_needed || $schedule->special_requirements)
                    <div class="bg-white rounded-lg shadow-card border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Information</h2>
                        <div class="space-y-4">
                            @if($schedule->equipment_needed)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Equipment Needed</label>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $schedule->equipment_needed }}</p>
                                </div>
                            @endif
                            
                            @if($schedule->special_requirements)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Special Requirements</label>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $schedule->special_requirements }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Divisions -->
                <div class="bg-white rounded-lg shadow-card border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Target Divisions</h3>
                    @if($schedule->divisions->count() > 0)
                        <div class="space-y-2">
                            @foreach($schedule->divisions as $division)
                                <div class="flex items-center">
                                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $division->name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">All divisions</p>
                    @endif
                </div>

                <!-- Capacity Information -->
                @if($schedule->max_participants)
                    <div class="bg-white rounded-lg shadow-card border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Capacity</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Max Participants:</span>
                                <span class="font-medium text-gray-900">{{ $schedule->max_participants }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Current Enrollment:</span>
                                <span class="font-medium text-gray-900">{{ $schedule->current_enrollment ?? 0 }}</span>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-aes-blue h-2 rounded-full transition-all" 
                                     style="width: {{ $schedule->max_participants > 0 ? (($schedule->current_enrollment ?? 0) / $schedule->max_participants) * 100 : 0 }}%"></div>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $schedule->max_participants - ($schedule->current_enrollment ?? 0) }} spots remaining
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="bg-white rounded-lg shadow-card border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Timestamps</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <p class="text-gray-900">{{ $schedule->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <p class="text-gray-900">{{ $schedule->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
