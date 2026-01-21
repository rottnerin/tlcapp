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
    
    <!-- TLC Brand Colors -->
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
        
        /* TLC Utility Classes */
        .bg-tlc-navy { background-color: var(--tlc-navy); }
        .bg-tlc-cream { background-color: var(--tlc-cream); }
        .bg-tlc-gold { background-color: var(--tlc-gold); }
        .bg-tlc-orange { background-color: var(--tlc-orange); }
        .text-tlc-navy { color: var(--tlc-navy); }
        .text-tlc-cream { color: var(--tlc-cream); }
        .text-tlc-gold { color: var(--tlc-gold); }
        .text-tlc-orange { color: var(--tlc-orange); }
        .border-tlc-navy { border-color: var(--tlc-navy); }
        .border-tlc-gold { border-color: var(--tlc-gold); }
        .border-tlc-orange { border-color: var(--tlc-orange); }
        
        /* Admin navigation */
        .gradient-header { background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%); }
        .bg-content { background-color: var(--tlc-cream); }
        
        /* Shadows */
        .shadow-content { box-shadow: 0 4px 6px -1px rgba(13, 59, 102, 0.1), 0 2px 4px -1px rgba(13, 59, 102, 0.06); }
        .shadow-card { box-shadow: 0 10px 15px -3px rgba(13, 59, 102, 0.1), 0 4px 6px -2px rgba(13, 59, 102, 0.05); }

        /* Custom text selection styling */
        ::selection {
            background-color: var(--tlc-gold);
            color: var(--tlc-navy);
        }

        /* Disable user-select on interactive elements */
        button, a[role="button"], [onclick] {
            user-select: none;
        }

        /* Hover states */
        @media (hover: hover) {
            .hover\:bg-tlc-orange:hover { background-color: var(--tlc-orange); }
            .hover\:bg-tlc-gold:hover { background-color: var(--tlc-gold); }
            .hover\:text-tlc-navy:hover { color: var(--tlc-navy); }
        }

        /* ===== Mobile Optimizations ===== */
        html {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        body {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        /* Safe area insets for admin content area only */
        main {
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
            padding-bottom: env(safe-area-inset-bottom);
        }

        @media (max-width: 768px) {
            body {
                font-size: 16px;
                line-height: 1.6;
            }

            /* Minimum touch targets */
            a, button {
                min-height: 44px;
            }

            /* Better admin nav on mobile */
            #admin-mobile-menu a {
                padding: 0.875rem 1rem;
                min-height: 48px;
                font-size: 1rem;
            }

            /* Sticky admin nav */
            nav.gradient-header {
                position: sticky;
                top: 0;
                z-index: 50;
            }
        }

        /* Touch handling */
        a, button, input, select, textarea {
            touch-action: manipulation;
        }

        /* Focus states */
        a:focus-visible, button:focus-visible {
            outline: 3px solid var(--tlc-gold);
            outline-offset: 2px;
        }

        /* Tap highlight */
        a, button {
            -webkit-tap-highlight-color: rgba(244, 211, 94, 0.3);
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-content min-h-screen">
    <!-- Admin Navigation Bar -->
    @if(auth()->check() && auth()->user()->is_admin)
        <nav class="gradient-header shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Mobile Navigation -->
                <div class="flex justify-between items-center h-16 md:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <img src="https://visitors.aes.ac.in/images/aes.png" alt="AES Admin" class="h-8 w-auto">
                    </a>
                    <button id="admin-mobile-menu-button" class="p-2 rounded-md text-white hover:text-gray-200 hover:bg-white/10" aria-label="Toggle admin navigation menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
                        <span class="ml-4 px-2.5 py-1 text-xs font-semibold bg-tlc-gold text-tlc-navy rounded-md">
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
                            <a href="{{ route('admin.ccl.index') }}" 
                               class="text-white hover:text-gray-200 {{ request()->routeIs('admin.ccl.*') ? 'font-medium underline underline-offset-4' : '' }}">
                                CCL
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
                <div id="admin-mobile-menu" class="hidden md:hidden border-t border-white/20">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="{{ route('admin.pl-wednesday.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.pl-wednesday.*') ? 'bg-white/10' : '' }}">
                            PL Wednesday
                        </a>
                        <a href="{{ route('admin.pddays.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.pddays.*') ? 'bg-white/10' : '' }}">
                            PL Days
                        </a>
                        <a href="{{ route('admin.wellness.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.wellness.*') ? 'bg-white/10' : '' }}">
                            Wellness
                        </a>
                        <a href="{{ route('admin.schedule.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.schedule.*') ? 'bg-white/10' : '' }}">
                            Schedule
                        </a>
                        <a href="{{ route('admin.ccl.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.ccl.*') ? 'bg-white/10' : '' }}">
                            CCL
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-white/10' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.reports') }}"
                           class="block px-3 py-2 text-base font-medium text-white hover:text-gray-200 hover:bg-white/10 rounded-md {{ request()->routeIs('admin.reports*') ? 'bg-white/10' : '' }}">
                            Reports
                        </a>
                        <div class="pt-3 mt-3 border-t border-white/20">
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
