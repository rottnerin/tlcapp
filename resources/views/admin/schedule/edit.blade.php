@extends('layouts.app')

@section('title', 'Edit Schedule Item')

@section('content')
<div class="min-h-screen bg-content dark:bg-gray-900 py-8 transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.schedule.index') }}" 
                   class="text-aes-blue dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-4 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Schedule Items
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Schedule Item</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Update the schedule item information</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-card border dark:border-gray-700">
            <form action="{{ route('admin.schedule.update', $schedule) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $schedule->title) }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('title') border-red-300 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('description') border-red-300 @enderror">{{ old('description', $schedule->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="session_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Session Type <span class="text-red-500">*</span>
                        </label>
                        <select id="session_type" name="session_type" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('session_type') border-red-300 @enderror">
                            <option value="">Select Type</option>
                            <option value="fixed" {{ old('session_type', $schedule->session_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="wellness" {{ old('session_type', $schedule->session_type) == 'wellness' ? 'selected' : '' }}>Wellness</option>
                            <option value="keynote" {{ old('session_type', $schedule->session_type) == 'keynote' ? 'selected' : '' }}>Keynote</option>
                            <option value="break" {{ old('session_type', $schedule->session_type) == 'break' ? 'selected' : '' }}>Break</option>
                            <option value="lunch" {{ old('session_type', $schedule->session_type) == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        </select>
                        @error('session_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wellness Session Selector (shown only for wellness slots) -->
                    <div id="wellness_session_div" class="lg:col-span-2 {{ $schedule->session_type === 'wellness' ? '' : 'hidden' }}">
                        <label for="wellness_session_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Link to Wellness Session <span class="text-orange-500">*</span>
                        </label>
                        <select id="wellness_session_id" name="wellness_session_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('wellness_session_id') border-red-300 @enderror"
                                {{ $schedule->session_type === 'wellness' ? 'required' : '' }}>
                            <option value="">Select a wellness session...</option>
                            @foreach($wellnessSessions as $session)
                                <option value="{{ $session->id }}" {{ old('wellness_session_id', $schedule->wellness_session_id) == $session->id ? 'selected' : '' }}>
                                    {{ $session->title }} - {{ $session->presenter_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select the wellness session this time slot belongs to.</p>
                        @error('wellness_session_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $schedule->location) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('location') border-red-300 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-gray-400 dark:text-gray-500"></i>Date <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="date" name="date" value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required
                               placeholder="Click to select date"
                               class="date-picker w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue cursor-pointer
                                      @error('date') border-red-300 dark:border-red-600 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-clock mr-1 text-gray-400 dark:text-gray-500"></i>Start Time <span class="text-red-500">*</span>
                        </label>
                        <div class="time-picker-group @error('start_time') border-red-300 dark:border-red-600 @enderror">
                            <input type="hidden" name="start_time" id="start_time_hidden" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required>
                            <select class="time-hour border border-gray-300 dark:border-gray-600 rounded-md px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                @for($h = 1; $h <= 12; $h++)
                                    <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">{{ $h }}</option>
                                @endfor
                            </select>
                            <select class="time-minute border border-gray-300 dark:border-gray-600 rounded-md px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                @for($m = 0; $m < 60; $m += 5)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            <select class="time-ampm border border-gray-300 dark:border-gray-600 rounded-md px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-clock mr-1 text-gray-400 dark:text-gray-500"></i>End Time <span class="text-red-500">*</span>
                        </label>
                        <div class="time-picker-group @error('end_time') border-red-300 dark:border-red-600 @enderror">
                            <input type="hidden" name="end_time" id="end_time_hidden" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required>
                            <select class="time-hour border border-gray-300 dark:border-gray-600 rounded-md px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                @for($h = 1; $h <= 12; $h++)
                                    <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">{{ $h }}</option>
                                @endfor
                            </select>
                            <select class="time-minute border border-gray-300 dark:border-gray-600 rounded-md px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                @for($m = 0; $m < 60; $m += 5)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            <select class="time-ampm border border-gray-300 dark:border-gray-600 rounded-md px-2 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Presenters -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="presenter_primary" class="block text-sm font-medium text-gray-700 mb-1">Primary Presenter</label>
                        <input type="text" id="presenter_primary" name="presenter_primary" value="{{ old('presenter_primary', $schedule->presenter_primary) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_primary') border-red-300 @enderror">
                        @error('presenter_primary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="presenter_secondary" class="block text-sm font-medium text-gray-700 mb-1">Secondary Presenter</label>
                        <input type="text" id="presenter_secondary" name="presenter_secondary" value="{{ old('presenter_secondary', $schedule->presenter_secondary) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_secondary') border-red-300 @enderror">
                        @error('presenter_secondary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="presenter_bio" class="block text-sm font-medium text-gray-700 mb-1">Presenter Bio</label>
                        <textarea id="presenter_bio" name="presenter_bio" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('presenter_bio') border-red-300 @enderror">{{ old('presenter_bio', $schedule->presenter_bio) }}</textarea>
                        @error('presenter_bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Capacity and Requirements -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">Max Participants</label>
                        <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $schedule->max_participants) }}" min="1" max="500"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('max_participants') border-red-300 @enderror">
                        @error('max_participants')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-aes-blue shadow-sm focus:border-aes-blue focus:ring focus:ring-aes-blue focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="space-y-6">
                    <div>
                        <label for="equipment_needed" class="block text-sm font-medium text-gray-700 mb-1">Equipment Needed</label>
                        <textarea id="equipment_needed" name="equipment_needed" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('equipment_needed') border-red-300 @enderror">{{ old('equipment_needed', $schedule->equipment_needed) }}</textarea>
                        @error('equipment_needed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                        <textarea id="special_requirements" name="special_requirements" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('special_requirements') border-red-300 @enderror">{{ old('special_requirements', $schedule->special_requirements) }}</textarea>
                        @error('special_requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Section -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h4 class="text-md font-medium text-gray-900 mb-3">🔗 Additional Link (Optional)</h4>
                        <p class="text-sm text-gray-600 mb-4">Add a link that users can click to access additional resources (e.g., menu, materials, documents)</p>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="link_title" class="block text-sm font-medium text-gray-700 mb-1">Link Title</label>
                                <input type="text" id="link_title" name="link_title" value="{{ old('link_title', $schedule->link_title) }}"
                                       placeholder="e.g., View Menu, Download Materials"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                              @error('link_title') border-red-300 @enderror">
                                @error('link_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                                <input type="url" id="link_url" name="link_url" value="{{ old('link_url', $schedule->link_url) }}"
                                       placeholder="https://example.com or example.com"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                              @error('link_url') border-red-300 @enderror">
                                @error('link_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="link_description" class="block text-sm font-medium text-gray-700 mb-1">Link Description (Optional)</label>
                            <textarea id="link_description" name="link_description" rows="2"
                                      placeholder="Brief description of what users will find at this link..."
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('link_description') border-red-300 @enderror">{{ old('link_description', $schedule->link_description) }}</textarea>
                            @error('link_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Divisions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Target Divisions</label>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($divisions as $division)
                            <div class="flex items-center">
                                <input type="checkbox" id="division_{{ $division->id }}" name="divisions[]" value="{{ $division->id }}"
                                       {{ in_array($division->id, old('divisions', $schedule->divisions->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-aes-blue shadow-sm focus:border-aes-blue focus:ring focus:ring-aes-blue focus:ring-opacity-50">
                                <label for="division_{{ $division->id }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $division->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('divisions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PL Day -->
                <div>
                    <label for="pd_day_id" class="block text-sm font-medium text-gray-700 mb-1">
                        PL Day Event
                    </label>
                    <select id="pd_day_id" name="pd_day_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                   @error('pd_day_id') border-red-300 @enderror">
                        <option value="">Not assigned to any PL Day</option>
                        @foreach($pdDays as $pdDay)
                            <option value="{{ $pdDay->id }}" {{ old('pd_day_id', $schedule->pd_day_id) == $pdDay->id ? 'selected' : '' }}>
                                {{ $pdDay->title }} ({{ $pdDay->date_range }})
                            </option>
                        @endforeach
                    </select>
                    @error('pd_day_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.schedule.index') }}" 
                       class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-aes-blue hover:bg-blue-700 text-white rounded-md font-medium transition-colors">
                        Update Schedule Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle wellness session selector based on session type
document.addEventListener('DOMContentLoaded', function() {
    const sessionTypeSelect = document.getElementById('session_type');
    const wellnessDiv = document.getElementById('wellness_session_div');
    const wellnessSelect = document.getElementById('wellness_session_id');
    
    function updateWellnessVisibility() {
        if (sessionTypeSelect.value === 'wellness') {
            wellnessDiv.classList.remove('hidden');
            wellnessSelect.required = true;
        } else {
            wellnessDiv.classList.add('hidden');
            wellnessSelect.required = false;
            wellnessSelect.value = '';
        }
    }
    
    sessionTypeSelect.addEventListener('change', updateWellnessVisibility);
    updateWellnessVisibility(); // Initialize on load
});
</script>
@endsection

