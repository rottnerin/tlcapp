<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AES Professional Learning Days</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .aes-primary { background: linear-gradient(135deg, #4CAF50 0%, #2196F3 50%, #FF9800 100%); }
            .aes-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        </style>
    </head>
    <body class="antialiased min-h-screen aes-primary">
        <div class="relative min-h-screen flex items-center justify-center px-6">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 max-w-md w-full">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-white mb-2">AES</h1>
                    <h2 class="text-xl text-white/90 mb-1">Professional Learning Days</h2>
                    @if($activePDDay)
                        <p class="text-white/80 text-lg">{{ $activePDDay->date_range }}</p>
                    @else
                        <p class="text-white/80 text-lg">No active event</p>
                    @endif
                </div>

                <!-- Login Card -->
                <div class="aes-card rounded-2xl shadow-2xl p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-2">Welcome Back</h3>
                        <p class="text-gray-600">Sign in with your AES Google account to access your personalized schedule and wellness sessions.</p>
                    </div>

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Welcome back, {{ auth()->user()->name }}!</p>
                            <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                                Go to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            <a href="{{ route('google.login') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition duration-200">
                                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                    <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Continue with Google</span>
                            </a>
                            
                            <div class="text-center">
                                <p class="text-xs text-gray-500">
                                    Please use your AES school email account<br>
                                </p>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Event Info -->
                <div class="mt-8 text-center text-white/80">
                    <p class="text-sm mb-2">🎯 Personalized Schedules • 🧘 Wellness Sessions </p>
                    <p class="text-xs">Questions? Contact TLC or email rmckinnie@aes.ac.in</p>
                </div>
            </div>
        </div>
    </body>
</html>
