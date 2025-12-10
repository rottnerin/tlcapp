@extends('layouts.app')

@section('title', 'Create Schedule Item')

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
            <h1 class="text-2xl font-bold" style="color: #1e293b;">Create New Schedule Item</h1>
            <p class="mt-1" style="color: #64748b;">Add a new item to the professional development schedule</p>
        </div>

        <!-- Form Card -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.schedule.store') }}" method="POST">
                @csrf

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
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full rounded-lg px-4 py-2.5 form-input @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium form-label mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="division_id" class="block text-sm font-medium form-label mb-1">
                                Division <span style="color: #dc2626;">*</span>
                            </label>
                            <select id="division_id" name="division_id" required
                                    class="w-full rounded-lg px-4 py-2.5 form-input @error('division_id') border-red-500 @enderror">
                                <option value="">Select division...</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division_id')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pd_day_id" class="block text-sm font-medium form-label mb-1">PL Day Event</label>
                            <select id="pd_day_id" name="pd_day_id"
                                    class="w-full rounded-lg px-4 py-2.5 form-input @error('pd_day_id') border-red-500 @enderror">
                                <option value="">Not assigned to any PL Day</option>
                                @foreach($pdDays as $pdDay)
                                    <option value="{{ $pdDay->id }}" {{ old('pd_day_id') == $pdDay->id ? 'selected' : '' }}>
                                        {{ $pdDay->title }} ({{ $pdDay->date_range }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pd_day_id')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="presenter_name" class="block text-sm font-medium form-label mb-1">Presenter Name</label>
                            <input type="text" id="presenter_name" name="presenter_name" value="{{ old('presenter_name') }}"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('presenter_name') border-red-500 @enderror">
                            @error('presenter_name')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium form-label mb-1">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                   placeholder="e.g., Auditorium, Gym, Library"
                                   class="w-full rounded-lg px-4 py-2.5 form-input @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="session_type" class="block text-sm font-medium form-label mb-1">Session Type</label>
                            <select id="session_type" name="session_type"
                                    class="w-full rounded-lg px-4 py-2.5 form-input @error('session_type') border-red-500 @enderror">
                                <option value="general" {{ old('session_type') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="workshop" {{ old('session_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="keynote" {{ old('session_type') == 'keynote' ? 'selected' : '' }}>Keynote</option>
                                <option value="breakout" {{ old('session_type') == 'breakout' ? 'selected' : '' }}>Breakout</option>
                            </select>
                            @error('session_type')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium form-label mb-1">Color Tag</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" id="color" name="color" value="{{ old('color', '#3b82f6') }}"
                                       class="h-10 w-16 rounded cursor-pointer" style="border: 1px solid #e2e8f0;">
                                <span class="text-sm" style="color: #64748b;">Choose a color to identify this item</span>
                            </div>
                            @error('color')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
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
                            <div class="datetime-picker-group @error('start_time') border-red-500 @enderror">
                                <input type="hidden" name="start_time" id="start_time_hidden" value="{{ old('start_time') }}" required>
                                <input type="text" id="start_time_date" 
                                       placeholder="Select date"
                                       class="date-picker w-full rounded-lg px-4 py-2.5 form-input cursor-pointer">
                                <div class="flex space-x-2 mt-2">
                                    <select class="time-hour flex-1 rounded-lg px-3 py-2.5 form-input">
                                        @for($h = 1; $h <= 12; $h++)
                                            <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">{{ $h }}</option>
                                        @endfor
                                    </select>
                                    <select class="time-minute flex-1 rounded-lg px-3 py-2.5 form-input">
                                        @for($m = 0; $m < 60; $m += 5)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <select class="time-ampm flex-1 rounded-lg px-3 py-2.5 form-input">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                            @error('start_time')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium form-label mb-1">
                                End Date & Time <span style="color: #dc2626;">*</span>
                            </label>
                            <div class="datetime-picker-group @error('end_time') border-red-500 @enderror">
                                <input type="hidden" name="end_time" id="end_time_hidden" value="{{ old('end_time') }}" required>
                                <input type="text" id="end_time_date" 
                                       placeholder="Select date"
                                       class="date-picker w-full rounded-lg px-4 py-2.5 form-input cursor-pointer">
                                <div class="flex space-x-2 mt-2">
                                    <select class="time-hour flex-1 rounded-lg px-3 py-2.5 form-input">
                                        @for($h = 1; $h <= 12; $h++)
                                            <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}">{{ $h }}</option>
                                        @endfor
                                    </select>
                                    <select class="time-minute flex-1 rounded-lg px-3 py-2.5 form-input">
                                        @for($m = 0; $m < 60; $m += 5)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <select class="time-ampm flex-1 rounded-lg px-3 py-2.5 form-input">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                            @error('end_time')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-sm" style="color: #64748b;">
                        <i class="fas fa-info-circle mr-1"></i>Click the date field to open calendar, use dropdowns for time
                    </p>
                </div>

                <!-- Settings -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-cog mr-2" style="color: #64748b;"></i>Settings
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" id="is_required" name="is_required" value="1"
                               {{ old('is_required') ? 'checked' : '' }}
                               class="h-5 w-5 rounded" style="accent-color: #2563eb;">
                        <div>
                            <span class="font-medium" style="color: #1e293b;">Required Session</span>
                            <p class="text-sm" style="color: #64748b;">This is a required item (mandatory attendance)</p>
                        </div>
                    </label>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded" style="accent-color: #2563eb;">
                        <div>
                            <span class="font-medium" style="color: #1e293b;">Active</span>
                            <p class="text-sm" style="color: #64748b;">Item is active and visible in schedule</p>
                        </div>
                    </label>
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
                        <i class="fas fa-save mr-2"></i>Create Schedule Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
