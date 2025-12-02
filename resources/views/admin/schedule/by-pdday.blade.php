<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule by PD Days - AES Professional Learning Days</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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
                
                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-indigo-200 hover:text-white">Dashboard</a>
                        <a href="{{ route('admin.pddays.index') }}" class="text-indigo-200 hover:text-white">PD Days</a>
                        <a href="{{ route('admin.wellness.index') }}" class="text-indigo-200 hover:text-white">Wellness</a>
                        <a href="{{ route('admin.schedule.index') }}" class="text-indigo-200 hover:text-white">Schedule</a>
                        <a href="{{ route('admin.users.index') }}" class="text-indigo-200 hover:text-white">Users</a>
                        <a href="{{ route('admin.reports') }}" class="text-indigo-200 hover:text-white">Reports</a>
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
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Schedule by PD Days</h1>
                <p class="mt-2 text-gray-600">Manage schedule items grouped by PD day events</p>
            </div>
            <a href="{{ route('admin.schedule.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-black hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Schedule Item
            </a>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- PD Days Schedule Sections -->
        <div class="space-y-6">
            @forelse($pdDaysWithCounts as $item)
                @php
                    $pdDay = $item['pdDay'];
                    $scheduleCount = $item['scheduleCount'];
                    $scheduleItems = $item['scheduleItems'];
                @endphp
                
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <!-- PD Day Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-white">{{ $pdDay->title }}</h2>
                                <p class="text-indigo-100 text-sm">{{ $pdDay->date_range }}</p>
                                @if($pdDay->description)
                                    <p class="text-indigo-100 text-sm mt-1">{{ $pdDay->description }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 bg-white text-indigo-600 rounded-full text-sm font-semibold">
                                    {{ $scheduleCount }} items
                                </span>
                                @if($pdDay->is_active)
                                    <span class="inline-block ml-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        ✓ Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- PD Day Content -->
                    <div class="p-6">
                        @if($scheduleCount > 0)
                            <!-- Schedule Items List -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Schedule Items</h3>
                                <div class="space-y-3">
                                    @foreach($scheduleItems as $schedule)
                                        <div class="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="font-medium text-gray-900">{{ $schedule->title }}</p>
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded 
                                                        @if($schedule->session_type === 'Wellness')
                                                            bg-blue-100 text-blue-800
                                                        @else
                                                            bg-purple-100 text-purple-800
                                                        @endif
                                                    ">
                                                        {{ $schedule->session_type ?? 'Fixed' }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $schedule->location ?? 'No location' }}</p>
                                                @if($schedule->wellnessSession)
                                                    <p class="text-xs text-green-600 mt-1">
                                                        <strong>Linked to:</strong> {{ $schedule->wellnessSession->title }}
                                                        <span class="text-green-600">✓</span>
                                                    </p>
                                                @elseif($schedule->session_type === 'Wellness')
                                                    <p class="text-xs text-orange-600 mt-1">
                                                        <strong>⚠ Not linked to a wellness session</strong>
                                                    </p>
                                                @endif
                                                @if($schedule->presenter_primary)
                                                    <p class="text-xs text-gray-500 mt-1">by {{ $schedule->presenter_primary }}</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                @if($schedule->is_active)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                                @endif
                                                <a href="{{ route('admin.schedule.edit', $schedule) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.schedule.copy-form', $pdDay) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Copy Schedule
                                </a>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:400px">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No schedule items for this PD day yet</p>
                                <div class="mt-4 flex flex-col sm:flex-row justify-center gap-3">
                                    <a href="{{ route('admin.schedule.create', ['pd_day_id' => $pdDay->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create Manually
                                    </a>
                                    @if($pdDays->count() > 1)
                                        <a href="{{ route('admin.schedule.copy-form', $pdDay) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            Copy from Other PD Day
                                        </a>
                                    @endif
                                    <button type="button" onclick="document.getElementById('csv-form-{{ $pdDay->id }}').classList.toggle('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Upload CSV
                                    </button>
                                </div>
                            </div>

                            <!-- CSV Upload Form (Hidden by default) -->
                            <div id="csv-form-{{ $pdDay->id }}" class="hidden mt-6 p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <h4 class="font-semibold text-gray-900 mb-4">Upload Schedule from CSV</h4>
                                <form action="{{ route('admin.schedule.upload-csv', $pdDay) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="csv_file_{{ $pdDay->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                CSV File
                                            </label>
                                            <input 
                                                type="file" 
                                                name="csv_file" 
                                                id="csv_file_{{ $pdDay->id }}"
                                                accept=".csv,.txt"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                required
                                            >
                                            <p class="text-xs text-gray-500 mt-2">CSV should include columns: title, description, location, presenter_primary, session_type (Fixed/Wellness), date (YYYY-MM-DD), start_time (HH:mm), end_time (HH:mm)</p>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="document.getElementById('csv-form-{{ $pdDay->id }}').classList.add('hidden')" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                                                Cancel
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                                Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-sm rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No PD days configured yet. Create a PD day first.</p>
                    <a href="{{ route('admin.pddays.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Create PD Day
                    </a>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
