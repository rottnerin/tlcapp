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
                @if($wellness->category && is_array($wellness->category) && count($wellness->category) > 0)
                    @foreach($wellness->category as $category)
                        <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                            {{ $category }}
                        </span>
                    @endforeach
                @elseif($wellness->category)
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
                                2:30 PM - 3:30 PM
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

                    </div>
                </div>

                <!-- Presenter Information -->
                @if($wellness->presenter_name || $wellness->presenter_bio || $wellness->presenter_email || $wellness->co_presenter_name || $wellness->co_presenter_email)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Presenter Information</h2>
                        
                        @if($wellness->presenter_name)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Presenter Information</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="mb-3">
                                        <h4 class="text-sm font-semibold text-gray-800 mb-1">Primary Presenter</h4>
                                        <p class="text-gray-900 font-medium">{{ $wellness->presenter_name }}</p>
                                        @if($wellness->presenter_email)
                                            <p class="text-gray-600 text-sm">
                                                <a href="mailto:{{ $wellness->presenter_email }}" class="hover:underline">
                                                    {{ $wellness->presenter_email }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    
                                    @if($wellness->co_presenter_name)
                                        <div class="border-t pt-3">
                                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Co-Presenter(s)</h4>
                                            <p class="text-gray-900 font-medium">{{ $wellness->co_presenter_name }}</p>
                                            @if($wellness->co_presenter_email)
                                                <p class="text-gray-600 text-sm">
                                                    <a href="mailto:{{ $wellness->co_presenter_email }}" class="hover:underline">
                                                        {{ $wellness->co_presenter_email }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
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
                        
                        @if($confirmedParticipants->count() > 0)
                            <a href="{{ route('admin.wellness.transfer', $wellness) }}" 
                               class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-exchange-alt mr-2"></i>Transfer Users
                            </a>
                        @endif
                        
                        <form action="{{ route('admin.wellness.toggle-status', $wellness) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 rounded-lg text-white transition-colors
                                           {{ $wellness->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                <i class="fas fa-{{ $wellness->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $wellness->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        @if($confirmedParticipants->count() == 0)
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
        @if($confirmedParticipants->count() > 0)
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Confirmed Participants -->
                @if($confirmedParticipants->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            Confirmed Participants ({{ $confirmedParticipants->count() }})
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach($confirmedParticipants as $enrollment)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $enrollment->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $enrollment->user->email }}</p>
                                        @if($enrollment->user->division)
                                            <p class="text-xs text-gray-500">{{ $enrollment->user->division->name }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">
                                                {{ $enrollment->enrolled_at->format('M j, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $enrollment->enrolled_at->format('g:i A') }}
                                            </p>
                                        </div>
                                        <form action="{{ route('admin.wellness.remove-enrollment', $wellness) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to remove {{ $enrollment->user->name }} from this session?')">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $enrollment->user->id }}">
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 p-1 rounded transition-colors"
                                                    title="Remove from session">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
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
