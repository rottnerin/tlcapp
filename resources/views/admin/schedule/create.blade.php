@extends('layouts.app')

@section('title', 'Create Schedule Item')

@section('content')
<div class="min-h-screen bg-content py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.schedule.index') }}" 
                   class="text-aes-blue hover:text-blue-700 mr-4 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Schedule Items
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Schedule Item</h1>
            <p class="text-gray-600 mt-1">Add a new item to the professional development schedule</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-card border">
            <form action="{{ route('admin.schedule.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('title') border-red-300 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="item_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select id="item_type" name="item_type" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('item_type') border-red-300 @enderror">
                            <option value="">Select type...</option>
                            <option value="session" {{ old('item_type') == 'session' ? 'selected' : '' }}>Session</option>
                            <option value="break" {{ old('item_type') == 'break' ? 'selected' : '' }}>Break</option>
                            <option value="meal" {{ old('item_type') == 'meal' ? 'selected' : '' }}>Meal</option>
                            <option value="assembly" {{ old('item_type') == 'assembly' ? 'selected' : '' }}>Assembly</option>
                            <option value="transition" {{ old('item_type') == 'transition' ? 'selected' : '' }}>Transition</option>
                            <option value="meeting" {{ old('item_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="other" {{ old('item_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('item_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="session_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Session Type <span class="text-red-500">*</span>
                        </label>
                        <select id="session_type" name="session_type" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('session_type') border-red-300 @enderror">
                            <option value="">Select session type...</option>
                            <option value="fixed" {{ old('session_type') == 'fixed' ? 'selected' : '' }}>Fixed Session</option>
                            <option value="wellness" {{ old('session_type') == 'wellness' ? 'selected' : '' }}>Wellness Slot</option>
                            <option value="keynote" {{ old('session_type') == 'keynote' ? 'selected' : '' }}>Keynote</option>
                            <option value="break" {{ old('session_type') == 'break' ? 'selected' : '' }}>Break</option>
                            <option value="lunch" {{ old('session_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                            <option value="transition" {{ old('session_type') == 'transition' ? 'selected' : '' }}>Transition</option>
                        </select>
                        @error('session_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wellness Session Selector (shown only for wellness slots) -->
                    <div id="wellness_session_div" class="hidden lg:col-span-2">
                        <label for="wellness_session_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Link to Wellness Session <span class="text-orange-500">*</span>
                        </label>
                        <select id="wellness_session_id" name="wellness_session_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('wellness_session_id') border-red-300 @enderror">
                            <option value="">Select a wellness session...</option>
                            @foreach($wellnessSessions as $session)
                                <option value="{{ $session->id }}" {{ old('wellness_session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->title }} - {{ $session->presenter_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select the wellness session this time slot belongs to. If you don't see your wellness session, create it first in Wellness management.</p>
                        @error('wellness_session_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Division <span class="text-red-500">*</span>
                        </label>
                        <select id="division_id" name="division_id" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('division_id') border-red-300 @enderror">
                            <option value="">Select division...</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pd_day_id" class="block text-sm font-medium text-gray-700 mb-1">
                            PD Day Event
                        </label>
                        <select id="pd_day_id" name="pd_day_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('pd_day_id') border-red-300 @enderror">
                            <option value="">Not assigned to any PD Day</option>
                            @foreach($pdDays as $pdDay)
                                <option value="{{ $pdDay->id }}" {{ old('pd_day_id') == $pdDay->id ? 'selected' : '' }}>
                                    {{ $pdDay->title }} ({{ $pdDay->date_range }})
                                </option>
                            @endforeach
                        </select>
                        @error('pd_day_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Presenter Name</label>
                        <input type="text" id="presenter_name" name="presenter_name" value="{{ old('presenter_name') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_name') border-red-300 @enderror">
                        @error('presenter_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}"
                               placeholder="e.g., Auditorium, Gym, Library"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('location') border-red-300 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Schedule -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Start Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('start_time') border-red-300 @enderror">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                End Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('end_time') border-red-300 @enderror">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visual Settings -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Visual Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" id="color" name="color" value="{{ old('color', '#3B82F6') }}"
                                       class="h-10 w-16 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-aes-blue">
                                <span class="text-sm text-gray-600">Choose a color for calendar display</span>
                            </div>
                            @error('color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                      placeholder="Internal notes, setup requirements, special instructions..."
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
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
                                    <input type="text" id="link_title" name="link_title" value="{{ old('link_title') }}"
                                           placeholder="e.g., View Menu, Download Materials"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                                  @error('link_title') border-red-300 @enderror">
                                    @error('link_title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                                    <input type="url" id="link_url" name="link_url" value="{{ old('link_url') }}"
                                           placeholder="https://example.com or example.com"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                                 @error('link_description') border-red-300 @enderror">{{ old('link_description') }}</textarea>
                                @error('link_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_required" name="is_required" value="1"
                                   {{ old('is_required') ? 'checked' : '' }}
                                   class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue">
                            <label for="is_required" class="ml-2 text-sm text-gray-700">
                                This is a required item (mandatory attendance)
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">
                                Item is active and visible in schedule
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="border-t pt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.schedule.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-aes-blue hover:bg-blue-700 text-white rounded-md transition-colors">
                        <i class="fas fa-save mr-2"></i>Create Schedule Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-adjust end time when start time changes
document.getElementById('start_time').addEventListener('change', function() {
    const startTime = new Date(this.value);
    const endTimeInput = document.getElementById('end_time');
    
    if (startTime && !endTimeInput.value) {
        // Default to 1 hour duration
        const endTime = new Date(startTime.getTime() + 60 * 60 * 1000);
        endTimeInput.value = endTime.toISOString().slice(0, 16);
    }
});

// Color presets
const colorPresets = [
    '#3B82F6', // Blue
    '#10B981', // Green  
    '#F59E0B', // Yellow
    '#EF4444', // Red
    '#8B5CF6', // Purple
    '#06B6D4', // Cyan
    '#84CC16', // Lime
    '#F97316', // Orange
];

// Add color preset buttons
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorContainer = colorInput.parentElement;
    
    const presetContainer = document.createElement('div');
    presetContainer.className = 'flex space-x-2 mt-2';
    
    colorPresets.forEach(color => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'w-6 h-6 rounded border-2 border-gray-300 hover:border-gray-400';
        button.style.backgroundColor = color;
        button.onclick = () => colorInput.value = color;
        presetContainer.appendChild(button);
    });
    
    colorContainer.appendChild(presetContainer);
});

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
