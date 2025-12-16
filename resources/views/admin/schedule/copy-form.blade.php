@extends('layouts.app')

@section('title', 'Copy Schedule - AES Professional Learning Days')

@section('content')
<main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <a href="{{ route('admin.schedule.by-pdday') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Schedule by PL Days
        </a>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Copy Schedule Items</h1>
        <p class="mt-2 text-gray-600">Copy schedule items from another PL day to {{ $pdDay->title }}</p>
    </div>

    <!-- Form -->
    <div class="max-w-2xl bg-white shadow-sm rounded-lg p-6">
        @if($sourcePdDays->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No other PL days with schedule items available to copy from.</p>
                <a href="{{ route('admin.schedule.by-pdday') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">
                    Back to Schedule
                </a>
            </div>
        @else
            <form method="POST" action="{{ route('admin.schedule.copy', $pdDay) }}">
                @csrf

                <!-- Target PL Day Info -->
                <div class="mb-6 p-4 bg-gray-100 border border-gray-200 rounded-lg">
                    <p class="text-sm font-medium text-gray-900">Target PL Day</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $pdDay->title }}</p>
                    <p class="text-sm text-gray-600">{{ $pdDay->date_range }}</p>
                </div>

                <!-- Source PL Day Selection -->
                <div class="mb-6">
                    <label for="source_pd_day_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Copy From <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="source_pd_day_id" 
                        id="source_pd_day_id"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 @error('source_pd_day_id') border-red-500 @else border-gray-300 @enderror"
                        required
                        onchange="updateSourceInfo()"
                    >
                        <option value="">Select a PL day to copy from...</option>
                        @foreach($sourcePdDays as $source)
                            <option value="{{ $source->id }}" data-schedule-count="{{ $source->scheduleItems()->count() }}">
                                {{ $source->title }} ({{ $source->date_range }}) - {{ $source->scheduleItems()->count() }} items
                            </option>
                        @endforeach
                    </select>
                    @error('source_pd_day_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Source Info Preview -->
                <div id="source-info" class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg hidden">
                    <p class="text-sm font-medium text-gray-900">Source Schedule Preview</p>
                    <p class="text-sm text-gray-600 mt-2">Will copy <strong id="item-count">0</strong> schedule items</p>
                </div>

                <!-- Info Box -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                All schedule items from the selected PD day will be copied with the same details. Links and divisions will also be copied.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.schedule.by-pdday') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Copy Schedule
                    </button>
                </div>
            </form>

            <script>
                function updateSourceInfo() {
                    const select = document.getElementById('source_pd_day_id');
                    const option = select.options[select.selectedIndex];
                    const sourceInfo = document.getElementById('source-info');
                    const itemCount = document.getElementById('item-count');

                    if (select.value) {
                        const count = option.getAttribute('data-schedule-count');
                        itemCount.textContent = count;
                        sourceInfo.classList.remove('hidden');
                    } else {
                        sourceInfo.classList.add('hidden');
                    }
                }
            </script>
        @endif
    </div>
</main>
@endsection
