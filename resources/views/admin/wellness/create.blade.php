@extends('layouts.app')

@section('title', 'Create Wellness Session')

@section('content')
<div class="min-h-screen bg-content py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.wellness.index') }}" 
                   class="text-aes-blue hover:text-blue-700 mr-4 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Wellness Sessions
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Wellness Session</h1>
            <p class="text-gray-600 mt-1">Add a new wellness session for staff to enroll in</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-card border">
            <form action="{{ route('admin.wellness.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Session Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
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
                                         @error('description') border-red-300 ring-2 ring-red-200 @enderror">{{ old('description') }}</textarea>
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
                                           {{ in_array($category, old('category', [])) ? 'checked' : '' }}
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
                        <input type="text" id="location" name="location" value="{{ old('location') }}"
                               placeholder="e.g., Gym, Library, Room 101"
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
                            <label for="presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Presenter Name</label>
                            <input type="text" id="presenter_name" name="presenter_name" value="{{ old('presenter_name') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('presenter_name') border-red-300 @enderror">
                            @error('presenter_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Presenter Email</label>
                            <input type="email" id="presenter_email" name="presenter_email" value="{{ old('presenter_email') }}"
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
                                             @error('presenter_bio') border-red-300 @enderror">{{ old('presenter_bio') }}</textarea>
                            @error('presenter_bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="co_presenter_name" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Name(s)</label>
                            <input type="text" id="co_presenter_name" name="co_presenter_name" value="{{ old('co_presenter_name') }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('co_presenter_name') border-red-300 @enderror">
                            @error('co_presenter_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="co_presenter_email" class="block text-sm font-medium text-gray-700 mb-1">Co-Presenter Email(s)</label>
                            <input type="email" id="co_presenter_email" name="co_presenter_email" value="{{ old('co_presenter_email') }}"
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
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>Date <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="date" name="date" value="{{ old('date') }}" required
                                   placeholder="Click to select date"
                                   class="flatpickr-date w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue cursor-pointer
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
                                   value="{{ old('max_participants', 15) }}" min="1" max="200" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                          @error('max_participants') border-red-300 @enderror">
                            @error('max_participants')
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
                            <label for="equipment_needed" class="block text-sm font-medium text-gray-700 mb-1">Equipment Needed</label>
                            <textarea id="equipment_needed" name="equipment_needed" rows="2"
                                      placeholder="e.g., yoga mats, comfortable clothing, water bottle"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('equipment_needed') border-red-300 @enderror">{{ old('equipment_needed') }}</textarea>
                            @error('equipment_needed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="special_requirements" class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                            <textarea id="special_requirements" name="special_requirements" rows="2"
                                      placeholder="e.g., fitness level requirements, dietary restrictions"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('special_requirements') border-red-300 @enderror">{{ old('special_requirements') }}</textarea>
                            @error('special_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="preparation_notes" class="block text-sm font-medium text-gray-700 mb-1">Preparation Notes</label>
                            <textarea id="preparation_notes" name="preparation_notes" rows="2"
                                      placeholder="What participants should know or do before the session"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-aes-blue
                                             @error('preparation_notes') border-red-300 @enderror">{{ old('preparation_notes') }}</textarea>
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
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
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-aes-blue hover:bg-blue-700 text-white rounded-lg transition-colors font-medium shadow-md">
                        <i class="fas fa-save mr-2"></i>Create Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
