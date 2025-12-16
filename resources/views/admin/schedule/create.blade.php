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
                                         @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Division <span class="text-red-500">*</span>
                        </label>
                        <select id="division_id" name="division_id" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                            PL Day Event
                        </label>
                        <select id="pd_day_id" name="pd_day_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('pd_day_id') border-red-300 @enderror">
                            <option value="">Not assigned to any PL Day</option>
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
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_name') border-red-300 @enderror">
                        @error('presenter_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}"
                               placeholder="e.g., Auditorium, Gym, Library"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('end_time') border-red-300 @enderror">
                            @error('end_time')
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
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Links Section -->
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="text-md font-medium text-gray-900">🔗 Links (Optional)</h4>
                                    <p class="text-sm text-gray-600">Add links that users can click to access additional resources (e.g., menu, materials, documents)</p>
                                </div>
                                <button type="button" id="add-link-btn"
                                        class="px-3 py-1.5 bg-aes-blue hover:bg-blue-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>Add Link
                                </button>
                            </div>
                            
                            <div id="links-container" class="space-y-4">
                                <!-- Links will be added here dynamically -->
                            </div>
                            
                            <div id="no-links-message" class="text-center py-4 text-gray-500 text-sm">
                                No links added yet. Click "Add Link" to add a resource link.
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

// Dynamic Links Management
document.addEventListener('DOMContentLoaded', function() {
    const linksContainer = document.getElementById('links-container');
    const addLinkBtn = document.getElementById('add-link-btn');
    const noLinksMessage = document.getElementById('no-links-message');
    let linkIndex = 0;

    function updateNoLinksMessage() {
        const linkCards = linksContainer.querySelectorAll('.link-card');
        noLinksMessage.style.display = linkCards.length === 0 ? 'block' : 'none';
    }

    function createLinkCard(index) {
        const card = document.createElement('div');
        card.className = 'link-card bg-white border border-gray-200 rounded-lg p-4 relative';
        card.innerHTML = `
            <button type="button" class="remove-link-btn absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors" title="Remove link">
                <i class="fas fa-times"></i>
            </button>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link Title</label>
                    <input type="text" name="links[${index}][title]" 
                           placeholder="e.g., View Menu, Download Materials"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                    <input type="url" name="links[${index}][url]" 
                           placeholder="https://example.com"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Link Description (Optional)</label>
                <input type="text" name="links[${index}][description]" 
                       placeholder="Brief description of what users will find at this link..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue">
            </div>
        `;

        // Add remove functionality
        card.querySelector('.remove-link-btn').addEventListener('click', function() {
            card.remove();
            updateNoLinksMessage();
        });

        return card;
    }

    addLinkBtn.addEventListener('click', function() {
        const card = createLinkCard(linkIndex);
        linksContainer.appendChild(card);
        linkIndex++;
        updateNoLinksMessage();
        
        // Focus on the first input of the new card
        card.querySelector('input').focus();
    });

    // Initialize with old values if any
    @if(old('links'))
        @foreach(old('links') as $index => $link)
            (function() {
                const card = createLinkCard({{ $index }});
                linksContainer.appendChild(card);
                card.querySelector('input[name="links[{{ $index }}][title]"]').value = "{{ $link['title'] ?? '' }}";
                card.querySelector('input[name="links[{{ $index }}][url]"]').value = "{{ $link['url'] ?? '' }}";
                card.querySelector('input[name="links[{{ $index }}][description]"]').value = "{{ $link['description'] ?? '' }}";
                linkIndex = {{ $index + 1 }};
            })();
        @endforeach
        updateNoLinksMessage();
    @endif
});
</script>
@endsection
