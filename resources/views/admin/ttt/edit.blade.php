@extends('layouts.app')

@section('title', 'Edit TTT Session')

@section('content')
<div class="min-h-screen bg-content py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.ttt.index') }}" 
                   class="mr-4 font-medium hover:opacity-70" style="color: var(--tlc-navy);">
                    <i class="fas fa-arrow-left mr-2"></i>Back to TTT Sessions
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Edit TTT Session</h1>
            <p class="text-gray-600 mt-1">Update the TTT session details</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-card border">
            <form action="{{ route('admin.ttt.update', $ttt) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Session Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $ttt->title) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent
                                      @error('title') border-red-300 ring-2 ring-red-200 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent
                                         @error('description') border-red-300 ring-2 ring-red-200 @enderror">{{ old('description', $ttt->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $ttt->location) }}"
                               placeholder="e.g., Room 101, Library"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('location') border-red-300 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Presenter Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Presenter Information</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Presenter Name <span class="text-red-500">*</span></label>
                            <input type="text" id="presenter_name" name="presenter_name" value="{{ old('presenter_name', $ttt->presenter_name) }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('presenter_name') border-red-300 @enderror">
                            @error('presenter_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Presenter Email</label>
                            <input type="email" id="presenter_email" name="presenter_email" value="{{ old('presenter_email', $ttt->presenter_email) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('presenter_email') border-red-300 @enderror">
                            @error('presenter_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label for="presenter_bio" class="block text-sm font-medium text-gray-700 mb-1">Presenter Bio</label>
                            <textarea id="presenter_bio" name="presenter_bio" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('presenter_bio') border-red-300 @enderror">{{ old('presenter_bio', $ttt->presenter_bio) }}</textarea>
                            @error('presenter_bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="co_presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Name(s)</label>
                            <input type="text" id="co_presenter_name" name="co_presenter_name" value="{{ old('co_presenter_name', $ttt->co_presenter_name) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('co_presenter_name') border-red-300 @enderror">
                            @error('co_presenter_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="co_presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Email(s)</label>
                            <input type="email" id="co_presenter_email" name="co_presenter_email" value="{{ old('co_presenter_email', $ttt->co_presenter_email) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('co_presenter_email') border-red-300 @enderror">
                            @error('co_presenter_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule & Capacity -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule & Capacity</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="p_d_day_id" class="block text-sm font-medium text-gray-700 mb-1">
                                PL Day Event
                            </label>
                            <select id="p_d_day_id" name="p_d_day_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                           @error('p_d_day_id') border-red-300 @enderror">
                                <option value="">Not assigned to any PL Day</option>
                                @foreach($pdDays as $pdDay)
                                    <option value="{{ $pdDay->id }}" {{ old('p_d_day_id', $ttt->p_d_day_id) == $pdDay->id ? 'selected' : '' }}>
                                        {{ $pdDay->title }} ({{ $pdDay->date_range }})
                                    </option>
                                @endforeach
                            </select>
                            @error('p_d_day_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date" name="date" value="{{ old('date', $ttt->date->format('Y-m-d')) }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('date') border-red-300 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Start Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $ttt->getRawOriginal('start_time')) }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('start_time') border-red-300 @enderror">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                End Time <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $ttt->getRawOriginal('end_time')) }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('end_time') border-red-300 @enderror">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                            <select id="division_id" name="division_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                           @error('division_id') border-red-300 @enderror">
                                <option value="">All Divisions</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id', $ttt->division_id) == $division->id ? 'selected' : '' }}>
                                        {{ $division->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_hours" class="block text-sm font-medium text-gray-700 mb-1">Contact Hours</label>
                            <input type="number" id="contact_hours" name="contact_hours" 
                                   value="{{ old('contact_hours', $ttt->contact_hours) }}" step="0.5" min="0" max="24"
                                   placeholder="Auto-calculated if empty"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('contact_hours') border-red-300 @enderror">
                            @error('contact_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $ttt->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">
                                Session is active and available for enrollment
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="border-t pt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.ttt.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 text-white rounded-lg transition-colors font-medium shadow-md" style="background-color: var(--tlc-orange);" onmouseover="this.style.backgroundColor='#0d3b66'" onmouseout="this.style.backgroundColor='#ee964b'">
                        <i class="fas fa-save mr-2"></i>Update Session
                    </button>
                </div>
            </form>
        </div>

        <!-- Participants List -->
        @if(isset($confirmedParticipants) && $confirmedParticipants->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow-card border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Enrolled Participants ({{ $confirmedParticipants->count() }})</h2>
                </div>
                
                <div class="p-6">
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
                                    <form action="{{ route('admin.ttt.remove-enrollment', $ttt) }}" method="POST" 
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
