@extends('layouts.app')

@section('title', 'CCL Session Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('admin.ccl.index') }}" 
                   class="hover:opacity-70" style="color: var(--tlc-navy);">
                    <i class="fas fa-arrow-left mr-2"></i>Back to CCL Sessions
                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.ccl.edit', $ccl) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Session
                    </a>
                    <form action="{{ route('admin.ccl.toggle-status', $ccl) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 rounded-lg text-white transition-colors
                                       {{ $ccl->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                            <i class="fas fa-{{ $ccl->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $ccl->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $ccl->title }}</h1>
            <div class="flex items-center mt-2 space-x-4">
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $ccl->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $ccl->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($ccl->division)
                    <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                        {{ $ccl->division->name }}
                    </span>
                @endif
                @if($ccl->pdDay)
                    <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">
                        {{ $ccl->pdDay->title }}
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
                    
                    @if($ccl->description)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                            <p class="text-gray-600 whitespace-pre-line">{{ $ccl->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Date & Time</h3>
                            <p class="text-gray-900">{{ $ccl->date->format('l, F j, Y') }}</p>
                            <p class="text-gray-600">
                                @if($ccl->start_time && $ccl->end_time)
                                    {{ $ccl->start_time->format('g:i A') }} - {{ $ccl->end_time->format('g:i A') }}
                                @else
                                    <span class="text-gray-400">Not specified</span>
                                @endif
                            </p>
                        </div>

                        @if($ccl->location)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Location</h3>
                                <p class="text-gray-900">{{ $ccl->location }}</p>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Contact Hours</h3>
                            <p class="text-gray-900">{{ $ccl->contact_hours ?: $ccl->calculateContactHours() }} hours</p>
                        </div>
                    </div>
                </div>

                <!-- Presenter Information -->
                @if($ccl->presenter_name || $ccl->presenter_bio || $ccl->presenter_email || $ccl->co_presenter_name || $ccl->co_presenter_email)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Presenter Information</h2>
                        
                        @if($ccl->presenter_name)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Presenter Information</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="mb-3">
                                        <h4 class="text-sm font-semibold text-gray-800 mb-1">Primary Presenter</h4>
                                        <p class="text-gray-900 font-medium">{{ $ccl->presenter_name }}</p>
                                        @if($ccl->presenter_email)
                                            <p class="text-gray-600 text-sm">
                                                <a href="mailto:{{ $ccl->presenter_email }}" class="hover:underline">
                                                    {{ $ccl->presenter_email }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                    
                                    @if($ccl->co_presenter_name)
                                        <div class="border-t pt-3">
                                            <h4 class="text-sm font-semibold text-gray-800 mb-1">Co-Presenter(s)</h4>
                                            <p class="text-gray-900 font-medium">{{ $ccl->co_presenter_name }}</p>
                                            @if($ccl->co_presenter_email)
                                                <p class="text-gray-600 text-sm">
                                                    <a href="mailto:{{ $ccl->co_presenter_email }}" class="hover:underline">
                                                        {{ $ccl->co_presenter_email }}
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($ccl->presenter_bio)
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-1">Bio</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $ccl->presenter_bio }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Resources -->
                @if($ccl->links->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resources</h2>
                        <div class="space-y-3">
                            @foreach($ccl->links as $link)
                                <a href="{{ $link->formatted_url }}" target="_blank" 
                                   class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center">
                                        <i class="fas fa-external-link-alt text-aes-blue mr-3"></i>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $link->title }}</p>
                                            <p class="text-sm text-gray-500">{{ ucfirst($link->type) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Enrollment Summary -->
                @if(isset($scheduleItem) && $scheduleItem)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Enrollment Summary</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Confirmed</span>
                                <span class="font-medium text-green-600">{{ $confirmedParticipants->count() }}</span>
                            </div>
                            @if($scheduleItem->max_participants !== null)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Available Spots</span>
                                    <span class="font-medium text-blue-600">
                                        {{ max(0, $scheduleItem->max_participants - $confirmedParticipants->count()) }}
                                    </span>
                                </div>
                                <hr>
                                <div class="flex justify-between font-semibold">
                                    <span class="text-gray-900">Total Capacity</span>
                                    <span class="text-gray-900">{{ $scheduleItem->max_participants }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.ccl.edit', $ccl) }}" 
                           class="block w-full text-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Session
                        </a>
                        
                        <form action="{{ route('admin.ccl.toggle-status', $ccl) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 rounded-lg text-white transition-colors
                                           {{ $ccl->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                <i class="fas fa-{{ $ccl->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $ccl->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.ccl.destroy', $ccl) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Session
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Associations -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Associations</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">PD Day</h3>
                            @if($ccl->pdDay)
                                <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">
                                    {{ $ccl->pdDay->title }}
                                </span>
                            @else
                                <p class="text-gray-500 text-sm">Not assigned</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Division</h3>
                            @if($ccl->division)
                                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                    {{ $ccl->division->full_name }}
                                </span>
                            @else
                                <p class="text-gray-500 text-sm">All Divisions</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants Lists -->
        @if(isset($confirmedParticipants) && $confirmedParticipants->count() > 0)
            <div class="mt-8">
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
                                    <form action="{{ route('admin.ccl.remove-enrollment', $ccl) }}" method="POST" 
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
            </div>
        @endif
    </div>
</div>
@endsection
