<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
    
    <!-- Dark Mode Script -->
    <script>
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const storedTheme = localStorage.getItem('theme');
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- AES Brand Styles -->
    <style>
        .aes-primary { background: linear-gradient(135deg, #4CAF50 0%, #2196F3 50%, #FF9800 100%); }
        .aes-bg { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .dark .aes-bg { background: linear-gradient(135deg, #1f2937 0%, #111827 100%); }
        .division-es { border-left: 4px solid #4CAF50; }
        .division-ms { border-left: 4px solid #2196F3; }
        .division-hs { border-left: 4px solid #FF9800; }
        .session-card { transition: all 0.3s ease; }
        .session-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .dark .session-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.4); }
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 aes-bg transition-colors duration-200">
    <!-- Navigation -->
    <nav class="shadow-lg border-b" style="background-color: #000;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Navigation -->
            <div class="flex justify-between items-center h-16 md:hidden">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="https://visitors.aes.ac.in/images/aes.png" alt="AES Professional Learning" class="h-8 w-auto">
                </a>
                <button id="mobile-menu-button" class="p-2 rounded-md text-white hover:text-gray-200 hover:bg-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="https://visitors.aes.ac.in/images/aes.png" alt="AES Professional Learning Days" class="h-10 w-auto">
                    </a>
                    @if(auth()->check() && auth()->user()->division)
                        <span class="ml-4 px-3 py-1 text-xs font-medium bg-{{ strtolower(auth()->user()->division->name) === 'es' ? 'green' : (strtolower(auth()->user()->division->name) === 'ms' ? 'blue' : 'orange') }}-100 text-{{ strtolower(auth()->user()->division->name) === 'es' ? 'green' : (strtolower(auth()->user()->division->name) === 'ms' ? 'blue' : 'orange') }}-800 rounded-full">
                            {{ auth()->user()->division->full_name }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        @if($plDaysActive ?? true)
                            <a href="{{ route('dashboard') }}"
                               class="text-white hover:text-gray-200 {{ request()->routeIs('dashboard') || request()->routeIs('schedule.*') ? 'text-white font-medium' : '' }}">
                                Schedule
                            </a>
                        @endif
                        @if($wellnessActive ?? true)
                            <a href="{{ route('wellness.index') }}"
                               class="text-white hover:text-gray-200 {{ request()->routeIs('wellness.*') ? 'text-white font-medium' : '' }}">
                                Wellness
                            </a>
                        @endif
                        @if($plWednesdayActive ?? false)
                            <a href="{{ route('pl-wednesday.index') }}"
                               class="text-white hover:text-gray-200 {{ request()->routeIs('pl-wednesday.*') ? 'text-white font-medium' : '' }}">
                                PL Wednesday
                            </a>
                        @endif
                        <a href="https://docs.google.com/document/d/1m08jPdge3v_A1ZTUZqlkzcC0P13LY2HnQ0rtA4_uJn4/edit?usp=sharing"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-white hover:text-gray-200">
                            Truman Group
                        </a>
                    </nav>

                    <div class="flex items-center space-x-2">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 text-white hover:text-gray-200 rounded-md transition-colors" title="Toggle dark mode">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:inline"></i>
                        </button>
                        @if(auth()->check() && auth()->user()->avatar)
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
            <div id="mobile-menu" class="hidden md:hidden" style="border-top: 1px solid rgba(255,255,255,0.2);">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    @if($plDaysActive ?? true)
                        <a href="{{ route('dashboard') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-yellow-600 rounded-md {{ request()->routeIs('dashboard') || request()->routeIs('schedule.*') ? 'text-white bg-yellow-600' : '' }}">
                            Schedule
                        </a>
                    @endif
                    @if($wellnessActive ?? true)
                        <a href="{{ route('wellness.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-yellow-600 rounded-md {{ request()->routeIs('wellness.*') ? 'text-white bg-yellow-600' : '' }}">
                            Wellness
                        </a>
                    @endif
                    @if($plWednesdayActive ?? false)
                        <a href="{{ route('pl-wednesday.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-yellow-600 rounded-md {{ request()->routeIs('pl-wednesday.*') ? 'text-white bg-yellow-600' : '' }}">
                            PL Wednesday
                        </a>
                    @endif
                    <a href="https://docs.google.com/document/d/1m08jPdge3v_A1ZTUZqlkzcC0P13LY2HnQ0rtA4_uJn4/edit?usp=sharing"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-yellow-600 rounded-md">
                        Truman Group
                    </a>
                    <div class="pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
                        <div class="flex items-center px-3 py-2">
                            @if(auth()->check() && auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full mr-3">
                            @endif
                            <div class="flex-1">
                                <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                                @if(auth()->check() && auth()->user()->division)
                                    <div class="text-xs text-gray-200">{{ auth()->user()->division->full_name }}</div>
                                @endif
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

    <!-- Mobile Menu & Dark Mode Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
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

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>