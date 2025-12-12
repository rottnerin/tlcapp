@extends('layouts.app')

@section('title', 'Edit Schedule Item')

@section('content')
<style>
    .card { background: #ffffff; border: 1px solid #e2e8f0; }
    .form-input { 
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        color: #1e293b;
        transition: all 0.15s ease;
    }
    .form-input:focus { 
        border-color: #2563eb; 
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .form-label { color: #475569; }
    .section-title { color: #1e293b; border-bottom: 1px solid #e2e8f0; }
</style>

<div class="min-h-screen py-8" style="background: #f1f5f9;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
                <a href="{{ route('admin.schedule.index') }}" 
               class="inline-flex items-center text-sm font-medium mb-4" style="color: #2563eb;">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Schedule Items
                </a>
            <h1 class="text-2xl font-bold" style="color: #1e293b;">Edit Schedule Item</h1>
            <p class="mt-1" style="color: #64748b;">Update the schedule item information</p>
        </div>

        <!-- Form Card -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.schedule.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-info-circle mr-2" style="color: #64748b;"></i>Basic Information
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium form-label mb-1">
                            Title <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $schedule->title) }}" required
                               class="w-full rounded-lg px-4 py-2.5 form-input @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium form-label mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('description') border-red-500 @enderror">{{ old('description', $schedule->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                            <label for="session_type" class="block text-sm font-medium form-label mb-1">
                                Session Type <span style="color: #dc2626;">*</span>
                        </label>
                        <select id="session_type" name="session_type" required
                                    class="w-full rounded-lg px-4 py-2.5 form-input @error('session_type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="fixed" {{ old('session_type', $schedule->session_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="wellness" {{ old('session_type', $schedule->session_type) == 'wellness' ? 'selected' : '' }}>Wellness</option>
                            <option value="keynote" {{ old('session_type', $schedule->session_type) == 'keynote' ? 'selected' : '' }}>Keynote</option>
                            <option value="break" {{ old('session_type', $schedule->session_type) == 'break' ? 'selected' : '' }}>Break</option>
                            <option value="lunch" {{ old('session_type', $schedule->session_type) == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        </select>
                        @error('session_type')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium form-label mb-1">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $schedule->location) }}"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <!-- Wellness Session Selector (shown only for wellness slots) -->
                    <div id="wellness_session_div" class="{{ $schedule->session_type === 'wellness' ? '' : 'hidden' }}">
                        <label for="wellness_session_id" class="block text-sm font-medium form-label mb-1">
                            Link to Wellness Session <span style="color: #f59e0b;">*</span>
                        </label>
                        <select id="wellness_session_id" name="wellness_session_id"
                                class="w-full rounded-lg px-4 py-2.5 form-input @error('wellness_session_id') border-red-500 @enderror"
                                {{ $schedule->session_type === 'wellness' ? 'required' : '' }}>
                            <option value="">Select a wellness session...</option>
                            @foreach($wellnessSessions as $session)
                                <option value="{{ $session->id }}" {{ old('wellness_session_id', $schedule->wellness_session_id) == $session->id ? 'selected' : '' }}>
                                    {{ $session->title }} - {{ $session->presenter_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm" style="color: #64748b;">Select the wellness session this time slot belongs to.</p>
                        @error('wellness_session_id')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Schedule -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-calendar-alt mr-2" style="color: #64748b;"></i>Schedule
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium form-label mb-1">
                                Start Date & Time <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" 
                                   name="start_time" 
                                   id="start_time" 
                                   value="{{ old('start_time', $schedule->start_time->format('Y-m-d H:i')) }}" 
                                   required
                                   placeholder="Select date and time"
                                   class="w-full rounded-lg px-4 py-2.5 form-input flatpickr-datetime @error('start_time') border-red-500 @enderror"
                                   readonly>
                            @error('start_time')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium form-label mb-1">
                                End Date & Time <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" 
                                   name="end_time" 
                                   id="end_time" 
                                   value="{{ old('end_time', $schedule->end_time->format('Y-m-d H:i')) }}" 
                                   required
                                   placeholder="Select date and time"
                                   class="w-full rounded-lg px-4 py-2.5 form-input flatpickr-datetime @error('end_time') border-red-500 @enderror"
                                   readonly>
                            @error('end_time')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Presenters -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-user mr-2" style="color: #64748b;"></i>Presenters
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                            <label for="presenter_primary" class="block text-sm font-medium form-label mb-1">Primary Presenter</label>
                        <input type="text" id="presenter_primary" name="presenter_primary" value="{{ old('presenter_primary', $schedule->presenter_primary) }}"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('presenter_primary') border-red-500 @enderror">
                        @error('presenter_primary')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                            <label for="presenter_secondary" class="block text-sm font-medium form-label mb-1">Secondary Presenter</label>
                        <input type="text" id="presenter_secondary" name="presenter_secondary" value="{{ old('presenter_secondary', $schedule->presenter_secondary) }}"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('presenter_secondary') border-red-500 @enderror">
                        @error('presenter_secondary')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <div>
                        <label for="presenter_bio" class="block text-sm font-medium form-label mb-1">Presenter Bio</label>
                        <textarea id="presenter_bio" name="presenter_bio" rows="3"
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('presenter_bio') border-red-500 @enderror">{{ old('presenter_bio', $schedule->presenter_bio) }}</textarea>
                        @error('presenter_bio')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Capacity & Settings -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-cog mr-2" style="color: #64748b;"></i>Capacity & Settings
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                            <label for="max_participants" class="block text-sm font-medium form-label mb-1">Max Participants</label>
                        <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $schedule->max_participants) }}" min="1" max="500"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('max_participants') border-red-500 @enderror">
                        @error('max_participants')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                            <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                                       class="h-5 w-5 rounded" style="accent-color: #2563eb;">
                                <div>
                                    <span class="font-medium" style="color: #1e293b;">Active</span>
                                    <p class="text-sm" style="color: #64748b;">Item is visible in schedule</p>
                                </div>
                            </label>
                    </div>
                </div>

                    <div>
                        <label for="equipment_needed" class="block text-sm font-medium form-label mb-1">Equipment Needed</label>
                        <textarea id="equipment_needed" name="equipment_needed" rows="2"
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('equipment_needed') border-red-500 @enderror">{{ old('equipment_needed', $schedule->equipment_needed) }}</textarea>
                        @error('equipment_needed')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="special_requirements" class="block text-sm font-medium form-label mb-1">Special Requirements</label>
                        <textarea id="special_requirements" name="special_requirements" rows="2"
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('special_requirements') border-red-500 @enderror">{{ old('special_requirements', $schedule->special_requirements) }}</textarea>
                        @error('special_requirements')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>

                    <!-- Link Section -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-link mr-2" style="color: #64748b;"></i>Additional Link (Optional)
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <p class="text-sm" style="color: #64748b;">Add a link that users can click to access additional resources</p>
                        
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                            <label for="link_title" class="block text-sm font-medium form-label mb-1">Link Title</label>
                                <input type="text" id="link_title" name="link_title" value="{{ old('link_title', $schedule->link_title) }}"
                                       placeholder="e.g., View Menu, Download Materials"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('link_title') border-red-500 @enderror">
                                @error('link_title')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                            <label for="link_url" class="block text-sm font-medium form-label mb-1">Link URL</label>
                                <input type="url" id="link_url" name="link_url" value="{{ old('link_url', $schedule->link_url) }}"
                                   placeholder="https://example.com"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('link_url') border-red-500 @enderror">
                                @error('link_url')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    <div>
                        <label for="link_description" class="block text-sm font-medium form-label mb-1">Link Description</label>
                            <textarea id="link_description" name="link_description" rows="2"
                                      placeholder="Brief description of what users will find at this link..."
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('link_description') border-red-500 @enderror">{{ old('link_description', $schedule->link_description) }}</textarea>
                            @error('link_description')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <!-- Divisions & PL Day -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-sitemap mr-2" style="color: #64748b;"></i>Target Audience
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                <div>
                        <label class="block text-sm font-medium form-label mb-3">Target Divisions</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($divisions as $division)
                                <label class="flex items-center space-x-3 cursor-pointer p-3 rounded-lg" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <input type="checkbox" id="division_{{ $division->id }}" name="divisions[]" value="{{ $division->id }}"
                                       {{ in_array($division->id, old('divisions', $schedule->divisions->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded" style="accent-color: #2563eb;">
                                    <span class="text-sm font-medium" style="color: #1e293b;">{{ $division->name }}</span>
                                </label>
                        @endforeach
                    </div>
                    @error('divisions')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                        <label for="pd_day_id" class="block text-sm font-medium form-label mb-1">PL Day Event</label>
                    <select id="pd_day_id" name="pd_day_id"
                                class="w-full rounded-lg px-4 py-2.5 form-input @error('pd_day_id') border-red-500 @enderror">
                        <option value="">Not assigned to any PL Day</option>
                        @foreach($pdDays as $pdDay)
                            <option value="{{ $pdDay->id }}" {{ old('pd_day_id', $schedule->pd_day_id) == $pdDay->id ? 'selected' : '' }}>
                                {{ $pdDay->title }} ({{ $pdDay->date_range }})
                            </option>
                        @endforeach
                    </select>
                    @error('pd_day_id')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="p-6 flex justify-end space-x-4" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('admin.schedule.index') }}" 
                       class="px-6 py-2.5 rounded-lg font-medium transition-colors"
                       style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569;">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-lg font-medium text-white transition-colors"
                            style="background: #2563eb;">
                        <i class="fas fa-save mr-2"></i>Update Schedule Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
    updateWellnessVisibility();
});
</script>
@endsection
