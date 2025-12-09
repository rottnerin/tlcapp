<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create PL Day - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Admin Navigation -->
    <nav class="bg-indigo-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-white text-lg font-bold">🛠️ AES Admin Panel</span>
                    <span class="ml-4 px-3 py-1 text-xs font-medium bg-yellow-400 text-yellow-900 rounded-full">
                        Administrator
                    </span>
                </div>
                
                <div class="flex items-center space-x-2 flex-1 justify-end">
                    <nav class="flex items-center space-x-2 flex-wrap">
                        <a href="{{ route('admin.pl-wednesday.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.pl-wednesday.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            PL Wednesday
                        </a>
                        <a href="{{ route('admin.pddays.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.pddays.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            PL Days
                        </a>
                        <a href="{{ route('admin.wellness.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.wellness.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Wellness
                        </a>
                        <a href="{{ route('admin.schedule.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.schedule.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Schedule
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="px-3 py-2 text-sm font-semibold text-white hover:text-yellow-200 hover:bg-indigo-700 rounded transition-colors whitespace-nowrap {{ request()->routeIs('admin.reports*') ? 'text-yellow-200 bg-indigo-700 border-b-2 border-yellow-200' : '' }}">
                            Reports
                        </a>
                    </nav>
                    
                    <div class="flex items-center space-x-2">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                        @endif
                        <span class="text-sm text-indigo-200">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-300 hover:text-red-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <a href="{{ route('admin.pddays.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                ← Back to PL Days
            </a>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">Create New PL Day</h1>
            <p class="mt-2 text-gray-600">Configure a new professional learning day event</p>
        </div>

        <!-- Form -->
        <div class="max-w-3xl bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.pddays.store') }}">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                        placeholder="Optional description for this PL day event..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Range -->
                <div class="mb-6 grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>Start Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="start_date" 
                            id="start_date" 
                            value="{{ old('start_date') }}"
                            placeholder="Click to select date"
                            class="flatpickr-date w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer @error('start_date') border-red-500 @enderror"
                            required
                        >
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>End Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="end_date" 
                            id="end_date" 
                            value="{{ old('end_date') }}"
                            placeholder="Click to select date"
                            class="flatpickr-date w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer @error('end_date') border-red-500 @enderror"
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
                                {{ old('is_active') ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
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
                                After creating the PL Day, you can assign schedule items and wellness sessions to it from their respective management pages.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.pddays.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-black hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create PL Day
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.flatpickr-date').forEach(function(el) {
            flatpickr(el, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: false,
                clickOpens: true
            });
        });
    });
    </script>
    <style>
        .flatpickr-input[readonly] { background-color: white !important; cursor: pointer; }
        .flatpickr-day:hover { background: #4f46e5 !important; border-color: #4f46e5 !important; color: white !important; }
        .flatpickr-day.selected { background: #4f46e5 !important; border-color: #4f46e5 !important; }
    </style>
</body>
</html>
