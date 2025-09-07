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

    <!-- AES Brand Styles -->
    <style>
        .aes-primary { background: linear-gradient(135deg, #4CAF50 0%, #2196F3 50%, #FF9800 100%); }
        .aes-bg { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .division-es { border-left: 4px solid #4CAF50; }
        .division-ms { border-left: 4px solid #2196F3; }
        .division-hs { border-left: 4px solid #FF9800; }
        .session-card { transition: all 0.3s ease; }
        .session-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased bg-gray-50 aes-bg">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        AES Professional Learning Days
                    </a>
                    @if(auth()->check() && auth()->user()->division)
                        <span class="ml-4 px-3 py-1 text-xs font-medium bg-{{ strtolower(auth()->user()->division->name) === 'es' ? 'green' : (strtolower(auth()->user()->division->name) === 'ms' ? 'blue' : 'orange') }}-100 text-{{ strtolower(auth()->user()->division->name) === 'es' ? 'green' : (strtolower(auth()->user()->division->name) === 'ms' ? 'blue' : 'orange') }}-800 rounded-full">
                            {{ auth()->user()->division->full_name }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    <nav class="space-x-4">
                        <a href="{{ route('dashboard') }}"
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'text-gray-900 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('schedule.index') }}"
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('schedule.*') ? 'text-gray-900 font-medium' : '' }}">
                            Schedule
                        </a>
                        <a href="{{ route('wellness.index') }}"
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('wellness.*') ? 'text-gray-900 font-medium' : '' }}">
                            Wellness
                        </a>
                        <a href="{{ route('my-schedule') }}"
                           class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('my-schedule') ? 'text-gray-900 font-medium' : '' }}">
                            My Schedule
                        </a>
                    </nav>

                    <div class="flex items-center space-x-2">
                        @if(auth()->check() && auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                        @endif
                        <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>