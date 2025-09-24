@extends('layouts.app')

@section('title', 'Edit Schedule Item')

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
            <h1 class="text-3xl font-bold text-gray-900">Edit Schedule Item</h1>
            <p class="text-gray-600 mt-1">Update the schedule item information</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-card border">
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
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $schedule->location) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('location') border-red-300 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date" name="date" value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('date') border-red-300 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('start_time') border-red-300 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('end_time') border-red-300 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Presenters -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="presenter_primary" class="block text-sm font-medium text-gray-700 mb-1">Primary Presenter</label>
                        <input type="text" id="presenter_primary" name="presenter_primary" value="{{ old('presenter_primary', $schedule->presenter_primary) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_primary') border-red-300 @enderror">
                        @error('presenter_primary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="presenter_secondary" class="block text-sm font-medium text-gray-700 mb-1">Secondary Presenter</label>
                        <input type="text" id="presenter_secondary" name="presenter_secondary" value="{{ old('presenter_secondary', $schedule->presenter_secondary) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_secondary') border-red-300 @enderror">
                        @error('presenter_secondary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="presenter_bio" class="block text-sm font-medium text-gray-700 mb-1">Presenter Bio</label>
                        <textarea id="presenter_bio" name="presenter_bio" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('equipment_needed') border-red-300 @enderror">{{ old('equipment_needed', $schedule->equipment_needed) }}</textarea>
                        @error('equipment_needed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                        <textarea id="special_requirements" name="special_requirements" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('special_requirements') border-red-300 @enderror">{{ old('special_requirements', $schedule->special_requirements) }}</textarea>
                        @error('special_requirements')
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
@endsection

<script>
// Links management
let linkCounter = 0;

document.addEventListener('DOMContentLoaded', function() {
    initializeLinks();
});

function initializeLinks() {
    const addLinkBtn = document.getElementById('add-link-btn');
    const linksContainer = document.getElementById('links-container');
    
    addLinkBtn.addEventListener('click', addLink);
    
    // Load existing links
    const existingLinks = @json($schedule->links ?? []);
    
    if (existingLinks.length > 0) {
        existingLinks.forEach(link => {
            addLink(link.title, link.url, link.description);
        });
    }
    
    // Add initial link if old data exists
    const oldLinks = @json(old('links', []));
    if (oldLinks.length > 0) {
        // Clear existing links first
        linksContainer.innerHTML = '';
        linkCounter = 0;
        
        oldLinks.forEach(link => {
            addLink(link.title || '', link.url || '', link.description || '');
        });
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
