@extends('layouts.app')

@section('title', 'Edit PL Day')

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
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.pddays.index') }}" 
               class="inline-flex items-center text-sm font-medium mb-4" style="color: #2563eb;">
                <i class="fas fa-arrow-left mr-2"></i>Back to PL Days
            </a>
            <h1 class="text-2xl font-bold" style="color: #1e293b;">Edit PL Day</h1>
            <p class="mt-1" style="color: #64748b;">Update professional learning day event details</p>
        </div>

        <!-- Form Card -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('admin.pddays.update', $pdday) }}">
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
                        <input type="text" name="title" id="title" value="{{ old('title', $pdday->title) }}" required
                               placeholder="e.g., September 2025 Professional Learning Days"
                               class="w-full rounded-lg px-4 py-2.5 form-input @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium form-label mb-1">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  placeholder="Optional description for this PL day event..."
                                  class="w-full rounded-lg px-4 py-2.5 form-input @error('description') border-red-500 @enderror">{{ old('description', $pdday->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date Range -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-calendar-alt mr-2" style="color: #64748b;"></i>Date Range
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium form-label mb-1">
                                Start Date <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" name="start_date" id="start_date" value="{{ old('start_date', $pdday->start_date->format('Y-m-d')) }}" required
                                   placeholder="Click to select date"
                                   class="date-picker w-full rounded-lg px-4 py-2.5 form-input cursor-pointer @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium form-label mb-1">
                                End Date <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="text" name="end_date" id="end_date" value="{{ old('end_date', $pdday->end_date->format('Y-m-d')) }}" required
                                   placeholder="Click to select date"
                                   class="date-picker w-full rounded-lg px-4 py-2.5 form-input cursor-pointer @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="p-6 section-title">
                    <h2 class="text-lg font-semibold" style="color: #1e293b;">
                        <i class="fas fa-cog mr-2" style="color: #64748b;"></i>Settings
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $pdday->is_active) ? 'checked' : '' }}
                               class="h-5 w-5 rounded mt-0.5" style="accent-color: #2563eb;">
                        <div>
                            <span class="font-medium" style="color: #1e293b;">Set as Active PL Day</span>
                            <p class="text-sm" style="color: #64748b;">If checked, this will become the active PL day and all others will be deactivated.</p>
                        </div>
                    </label>

                    <!-- Associated Sessions Info -->
                    <div class="p-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <h3 class="text-sm font-semibold mb-2" style="color: #1e293b;">
                            <i class="fas fa-link mr-2" style="color: #64748b;"></i>Associated Sessions
                        </h3>
                        <div class="text-sm grid grid-cols-2 gap-4" style="color: #64748b;">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2" style="color: #7c3aed;"></i>
                                Schedule Items: <span class="font-semibold ml-1" style="color: #1e293b;">{{ $pdday->scheduleItems()->count() }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-heart mr-2" style="color: #059669;"></i>
                                Wellness Sessions: <span class="font-semibold ml-1" style="color: #1e293b;">{{ $pdday->wellnessSessions()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="p-6 flex justify-end space-x-4" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('admin.pddays.index') }}" 
                       class="px-6 py-2.5 rounded-lg font-medium transition-colors"
                       style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569;">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-lg font-medium text-white transition-colors"
                            style="background: #2563eb;">
                        <i class="fas fa-save mr-2"></i>Update PL Day
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
