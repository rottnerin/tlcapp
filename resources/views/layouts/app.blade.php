<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AES Professional Learning Days')</title>
    
    <!-- Fonts - Match Dashboard -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Flatpickr Date/Time Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AES Brand Colors & Admin Styles -->
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .gradient-header { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); }
        .bg-aes-blue { background-color: #1e40af; }
        .text-aes-blue { color: #1e40af; }
        .border-aes-blue { border-color: #1e40af; }
        .hover\:bg-aes-blue:hover { background-color: #1e40af; }
        .hover\:text-aes-blue:hover { color: #1e40af; }
        .focus\:ring-aes-blue:focus { --tw-ring-color: #1e40af; }
        .bg-content { background-color: #f1f5f9; }
        .shadow-card { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
        .card { background: #ffffff; border: 1px solid #e5e7eb; }
    </style>
</head>
<body style="background: #f1f5f9; min-height: 100vh; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
    <!-- Admin Navigation Bar - Matches Dashboard -->
    @if(auth()->check() && auth()->user()->is_admin)
        <nav class="gradient-header shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                            <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-white text-lg"></i>
                            </div>
                            <span class="text-white text-lg font-semibold tracking-tight">AES Admin</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-1">
                        <nav class="hidden md:flex items-center space-x-1">
                            <a href="{{ route('admin.schedule.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.schedule.*') ? 'text-white bg-white/10' : '' }}">
                                <i class="fas fa-calendar-alt mr-3 text-xs"></i>Schedule
                            </a>
                            <a href="{{ route('admin.wellness.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.wellness.*') ? 'text-white bg-white/10' : '' }}">
                                <i class="fas fa-heart mr-3 text-xs"></i>Wellness
                            </a>
                            <a href="{{ route('admin.pddays.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.pddays.*') ? 'text-white bg-white/10' : '' }}">
                                <i class="fas fa-calendar-check mr-3 text-xs"></i>PL Days
                            </a>
                            <a href="{{ route('admin.pl-wednesday.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.pl-wednesday.*') ? 'text-white bg-white/10' : '' }}">
                                <i class="fas fa-book mr-3 text-xs"></i>PL Wed
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'text-white bg-white/10' : '' }}">
                                <i class="fas fa-users mr-3 text-xs"></i>Users
                            </a>
                            <a href="{{ route('admin.reports') }}" 
                               class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all {{ request()->routeIs('admin.reports*') ? 'text-white bg-white/10' : '' }}">
                                <i class="fas fa-chart-bar mr-3 text-xs"></i>Reports
                            </a>
                        </nav>
                        
                        <div class="flex items-center space-x-3 ml-4 pl-4 border-l border-white/20">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full ring-2 ring-white/20">
                            @else
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-400 rounded-lg hover:bg-white/10 transition-all" title="Logout">
                                    <i class="fas fa-sign-out-alt text-sm"></i>
                                </button>
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
    
    <!-- Custom Picker Styles -->
    <style>
        /* Flatpickr Custom Styling */
        .flatpickr-calendar {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid #e2e8f0;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            overflow: visible !important;
            z-index: 9999 !important;
        }
        
        .flatpickr-calendar.open {
            z-index: 9999 !important;
        }
        
        .flatpickr-months {
            background: #2563eb;
            border-radius: 12px 12px 0 0;
            padding: 12px;
        }
        
        .flatpickr-month {
            color: white;
        }
        
        .flatpickr-current-month {
            color: white;
            font-weight: 600;
        }
        
        .flatpickr-prev-month,
        .flatpickr-next-month {
            color: white;
            fill: white;
        }
        
        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }
        
        .flatpickr-weekdays {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .flatpickr-weekday {
            color: #64748b;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .flatpickr-day {
            border-radius: 6px;
            color: #1e293b;
            font-weight: 500;
        }
        
        .flatpickr-day:hover {
            background: #e0e7ff;
            border-color: #e0e7ff;
        }
        
        .flatpickr-day.selected {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
            font-weight: 600;
        }
        
        .flatpickr-day.today {
            border-color: #2563eb;
            color: #2563eb;
            font-weight: 600;
        }
        
        .flatpickr-day.today.selected {
            background: #2563eb;
            color: white;
        }
        
        .flatpickr-time {
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 12px;
            border-radius: 0 0 12px 12px;
            overflow: visible !important;
        }
        
        .flatpickr-time .flatpickr-time-wrapper {
            overflow: visible !important;
        }
        
        .flatpickr-time input {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            font-weight: 500;
        }
        
        .flatpickr-time input:hover {
            background: #e0e7ff;
        }
        
        .flatpickr-time .flatpickr-am-pm {
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            font-weight: 500;
        }
        
        .flatpickr-time .flatpickr-am-pm:hover {
            background: #e0e7ff;
        }
        
        .flatpickr-input {
            cursor: pointer;
        }
        
        /* Ensure parent containers don't clip the calendar */
        .form-input,
        .w-full,
        .card,
        .rounded-2xl {
            overflow: visible !important;
        }
        
        /* Ensure form sections don't clip */
        .p-6,
        .space-y-6 > div {
            overflow: visible !important;
        }
        
        /* Fix for time picker being cut off */
        .flatpickr-calendar.animate.open {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        .flatpickr-calendar .flatpickr-time {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 8px;
            min-height: 60px;
        }
        
        /* Ensure calendar wrapper doesn't clip */
        .flatpickr-wrapper {
            overflow: visible !important;
        }
        
        /* Fix for any parent overflow issues */
        .card,
        .form-input {
            position: relative;
        }
        
        /* Ensure time inputs are fully visible */
        .flatpickr-time input.flatpickr-hour,
        .flatpickr-time input.flatpickr-minute {
            padding: 8px;
            margin: 0 4px;
            min-width: 50px;
        }
        
        .flatpickr-time .flatpickr-am-pm {
            padding: 8px 12px;
            margin: 0 4px;
        }
    </style>
    
    <!-- Initialize Flatpickr -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for date-only inputs
        document.querySelectorAll('.flatpickr-date').forEach(function(el) {
            flatpickr(el, {
                dateFormat: "Y-m-d",
                allowInput: false,
                clickOpens: true,
                defaultDate: el.value || new Date()
            });
        });
        
        // Initialize Flatpickr for date-time inputs
        document.querySelectorAll('.flatpickr-datetime').forEach(function(el) {
            const defaultDate = el.value ? new Date(el.value.replace(' ', 'T')) : new Date();
            const picker = flatpickr(el, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: false,
                defaultDate: defaultDate,
                minuteIncrement: 5,
                allowInput: false,
                clickOpens: true
            });
            
            // Ensure end time is after start time (for schedule forms)
            if (el.id === 'start_time' && document.getElementById('end_time')) {
                picker.config.onChange.push(function(selectedDates) {
                    if (selectedDates.length > 0) {
                        const endPicker = flatpickr('#end_time');
                        const startDate = selectedDates[0];
                        const endDate = endPicker.selectedDates[0];
                        
                        if (endDate && endDate <= startDate) {
                            const newEndDate = new Date(startDate);
                            newEndDate.setHours(newEndDate.getHours() + 1);
                            endPicker.setDate(newEndDate);
                        }
                        endPicker.set('minDate', startDate);
                    }
                });
            }
        });
        
        // Initialize Flatpickr for time-only inputs
        document.querySelectorAll('.flatpickr-time').forEach(function(el) {
            // Parse time value (HH:mm format)
            let defaultTime = '15:00';
            if (el.value) {
                defaultTime = el.value;
            }
            
            // Create a date object with today's date and the specified time
            const [hours, minutes] = defaultTime.split(':').map(Number);
            const defaultDate = new Date();
            defaultDate.setHours(hours, minutes, 0, 0);
            
            flatpickr(el, {
                noCalendar: true,
                enableTime: true,
                dateFormat: "H:i",
                time_24hr: true,
                defaultDate: defaultDate,
                minuteIncrement: 5,
                allowInput: false,
                clickOpens: true
            });
        });
        
        // Fix for time picker being cut off
        setTimeout(function() {
            const calendars = document.querySelectorAll('.flatpickr-calendar');
            calendars.forEach(function(cal) {
                cal.style.overflow = 'visible';
                const timeSection = cal.querySelector('.flatpickr-time');
                if (timeSection) {
                    timeSection.style.overflow = 'visible';
                    timeSection.style.paddingBottom = '12px';
                }
            });
        }, 100);
    });
    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
