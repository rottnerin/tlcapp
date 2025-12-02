@extends('layouts.app')

@section('title', 'Wellness Session Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('admin.wellness.index') }}" 
                   class="text-aes-blue hover:text-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Wellness Sessions
                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.wellness.edit', $wellness) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Session
                    </a>
                    <form action="{{ route('admin.wellness.toggle-status', $wellness) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 rounded-lg text-white transition-colors
                                       {{ $wellness->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                            <i class="fas fa-{{ $wellness->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $wellness->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $wellness->title }}</h1>
            <div class="flex items-center mt-2 space-x-4">
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $wellness->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $wellness->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($wellness->category)
                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                        {{ ucfirst($wellness->category) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Session Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Session Details</h2>
                    
                    @if($wellness->description)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $wellness->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Date & Time</h3>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($wellness->date)->format('l, F j, Y') }}</p>
                            <p class="text-gray-600">
                                {{ \Carbon\Carbon::parse($wellness->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($wellness->end_time)->format('g:i A') }}
                            </p>
                        </div>

                        @if($wellness->location)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Location</h3>
                                <p class="text-gray-900">{{ $wellness->location }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Capacity</h3>
                            <div class="flex items-center">
                                <span class="text-gray-900 mr-2">{{ $confirmedParticipants->count() }}/{{ $wellness->max_participants }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-aes-blue h-2 rounded-full" 
                                         style="width: {{ $wellness->max_participants > 0 ? ($confirmedParticipants->count() / $wellness->max_participants) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Waitlist</h3>
                            <p class="text-gray-900">
                                @if($wellness->allow_waitlist)
                                    <span class="text-green-600">Enabled</span>
                                    @if($waitlistedParticipants->count() > 0)
                                        ({{ $waitlistedParticipants->count() }} waiting)
                                    @endif
                                @else
                                    <span class="text-gray-600">Disabled</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Presenter Information -->
                @if($wellness->presenter_name || $wellness->presenter_bio || $wellness->presenter_email)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Presenter Information</h2>
                        
                        @if($wellness->presenter_name)
                            <div class="mb-3">
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Presenter</h3>
                                <p class="text-gray-900">{{ $wellness->presenter_name }}</p>
                                @if($wellness->presenter_email)
                                    <p class="text-gray-600">
                                        <a href="mailto:{{ $wellness->presenter_email }}" class="hover:underline">
                                            {{ $wellness->presenter_email }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if($wellness->presenter_bio)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Bio</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $wellness->presenter_bio }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Additional Information -->
                @if($wellness->equipment_needed || $wellness->special_requirements || $wellness->preparation_notes)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Information</h2>
                        
                        @if($wellness->equipment_needed)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Equipment Needed</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $wellness->equipment_needed }}</p>
                            </div>
                        @endif

                        @if($wellness->special_requirements)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Special Requirements</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $wellness->special_requirements }}</p>
                            </div>
                        @endif

                        @if($wellness->preparation_notes)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Preparation Notes</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $wellness->preparation_notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Enrollment Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Enrollment Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Confirmed</span>
                            <span class="font-medium text-green-600">{{ $confirmedParticipants->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Waitlisted</span>
                            <span class="font-medium text-yellow-600">{{ $waitlistedParticipants->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Available Spots</span>
                            <span class="font-medium text-blue-600">
                                {{ max(0, $wellness->max_participants - $confirmedParticipants->count()) }}
                            </span>
                        </div>
                        <hr>
                        <div class="flex justify-between font-semibold">
                            <span class="text-gray-900">Total Capacity</span>
                            <span class="text-gray-900">{{ $wellness->max_participants }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.wellness.edit', $wellness) }}" 
                           class="block w-full text-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Session
                        </a>
                        
                        <form action="{{ route('admin.wellness.toggle-status', $wellness) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 rounded-lg text-white transition-colors
                                           {{ $wellness->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                <i class="fas fa-{{ $wellness->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $wellness->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        @if($confirmedParticipants->count() == 0 && $waitlistedParticipants->count() == 0)
                            <form action="{{ route('admin.wellness.destroy', $wellness) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Delete Session
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants Lists -->
        @if($confirmedParticipants->count() > 0 || $waitlistedParticipants->count() > 0)
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Confirmed Participants -->
                @if($confirmedParticipants->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            Confirmed Participants ({{ $confirmedParticipants->count() }})
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach($confirmedParticipants as $enrollment)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $enrollment->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $enrollment->user->email }}</p>
                                        @if($enrollment->user->division)
                                            <p class="text-xs text-gray-500">{{ $enrollment->user->division->name }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">
                                            {{ $enrollment->enrolled_at->format('M j, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $enrollment->enrolled_at->format('g:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Waitlisted Participants -->
                @if($waitlistedParticipants->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            Waitlisted Participants ({{ $waitlistedParticipants->count() }})
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach($waitlistedParticipants as $enrollment)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $enrollment->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $enrollment->user->email }}</p>
                                        @if($enrollment->user->division)
                                            <p class="text-xs text-gray-500">{{ $enrollment->user->division->name }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-yellow-600 font-medium">Waitlisted</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $enrollment->enrolled_at->format('M j, g:i A') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
