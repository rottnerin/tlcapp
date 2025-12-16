@extends('layouts.app')

@section('title', 'Edit PL Day - AES Professional Learning Days')

@section('content')
<main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('admin.pddays.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to PL Days
        </a>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Edit PL Day</h1>
        <p class="mt-2 text-gray-600">Update professional learning day event details</p>
    </div>

    <!-- Form -->
    <div class="max-w-3xl bg-white shadow-sm rounded-lg p-6">
        <form method="POST" action="{{ route('admin.pddays.update', $pdday) }}">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $pdday->title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 @error('title') border-red-500 @enderror"
                    placeholder="e.g., September 2025 Professional Learning Days"
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 @error('description') border-red-500 @enderror"
                    placeholder="Optional description for this PD day event..."
                >{{ old('description', $pdday->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Range -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="start_date" 
                        value="{{ old('start_date', $pdday->start_date->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 @error('start_date') border-red-500 @enderror"
                        required
                    >
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        End Date <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="end_date" 
                        id="end_date" 
                        value="{{ old('end_date', $pdday->end_date->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 @error('end_date') border-red-500 @enderror"
                        required
                    >
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Status -->
            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            value="1"
                            {{ old('is_active', $pdday->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded"
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">
                            Set as Active PL Day
                        </label>
                        <p class="text-gray-500">If checked, this will become the active PL day and all others will be deactivated.</p>
                    </div>
                </div>
            </div>

            <!-- Associated Sessions Info -->
            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-md p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Associated Sessions</h3>
                <div class="text-sm text-gray-600">
                    <p>Schedule Items: <span class="font-semibold">{{ $pdday->scheduleItems()->count() }}</span></p>
                    <p>Wellness Sessions: <span class="font-semibold">{{ $pdday->wellnessSessions()->count() }}</span></p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.pddays.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Update PL Day
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
