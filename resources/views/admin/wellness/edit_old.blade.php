@extends('layouts.app')

@section('title', 'Edit Wellness Session')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.wellness.index') }}" 
                   class="text-aes-blue hover:text-blue-700 mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Wellness Sessions
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Wellness Session</h1>
            <p class="text-gray-600">Update wellness session details</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('admin.wellness.update', $wellness) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Session Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $wellness->title) }}" required
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('title') border-red-300 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                         @error('description') border-red-300 @enderror">{{ old('description', $wellness->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input type="text" id="category" name="category" value="{{ old('category', $wellness->category) }}"
                               placeholder="e.g., mindfulness, fitness, nutrition"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                      @error('category') border-red-300 @enderror">
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $wellness->location) }}"
                               placeholder="e.g., Gym, Library, Room 101"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                            <label for="presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Presenter Name</label>
                            <input type="text" id="presenter_name" name="presenter_name" value="{{ old('presenter_name', $wellness->presenter_name) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('presenter_name') border-red-300 @enderror">
                            @error('presenter_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Presenter Email</label>
                            <input type="email" id="presenter_email" name="presenter_email" value="{{ old('presenter_email', $wellness->presenter_email) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('presenter_email') border-red-300 @enderror">
                            @error('presenter_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label for="presenter_bio" class="block text-sm font-medium text-gray-700 mb-1">Presenter Bio</label>
                            <textarea id="presenter_bio" name="presenter_bio" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('presenter_bio') border-red-300 @enderror">{{ old('presenter_bio', $wellness->presenter_bio) }}</textarea>
                            @error('presenter_bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="co_presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Name(s)</label>
                            <input type="text" id="co_presenter_name" name="co_presenter_name" value="{{ old('co_presenter_name', $wellness->co_presenter_name) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('co_presenter_name') border-red-300 @enderror">
                            @error('co_presenter_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="co_presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Email(s)</label>
                            <input type="email" id="co_presenter_email" name="co_presenter_email" value="{{ old('co_presenter_email', $wellness->co_presenter_email) }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date" name="date" 
                                   value="{{ old('date', $wellness->date ? $wellness->date->format('Y-m-d') : '') }}" required
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
                            <input type="time" id="start_time" name="start_time" 
                                   value="{{ old('start_time', $wellness->start_time ? $wellness->start_time->format('H:i') : '') }}" required
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
                            <input type="time" id="end_time" name="end_time" 
                                   value="{{ old('end_time', $wellness->end_time ? $wellness->end_time->format('H:i') : '') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('end_time') border-red-300 @enderror">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">
                                Max Participants <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="max_participants" name="max_participants" 
                                   value="{{ old('max_participants', $wellness->max_participants) }}" min="1" max="200" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('max_participants') border-red-300 @enderror">
                            @error('max_participants')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($wellness->userSessions()->count() > 0)
                                <p class="mt-1 text-sm text-yellow-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Current enrollments: {{ $wellness->userSessions()->count() }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="equipment_needed" class="block text-sm font-medium text-gray-700 mb-1">Equipment Needed</label>
                            <textarea id="equipment_needed" name="equipment_needed" rows="2"
                                      placeholder="e.g., yoga mats, comfortable clothing, water bottle"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('equipment_needed') border-red-300 @enderror">{{ old('equipment_needed', $wellness->equipment_needed) }}</textarea>
                            @error('equipment_needed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                            <textarea id="special_requirements" name="special_requirements" rows="2"
                                      placeholder="e.g., fitness level requirements, dietary restrictions"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('special_requirements') border-red-300 @enderror">{{ old('special_requirements', $wellness->special_requirements) }}</textarea>
                            @error('special_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="preparation_notes" class="block text-sm font-medium text-gray-700 mb-1">Preparation Notes</label>
                            <textarea id="preparation_notes" name="preparation_notes" rows="2"
                                      placeholder="What participants should know or do before the session"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue
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
                        </div>

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

                <!-- Submit Buttons -->
                <div class="border-t pt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.wellness.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-aes-blue hover:bg-blue-700 text-white rounded-md transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
