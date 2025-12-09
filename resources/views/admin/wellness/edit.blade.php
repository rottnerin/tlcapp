@extends('layouts.app')

@section('title', 'Edit Wellness Session')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Wellness Session</h1>
            <p class="mt-1 text-sm text-gray-600">Update the wellness session details</p>
        </div>

        <form action="{{ route('admin.wellness.update', $wellness) }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Session Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $wellness->title) }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('title') border-red-300 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Session Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('description') border-red-300 @enderror">{{ old('description', $wellness->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories (check all that apply)</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox" id="category_{{ $loop->index }}" name="category[]" value="{{ $category }}"
                                           {{ in_array($category, old('category', $wellness->category ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue">
                                    <label for="category_{{ $loop->index }}" class="ml-2 text-sm text-gray-700">{{ $category }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('category.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $wellness->location) }}"
                               placeholder="e.g., Gym, Library, Room 101"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('location') border-red-300 @enderror">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Presenter Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Presenter Information</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Presenter Name</label>
                        <input type="text" id="presenter_name" name="presenter_name" value="{{ old('presenter_name', $wellness->presenter_name) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('presenter_name') border-red-300 @enderror">
                        @error('presenter_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Presenter Email</label>
                        <input type="email" id="presenter_email" name="presenter_email" value="{{ old('presenter_email', $wellness->presenter_email) }}"
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
                                         @error('presenter_bio') border-red-300 @enderror">{{ old('presenter_bio', $wellness->presenter_bio) }}</textarea>
                        @error('presenter_bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="co_presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Name(s)</label>
                        <input type="text" id="co_presenter_name" name="co_presenter_name" value="{{ old('co_presenter_name', $wellness->co_presenter_name) }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('co_presenter_name') border-red-300 @enderror">
                        @error('co_presenter_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="co_presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Email(s)</label>
                        <input type="email" id="co_presenter_email" name="co_presenter_email" value="{{ old('co_presenter_email', $wellness->co_presenter_email) }}"
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
                        <label for="pd_day_id" class="block text-sm font-medium text-gray-700 mb-1">
                            PL Day Event
                        </label>
                        <select id="pd_day_id" name="pd_day_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                       @error('pd_day_id') border-red-300 @enderror">
                            <option value="">Not assigned to any PL Day</option>
                            @foreach($pdDays as $pdDay)
                                <option value="{{ $pdDay->id }}" {{ old('pd_day_id', $wellness->pd_day_id) == $pdDay->id ? 'selected' : '' }}>
                                    {{ $pdDay->title }} ({{ $pdDay->date_range }})
                                </option>
                            @endforeach
                        </select>
                        @error('pd_day_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date" name="date" value="{{ old('date', $wellness->date ? $wellness->date->format('Y-m-d') : '') }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('date') border-red-300 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">All wellness sessions run from 2:30 PM - 3:30 PM</p>
                    </div>

                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">
                            Max Participants <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="max_participants" name="max_participants" 
                               value="{{ old('max_participants', $wellness->max_participants) }}" min="1" max="200" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('max_participants') border-red-300 @enderror">
                        @error('max_participants')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Details</h3>
                <div class="space-y-6">
                    <div>
                        <label for="equipment_needed" class="block text-sm font-medium text-gray-700 mb-1">Equipment Needed</label>
                        <textarea id="equipment_needed" name="equipment_needed" rows="2"
                                  placeholder="List any equipment participants need to bring or that will be provided"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('equipment_needed') border-red-300 @enderror">{{ old('equipment_needed', $wellness->equipment_needed) }}</textarea>
                        @error('equipment_needed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                        <textarea id="special_requirements" name="special_requirements" rows="2"
                                  placeholder="e.g., fitness level requirements, dietary restrictions"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('special_requirements') border-red-300 @enderror">{{ old('special_requirements', $wellness->special_requirements) }}</textarea>
                        @error('special_requirements')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="preparation_notes" class="block text-sm font-medium text-gray-700 mb-1">Preparation Notes</label>
                        <textarea id="preparation_notes" name="preparation_notes" rows="2"
                                  placeholder="What participants should know or do before the session"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('preparation_notes') border-red-300 @enderror">{{ old('preparation_notes', $wellness->preparation_notes) }}</textarea>
                        @error('preparation_notes')
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
                               {{ old('is_active', $wellness->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Session is active and available for enrollment
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t pt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.wellness.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aes-blue">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-aes-blue border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aes-blue">
                    Update Session
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
