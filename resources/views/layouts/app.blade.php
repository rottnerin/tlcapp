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
                    
                    <div class="flex items-center space-x-4">
                        <nav class="space-x-4">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'text-white font-medium' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.wellness.index') }}" 
                               class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.wellness.*') ? 'text-white font-medium' : '' }}">
                                Wellness
                            </a>
                            <a href="{{ route('admin.schedule.index') }}" 
                               class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.schedule.*') ? 'text-white font-medium' : '' }}">
                                Schedule
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.users.*') ? 'text-white font-medium' : '' }}">
                                Users
                            </a>
                            <a href="{{ route('admin.reports') }}" 
                               class="text-indigo-200 hover:text-white {{ request()->routeIs('admin.reports*') ? 'text-white font-medium' : '' }}">
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

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
