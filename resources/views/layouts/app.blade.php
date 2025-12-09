<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AES Professional Learning Days')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Flatpickr Date/Time Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    
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
        .shadow-content { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .shadow-card { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="font-sans antialiased bg-content min-h-screen">
    <!-- Admin Navigation Bar -->
    @if(auth()->check() && auth()->user()->is_admin)
        <nav class="bg-indigo-800 shadow-lg">
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

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Initialize Flatpickr for all date/time inputs -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // DateTime picker (calendar + time)
        document.querySelectorAll('.flatpickr-datetime').forEach(function(el) {
            flatpickr(el, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "F j, Y at h:i K",
                time_24hr: false,
                minuteIncrement: 5,
                allowInput: false,
                clickOpens: true,
                disableMobile: false,
                onReady: function(selectedDates, dateStr, instance) {
                    // Add calendar icon
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';
                    instance.input.parentNode.insertBefore(wrapper, instance.input);
                    wrapper.appendChild(instance.input);
                    if (instance.altInput) {
                        wrapper.appendChild(instance.altInput);
                        instance.altInput.classList.add('pl-10');
                    }
                    const icon = document.createElement('span');
                    icon.className = 'absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none';
                    icon.innerHTML = '<i class="fas fa-calendar-alt"></i>';
                    wrapper.appendChild(icon);
                }
            });
        });
        
        // Date-only picker (calendar only)
        document.querySelectorAll('.flatpickr-date').forEach(function(el) {
            flatpickr(el, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: false,
                clickOpens: true,
                disableMobile: false,
                onReady: function(selectedDates, dateStr, instance) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';
                    instance.input.parentNode.insertBefore(wrapper, instance.input);
                    wrapper.appendChild(instance.input);
                    if (instance.altInput) {
                        wrapper.appendChild(instance.altInput);
                        instance.altInput.classList.add('pl-10');
                    }
                    const icon = document.createElement('span');
                    icon.className = 'absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none';
                    icon.innerHTML = '<i class="fas fa-calendar-alt"></i>';
                    wrapper.appendChild(icon);
                }
            });
        });
        
        // Time-only picker (clock only)
        document.querySelectorAll('.flatpickr-time').forEach(function(el) {
            flatpickr(el, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                altInput: true,
                altFormat: "h:i K",
                time_24hr: false,
                minuteIncrement: 5,
                allowInput: false,
                clickOpens: true,
                disableMobile: false,
                onReady: function(selectedDates, dateStr, instance) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';
                    instance.input.parentNode.insertBefore(wrapper, instance.input);
                    wrapper.appendChild(instance.input);
                    if (instance.altInput) {
                        wrapper.appendChild(instance.altInput);
                        instance.altInput.classList.add('pl-10');
                    }
                    const icon = document.createElement('span');
                    icon.className = 'absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none';
                    icon.innerHTML = '<i class="fas fa-clock"></i>';
                    wrapper.appendChild(icon);
                }
            });
        });
    });
    </script>
    
    <!-- Custom Flatpickr Styles -->
    <style>
        /* Make flatpickr inputs look consistent */
        .flatpickr-input[readonly] {
            background-color: white !important;
            cursor: pointer;
        }
        .flatpickr-alt-input {
            cursor: pointer !important;
        }
        /* Better time picker styling */
        .flatpickr-time {
            max-height: none !important;
        }
        .flatpickr-time input.flatpickr-hour,
        .flatpickr-time input.flatpickr-minute {
            font-size: 1.25rem !important;
        }
        /* Calendar hover effects */
        .flatpickr-day:hover {
            background: #1e40af !important;
            border-color: #1e40af !important;
            color: white !important;
        }
        .flatpickr-day.selected {
            background: #1e40af !important;
            border-color: #1e40af !important;
        }
    </style>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
