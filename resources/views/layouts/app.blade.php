<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AES Professional Learning Days')</title>
    
    <!-- Dark Mode Script - Respects system preference -->
    <script>
        // Check for saved theme preference or default to system preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const storedTheme = localStorage.getItem('theme');
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Pikaday Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AES Brand Colors -->
    <style>
        .bg-aes-blue { background-color: #1e40af; }
        .text-aes-blue { color: #1e40af; }
        .border-aes-blue { border-color: #1e40af; }
        .hover\:bg-aes-blue:hover { background-color: #1e40af; }
        .hover\:text-aes-blue:hover { color: #1e40af; }
        .focus\:ring-aes-blue:focus { --tw-ring-color: #1e40af; }
        
        /* Better contrast backgrounds */
        .bg-admin-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-content { background-color: #f8fafc; }
        .dark .bg-content { background-color: #111827; }
        .shadow-content { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .dark .shadow-content { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2); }
        .shadow-card { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .dark .shadow-card { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3); }
    </style>
</head>
<body class="font-sans antialiased bg-content dark:bg-gray-900 min-h-screen transition-colors duration-200">
    <!-- Admin Navigation Bar -->
    @if(auth()->check() && auth()->user()->is_admin)
        <nav class="bg-indigo-800 dark:bg-indigo-900 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-white text-lg font-bold">
                            🛠️ AES Admin Panel
                        </a>
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
                            <!-- Dark Mode Toggle -->
                            <button id="darkModeToggle" class="p-2 text-white hover:text-yellow-200 rounded-md transition-colors" title="Toggle dark mode">
                                <i class="fas fa-moon dark:hidden"></i>
                                <i class="fas fa-sun hidden dark:inline"></i>
                            </button>
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                            @endif
                            <span class="text-sm text-indigo-200">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-300 hover:text-red-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Pikaday JS -->
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.js"></script>
    
    <!-- Dark Mode Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }
    });
    </script>
    
    <!-- Initialize Date/Time Pickers -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date picker (calendar)
        document.querySelectorAll('.date-picker').forEach(function(el) {
            const picker = new Pikaday({
                field: el,
                format: 'YYYY-MM-DD',
                showDaysInNextAndPreviousMonths: true,
                enableSelectionDaysInNextAndPreviousMonths: true,
                onSelect: function(date) {
                    el.value = moment(date).format('YYYY-MM-DD');
                }
            });
            
            // Set initial value if exists
            if (el.value) {
                picker.setDate(moment(el.value, 'YYYY-MM-DD').toDate());
            }
        });
        
        // DateTime picker - split into date + time dropdowns
        document.querySelectorAll('.datetime-picker-group').forEach(function(group) {
            const dateInput = group.querySelector('.date-picker');
            const hourSelect = group.querySelector('.time-hour');
            const minuteSelect = group.querySelector('.time-minute');
            const ampmSelect = group.querySelector('.time-ampm');
            const hiddenInput = group.querySelector('input[type="hidden"]');
            
            // Initialize date picker
            const picker = new Pikaday({
                field: dateInput,
                format: 'YYYY-MM-DD',
                showDaysInNextAndPreviousMonths: true,
                enableSelectionDaysInNextAndPreviousMonths: true,
                onSelect: function(date) {
                    dateInput.value = moment(date).format('YYYY-MM-DD');
                    updateDateTime();
                }
            });
            
            // Set initial values
            if (hiddenInput && hiddenInput.value) {
                const dt = moment(hiddenInput.value, ['YYYY-MM-DD HH:mm', 'YYYY-MM-DD HH:mm:ss']);
                if (dt.isValid()) {
                    dateInput.value = dt.format('YYYY-MM-DD');
                    picker.setDate(dt.toDate());
                    
                    const hour24 = dt.hour();
                    const hour12 = hour24 % 12 || 12;
                    // Round minute to nearest 5-minute increment
                    const roundedMinute = Math.round(dt.minute() / 5) * 5;
                    hourSelect.value = hour12.toString().padStart(2, '0');
                    minuteSelect.value = roundedMinute.toString().padStart(2, '0');
                    ampmSelect.value = hour24 >= 12 ? 'PM' : 'AM';
                }
            }
            
            // Update hidden input when time changes
            function updateDateTime() {
                if (!dateInput.value) return;
                
                const hour12 = parseInt(hourSelect.value);
                const minute = parseInt(minuteSelect.value);
                const ampm = ampmSelect.value;
                const hour24 = ampm === 'PM' && hour12 !== 12 ? hour12 + 12 : (ampm === 'AM' && hour12 === 12 ? 0 : hour12);
                
                const datetime = moment(dateInput.value + ' ' + hour24.toString().padStart(2, '0') + ':' + minute.toString().padStart(2, '0'), 'YYYY-MM-DD HH:mm');
                hiddenInput.value = datetime.format('YYYY-MM-DD HH:mm');
            }
            
            [hourSelect, minuteSelect, ampmSelect].forEach(function(select) {
                select.addEventListener('change', updateDateTime);
            });
        });
        
        // Time picker - dropdowns only
        document.querySelectorAll('.time-picker-group').forEach(function(group) {
            const hourSelect = group.querySelector('.time-hour');
            const minuteSelect = group.querySelector('.time-minute');
            const ampmSelect = group.querySelector('.time-ampm');
            const hiddenInput = group.querySelector('input[type="hidden"]');
            
            // Set initial values
            if (hiddenInput && hiddenInput.value) {
                // Handle both 'HH:mm' and 'YYYY-MM-DD HH:mm' formats
                const timeStr = hiddenInput.value.includes(' ') ? hiddenInput.value.split(' ')[1] : hiddenInput.value;
                const [hour24, minute] = timeStr.split(':').map(Number);
                const hour12 = hour24 % 12 || 12;
                // Round minute to nearest 5-minute increment
                const roundedMinute = Math.round(minute / 5) * 5;
                hourSelect.value = hour12.toString().padStart(2, '0');
                minuteSelect.value = roundedMinute.toString().padStart(2, '0');
                ampmSelect.value = hour24 >= 12 ? 'PM' : 'AM';
            }
            
            // Update hidden input when time changes
            function updateTime() {
                const hour12 = parseInt(hourSelect.value);
                const minute = parseInt(minuteSelect.value);
                const ampm = ampmSelect.value;
                const hour24 = ampm === 'PM' && hour12 !== 12 ? hour12 + 12 : (ampm === 'AM' && hour12 === 12 ? 0 : hour12);
                
                hiddenInput.value = hour24.toString().padStart(2, '0') + ':' + minute.toString().padStart(2, '0');
            }
            
            [hourSelect, minuteSelect, ampmSelect].forEach(function(select) {
                select.addEventListener('change', updateTime);
            });
        });
    });
    </script>
    
    <!-- Custom Picker Styles -->
    <style>
        .pika-single {
            z-index: 9999 !important;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .dark .pika-single {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
        }
        .pika-button:hover {
            background: #1e40af !important;
            color: white !important;
        }
        .pika-day.is-selected {
            background: #1e40af !important;
            color: white !important;
        }
        .dark .pika-day {
            color: #f9fafb !important;
        }
        .dark .pika-day:hover {
            background: #374151 !important;
        }
        .time-picker-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .time-picker-group select {
            flex: 1;
            min-width: 0;
        }
        .datetime-picker-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .datetime-picker-group .date-picker {
            flex: 2;
            min-width: 0;
        }
        .datetime-picker-group select {
            flex: 1;
            min-width: 0;
        }
    </style>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
