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

                        <!-- Links Section -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-medium text-gray-900">🔗 Additional Links (Optional)</h4>
                                <button type="button" id="add-link-btn" class="px-3 py-1 text-sm bg-aes-blue text-white rounded hover:bg-blue-700 transition-colors">
                                    Add Link
                                </button>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Add links that users can click to access additional resources (e.g., menu, materials, documents)</p>
                            
                            <div id="links-container">
                                <!-- Links will be added dynamically here -->
                            </div>
                            
                            <div class="text-sm text-gray-500 mt-2">
                                <p>💡 <strong>Tip:</strong> You can add multiple links by clicking the "Add Link" button. Each link will appear as a separate button on the session card.</p>
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
    
    // Initialize links functionality
    initializeLinks();
});

// Links management
let linkCounter = 0;

function initializeLinks() {
    const addLinkBtn = document.getElementById('add-link-btn');
    const linksContainer = document.getElementById('links-container');
    
    addLinkBtn.addEventListener('click', addLink);
    
    // Add initial link if old data exists
    const oldTitle = '{{ old("link_title") }}';
    const oldUrl = '{{ old("link_url") }}';
    const oldDescription = '{{ old("link_description") }}';
    
    if (oldTitle && oldUrl) {
        addLink(oldTitle, oldUrl, oldDescription);
    }
}

function addLink(title = '', url = '', description = '') {
    const linksContainer = document.getElementById('links-container');
    const linkIndex = linkCounter++;
    
    const linkHtml = `
        <div class="link-item border border-gray-300 rounded-lg p-4 mb-4 bg-white" data-index="${linkIndex}">
            <div class="flex items-center justify-between mb-3">
                <h5 class="text-sm font-medium text-gray-700">Link ${linkIndex + 1}</h5>
                <button type="button" class="remove-link-btn text-red-600 hover:text-red-800 text-sm font-medium">
                    Remove
                </button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link Title</label>
                                    <input type="text" name="links[${linkIndex}][title]" value="${title}"
                                           placeholder="e.g., Food Menu, View Materials, Download Resources"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                    <input type="url" name="links[${linkIndex}][url]" value="${url}"
                           placeholder="https://example.com or example.com"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Link Description (Optional)</label>
                <textarea name="links[${linkIndex}][description]" rows="2"
                          placeholder="Brief description of what users will find at this link..."
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">${description}</textarea>
            </div>
        </div>
    `;
    
    linksContainer.insertAdjacentHTML('beforeend', linkHtml);
    
    // Add remove functionality
    const removeBtn = linksContainer.querySelector(`[data-index="${linkIndex}"] .remove-link-btn`);
    removeBtn.addEventListener('click', () => {
        removeLink(linkIndex);
    });
}

function removeLink(index) {
    const linkItem = document.querySelector(`[data-index="${index}"]`);
    if (linkItem) {
        linkItem.remove();
    }
}
</script>
@endsection
