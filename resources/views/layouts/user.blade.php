<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TLC Professional Learning')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- TLC Brand Styles -->
    <style>
        :root {
            --tlc-navy: #0d3b66;
            --tlc-cream: #faf0ca;
            --tlc-gold: #f4d35e;
            --tlc-orange: #ee964b;
        }
        body {
            font-family: 'Lexend', ui-sans-serif, system-ui, sans-serif;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }
        
        .tlc-bg { background-color: var(--tlc-cream); }
        .bg-tlc-navy { background-color: var(--tlc-navy); }
        .bg-tlc-cream { background-color: var(--tlc-cream); }
        .bg-tlc-gold { background-color: var(--tlc-gold); }
        .bg-tlc-orange { background-color: var(--tlc-orange); }
        .text-tlc-navy { color: var(--tlc-navy); }
        .text-tlc-cream { color: var(--tlc-cream); }
        .text-tlc-gold { color: var(--tlc-gold); }
        .text-tlc-orange { color: var(--tlc-orange); }
        
        .division-es { border-left: 4px solid var(--tlc-gold); }
        .division-ms { border-left: 4px solid var(--tlc-orange); }
        .division-hs { border-left: 4px solid var(--tlc-navy); }
        .session-card { transition: all 0.2s ease; }
        @media (hover: hover) {
            .session-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(13, 59, 102, 0.15); }
        }
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        /* Custom text selection styling */
        ::selection {
            background-color: var(--tlc-gold);
            color: var(--tlc-navy);
        }

        /* Disable user-select on interactive elements */
        button, a[role="button"], [onclick] {
            user-select: none;
        }

        /* Main Navigation Styles */
        .main-nav-item {
            position: relative;
            padding: 0.75rem 1.25rem;
            color: var(--tlc-cream);
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 0.5rem 0.5rem 0 0;
            display: inline-block;
            margin-bottom: 0;
            user-select: none;
        }
        @media (hover: hover) {
            .main-nav-item:hover {
                color: var(--tlc-gold);
                background: rgba(244, 211, 94, 0.1);
            }
        }
        .main-nav-item.active {
            color: var(--tlc-navy);
            background: var(--tlc-gold);
            font-weight: 600;
        }
        .main-nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--tlc-gold);
        }

        /* Sub Navigation Dropdown */
        .main-nav-item-wrapper {
            position: relative;
        }
        @media (hover: hover) {
            .main-nav-item-wrapper:hover .sub-nav-dropdown {
                opacity: 1;
                visibility: visible;
            }
            /* Keep dropdown open when hovering over it */
            .sub-nav-dropdown:hover {
                opacity: 1 !important;
                visibility: visible !important;
            }
        }
        /* Create invisible bridge to prevent gap issues */
        .sub-nav-dropdown::before {
            content: '';
            position: absolute;
            top: -4px;
            left: 0;
            right: 0;
            height: 4px;
        }

        /* Sub Navigation */
        .sub-nav {
            background: linear-gradient(135deg, var(--tlc-gold), rgba(244, 211, 94, 0.9));
            border-bottom: 3px solid var(--tlc-orange);
        }
        .sub-nav-item {
            padding: 0.5rem 1rem;
            color: var(--tlc-navy);
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            user-select: none;
        }
        @media (hover: hover) {
            .sub-nav-item:hover {
                background: rgba(13, 59, 102, 0.1);
            }
        }
        .sub-nav-item.active {
            background: var(--tlc-navy);
            color: var(--tlc-cream);
            font-weight: 600;
        }

        /* Mobile menu dropdown */
        .mobile-submenu {
            display: none;
            background: rgba(13, 59, 102, 0.95);
            padding-left: 1rem;
        }
        .mobile-submenu.open {
            display: block;
        }

        /* ===== Mobile Optimizations ===== */

        /* Smooth scrolling for the whole page */
        html {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Safe area insets for notched phones */
        body {
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
        }

        /* Prevent text size adjustment on orientation change */
        body {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        /* Mobile-first typography optimizations for Lexend */
        @media (max-width: 768px) {
            body {
                font-size: 16px; /* Prevent zoom on input focus */
                line-height: 1.6;
                letter-spacing: 0.01em; /* Lexend reads better with slight spacing on mobile */
            }

            /* Improved touch targets - minimum 44px */
            a, button, .main-nav-item, .sub-nav-item {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
            }

            /* Better mobile navigation */
            .main-nav-item {
                padding: 0.875rem 1rem;
                font-size: 0.9375rem;
            }

            /* Larger mobile menu button */
            #mobile-menu-button {
                min-width: 44px;
                min-height: 44px;
                padding: 0.625rem;
            }

            /* Better mobile menu items */
            #mobile-menu a,
            #mobile-menu button {
                padding: 0.875rem 1rem;
                font-size: 1rem;
                min-height: 48px;
            }

            /* Improved mobile sub-menu spacing */
            .mobile-submenu a {
                padding: 0.75rem 1rem;
                min-height: 44px;
            }

            /* Sub navigation on mobile */
            .sub-nav .sub-nav-item {
                padding: 0.625rem 0.875rem;
                font-size: 0.875rem;
                min-height: 40px;
            }

            /* Better spacing for content areas */
            main {
                padding-bottom: 2rem;
            }
        }

        /* Small mobile screens */
        @media (max-width: 480px) {
            body {
                font-size: 15px;
            }

            /* Tighter navigation for small screens */
            .main-nav-item {
                padding: 0.75rem 0.625rem;
                font-size: 0.875rem;
            }

            /* Stack user info vertically on very small screens */
            .mobile-submenu a {
                padding: 0.625rem 0.875rem;
            }
        }

        /* Prevent double-tap zoom while keeping accessibility */
        a, button, input, select, textarea {
            touch-action: manipulation;
        }

        /* Better focus states for accessibility */
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible {
            outline: 3px solid var(--tlc-gold);
            outline-offset: 2px;
        }

        /* Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            html {
                scroll-behavior: auto;
            }
        }

        /* Sticky mobile navigation for easy access */
        @media (max-width: 768px) {
            nav {
                position: sticky;
                top: 0;
                z-index: 50;
            }
        }

        /* Better tap highlight on mobile */
        a, button {
            -webkit-tap-highlight-color: rgba(244, 211, 94, 0.3);
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased tlc-bg">
    <!-- Navigation -->
    <nav class="shadow-lg border-b border-tlc-gold/30" style="background-color: var(--tlc-navy);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Navigation -->
            <div class="flex justify-between items-center h-16 md:hidden">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="https://visitors.aes.ac.in/images/aes.png" alt="TLC Professional Learning" class="h-8 w-auto">
                </a>
                <button id="mobile-menu-button" class="p-2 rounded-md text-tlc-cream hover:text-white" style="background-color: var(--tlc-orange);" aria-label="Toggle navigation menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex justify-between h-16" style="overflow: hidden;">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="https://visitors.aes.ac.in/images/aes.png" alt="TLC Professional Learning" class="h-10 w-auto">
                    </a>
                    @if(auth()->check() && auth()->user()->division)
                        <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                            {{ auth()->user()->division->full_name }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Main Navigation Tabs -->
                    <nav class="flex items-center space-x-1" style="overflow: visible;">
                        <!-- My PL -->
                        <a href="{{ route('my-pl.index') }}"
                           class="main-nav-item {{ request()->routeIs('my-pl.*') ? 'active' : '' }}">
                            My PL
                        </a>

                        <!-- Fall PL Day -->
                        @if($plDaysActive ?? true)
                        <a href="{{ route('fall.schedule') }}"
                           class="main-nav-item {{ request()->routeIs('fall.*') ? 'active' : '' }}">
                            Fall PL Day
                        </a>
                        @endif

                        <!-- Spring PL Days -->
                        @if($plDaysActive ?? true)
                        <a href="{{ route('spring.schedule') }}"
                           class="main-nav-item {{ request()->routeIs('spring.*') ? 'active' : '' }}">
                            Spring PL Days
                        </a>
                        @endif

                        <!-- PL Wednesday -->
                        @if($plWednesdayActive ?? false)
                        <a href="{{ route('pl-wednesday.index') }}"
                           class="main-nav-item {{ request()->routeIs('pl-wednesday.*') ? 'active' : '' }}">
                            PL Wednesday
                        </a>
                        @endif
                    </nav>

                    <div class="flex items-center space-x-2 ml-4 pl-4 border-l border-tlc-gold/30">
                        @if(auth()->check() && auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full ring-2 ring-tlc-gold/50">
                        @endif
                        <span class="text-sm text-tlc-cream hidden lg:inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-tlc-cream hover:text-tlc-orange transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu (Hidden by default) -->
            <div id="mobile-menu" class="hidden md:hidden" style="border-top: 1px solid rgba(244, 211, 94, 0.3);">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- My PL -->
                    <a href="{{ route('my-pl.index') }}"
                       class="block px-3 py-2 text-base font-medium text-tlc-cream hover:text-tlc-navy rounded-md transition-colors {{ request()->routeIs('my-pl.*') ? 'text-tlc-navy' : '' }}" style="{{ request()->routeIs('my-pl.*') ? 'background-color: var(--tlc-gold);' : '' }}">
                        My PL
                    </a>

                    <!-- Fall PL Day -->
                    @if($plDaysActive ?? true)
                    <div class="mobile-nav-group">
                        <button class="mobile-nav-toggle w-full flex items-center justify-between px-3 py-2 text-base font-medium text-tlc-cream hover:text-tlc-navy rounded-md transition-colors {{ request()->routeIs('fall.*') ? 'text-tlc-navy' : '' }}" style="{{ request()->routeIs('fall.*') ? 'background-color: var(--tlc-gold);' : '' }}">
                            <span>Fall PL Day</span>
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mobile-submenu {{ request()->routeIs('fall.*') ? 'open' : '' }}">
                            <a href="{{ route('fall.schedule') }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->routeIs('fall.schedule') && !request()->route('pdday') ? 'text-tlc-gold font-semibold' : '' }}">
                                Schedule
                            </a>
                            <a href="{{ route('fall.wellness') }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->routeIs('fall.wellness') ? 'text-tlc-gold font-semibold' : '' }}">
                                Wellness
                            </a>
                            @if(isset($archivedFallPDDays) && $archivedFallPDDays->count() > 0)
                                @foreach($archivedFallPDDays as $archived)
                                <a href="{{ route('fall.schedule', ['pdday' => $archived->id]) }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->route('pdday') && request()->route('pdday')->id == $archived->id ? 'text-tlc-gold font-semibold' : '' }}">
                                    📁 {{ $archived->academic_year }}
                                </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Spring PL Days -->
                    @if($plDaysActive ?? true)
                    <div class="mobile-nav-group">
                        <button class="mobile-nav-toggle w-full flex items-center justify-between px-3 py-2 text-base font-medium text-tlc-cream hover:text-tlc-navy rounded-md transition-colors {{ request()->routeIs('spring.*') ? 'text-tlc-navy' : '' }}" style="{{ request()->routeIs('spring.*') ? 'background-color: var(--tlc-gold);' : '' }}">
                            <span>Spring PL Days</span>
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mobile-submenu {{ request()->routeIs('spring.*') ? 'open' : '' }}">
                            <a href="{{ route('spring.schedule') }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->routeIs('spring.schedule') && !request()->route('pdday') ? 'text-tlc-gold font-semibold' : '' }}">
                                Schedule
                            </a>
                            <a href="{{ route('spring.wellness') }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->routeIs('spring.wellness') ? 'text-tlc-gold font-semibold' : '' }}">
                                Wellness
                            </a>
                            @if($tttActive ?? false)
                            <a href="{{ route('spring.ttt') }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->routeIs('spring.ttt') ? 'text-tlc-gold font-semibold' : '' }}">
                                TTT
                            </a>
                            @endif
                            @if(isset($archivedSpringPDDays) && $archivedSpringPDDays->count() > 0)
                                @foreach($archivedSpringPDDays as $archived)
                                <a href="{{ route('spring.schedule', ['pdday' => $archived->id]) }}" class="block px-3 py-2 text-sm text-tlc-cream hover:text-tlc-gold {{ request()->route('pdday') && request()->route('pdday')->id == $archived->id ? 'text-tlc-gold font-semibold' : '' }}">
                                    📁 {{ $archived->academic_year }}
                                </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- PL Wednesday -->
                    @if($plWednesdayActive ?? false)
                    <a href="{{ route('pl-wednesday.index') }}"
                       class="block px-3 py-2 text-base font-medium text-tlc-cream hover:text-tlc-navy rounded-md transition-colors {{ request()->routeIs('pl-wednesday.*') ? 'text-tlc-navy' : '' }}" style="{{ request()->routeIs('pl-wednesday.*') ? 'background-color: var(--tlc-gold);' : '' }}">
                        PL Wednesday
                    </a>
                    @endif

                    <!-- User Info -->
                    <div class="pt-3 mt-3" style="border-top: 1px solid rgba(244, 211, 94, 0.3);">
                        <div class="flex items-center px-3 py-2">
                            @if(auth()->check() && auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full mr-3 ring-2 ring-tlc-gold/50">
                            @endif
                            <div class="flex-1">
                                <div class="text-sm font-medium text-tlc-cream">{{ auth()->user()->name }}</div>
                                @if(auth()->check() && auth()->user()->division)
                                    <div class="text-xs text-tlc-gold">{{ auth()->user()->division->full_name }}</div>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                            @csrf
                            <button type="submit" class="w-full text-left text-sm text-tlc-cream hover:text-tlc-orange transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sub Navigation for Fall/Spring PL Days -->
    @if(request()->routeIs('fall.*'))
    <div class="sub-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 py-2">
                <a href="{{ route('fall.schedule') }}"
                   class="sub-nav-item {{ request()->routeIs('fall.schedule') && !request()->route('pdday') ? 'active' : '' }}">
                    Schedule
                </a>
                <a href="{{ route('fall.wellness') }}"
                   class="sub-nav-item {{ request()->routeIs('fall.wellness') ? 'active' : '' }}">
                    Wellness
                </a>
                @if(isset($archivedFallPDDays) && $archivedFallPDDays->count() > 0)
                    @foreach($archivedFallPDDays as $archived)
                    <a href="{{ route('fall.schedule', ['pdday' => $archived->id]) }}"
                       class="sub-nav-item {{ request()->route('pdday') && request()->route('pdday')->id == $archived->id ? 'active' : '' }}">
                        <span class="inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            {{ $archived->academic_year }}
                        </span>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(request()->routeIs('spring.*'))
    <div class="sub-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 py-2">
                <a href="{{ route('spring.schedule') }}"
                   class="sub-nav-item {{ request()->routeIs('spring.schedule') && !request()->route('pdday') ? 'active' : '' }}">
                    Schedule
                </a>
                <a href="{{ route('spring.wellness') }}"
                   class="sub-nav-item {{ request()->routeIs('spring.wellness') ? 'active' : '' }}">
                    Wellness
                </a>
                @if($tttActive ?? false)
                <a href="{{ route('spring.ttt') }}"
                   class="sub-nav-item {{ request()->routeIs('spring.ttt') ? 'active' : '' }}">
                    TTT
                </a>
                @endif
                @if(isset($archivedSpringPDDays) && $archivedSpringPDDays->count() > 0)
                    @foreach($archivedSpringPDDays as $archived)
                    <a href="{{ route('spring.schedule', ['pdday' => $archived->id]) }}"
                       class="sub-nav-item {{ request()->route('pdday') && request()->route('pdday')->id == $archived->id ? 'active' : '' }}">
                        <span class="inline-flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            {{ $archived->academic_year }}
                        </span>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Mobile submenu toggles
            document.querySelectorAll('.mobile-nav-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const submenu = this.nextElementSibling;
                    submenu.classList.toggle('open');
                    const arrow = this.querySelector('svg');
                    arrow.classList.toggle('rotate-180');
                });
            });

            // Keep dropdown open when hovering over it, but only one at a time
            const allDropdowns = document.querySelectorAll('.sub-nav-dropdown');
            document.querySelectorAll('.main-nav-item-wrapper').forEach(function(wrapper) {
                const dropdown = wrapper.querySelector('.sub-nav-dropdown');
                if (dropdown) {
                    let hideTimeout;
                    
                    function showDropdown() {
                        // Clear any pending hide timeout
                        if (hideTimeout) {
                            clearTimeout(hideTimeout);
                            hideTimeout = null;
                        }
                        
                        // Close all other dropdowns first
                        allDropdowns.forEach(function(otherDropdown) {
                            if (otherDropdown !== dropdown) {
                                otherDropdown.style.opacity = '0';
                                otherDropdown.style.visibility = 'hidden';
                            }
                        });
                        // Then show this dropdown
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                    }
                    
                    function hideDropdown() {
                        // Add a small delay before hiding to allow moving to dropdown
                        hideTimeout = setTimeout(function() {
                            dropdown.style.opacity = '0';
                            dropdown.style.visibility = 'hidden';
                        }, 100);
                    }
                    
                    wrapper.addEventListener('mouseenter', showDropdown);
                    wrapper.addEventListener('mouseleave', hideDropdown);
                    
                    // Also handle hover on the dropdown itself
                    dropdown.addEventListener('mouseenter', function() {
                        if (hideTimeout) {
                            clearTimeout(hideTimeout);
                            hideTimeout = null;
                        }
                        showDropdown();
                    });
                    
                    dropdown.addEventListener('mouseleave', hideDropdown);
                }
            });
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
