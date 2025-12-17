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
        <nav class="shadow-lg border-b" style="background-color: #000;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Mobile Navigation -->
                <div class="flex justify-between items-center h-16 md:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <img src="https://visitors.aes.ac.in/images/aes.png" alt="AES Admin" class="h-8 w-auto">
                    </a>
                    <button id="admin-mobile-menu-button" class="p-2 rounded-md text-white hover:text-gray-200 hover:bg-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                            <img src="https://visitors.aes.ac.in/images/aes.png" alt="AES Admin Panel" class="h-10 w-auto">
                        </a>
                        <span class="ml-4 px-3 py-1 text-xs font-medium bg-red-600 text-white rounded-full">
                            Admin
                        </span>
                    </div>

                    <div class="flex items-center space-x-4">
                        <nav class="space-x-4">
                            <a href="{{ route('admin.pl-wednesday.index') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.pl-wednesday.*') ? 'font-medium underline underline-offset-4' : '' }}">
                                PL Wednesday
                            </a>
                            <a href="{{ route('admin.pddays.index') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.pddays.*') ? 'font-medium underline underline-offset-4' : '' }}">
                                PL Days
                            </a>
                            <a href="{{ route('admin.wellness.index') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.wellness.*') ? 'font-medium underline underline-offset-4' : '' }}">
                                Wellness
                            </a>
                            <a href="{{ route('admin.schedule.index') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.schedule.*') ? 'font-medium underline underline-offset-4' : '' }}">
                                Schedule
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.users.*') ? 'font-medium underline underline-offset-4' : '' }}">
                                Users
                            </a>
                            <a href="{{ route('admin.reports') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.reports*') ? 'font-medium underline underline-offset-4' : '' }}">
                                Reports
                            </a>
                        </nav>

                        <div class="flex items-center space-x-2">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                            @endif
                            <span class="text-sm text-white hidden lg:inline">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-white hover:text-gray-200">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu (Hidden by default) -->
                <div id="admin-mobile-menu" class="hidden md:hidden" style="border-top: 1px solid rgba(255,255,255,0.2);">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="{{ route('admin.pl-wednesday.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-gray-800 rounded-md {{ request()->routeIs('admin.pl-wednesday.*') ? 'bg-gray-800' : '' }}">
                            PL Wednesday
                        </a>
                        <a href="{{ route('admin.pddays.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-gray-800 rounded-md {{ request()->routeIs('admin.pddays.*') ? 'bg-gray-800' : '' }}">
                            PL Days
                        </a>
                        <a href="{{ route('admin.wellness.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-gray-800 rounded-md {{ request()->routeIs('admin.wellness.*') ? 'bg-gray-800' : '' }}">
                            Wellness
                        </a>
                        <a href="{{ route('admin.schedule.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-gray-800 rounded-md {{ request()->routeIs('admin.schedule.*') ? 'bg-gray-800' : '' }}">
                            Schedule
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-gray-800 rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.reports') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-gray-800 rounded-md {{ request()->routeIs('admin.reports*') ? 'bg-gray-800' : '' }}">
                            Reports
                        </a>
                        <div class="pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
                            <div class="flex items-center px-3 py-2">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full mr-3">
                                @endif
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-400">Administrator</div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm text-white hover:text-gray-200">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Admin Mobile Menu Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('admin-mobile-menu-button');
                const mobileMenu = document.getElementById('admin-mobile-menu');
                
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                    });
                }
            });
        </script>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
