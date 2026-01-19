<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TLC Professional Learning</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --tlc-navy: #0d3b66;
                --tlc-cream: #faf0ca;
                --tlc-gold: #f4d35e;
                --tlc-orange: #ee964b;
            }
            body { font-family: 'Lexend', ui-sans-serif, system-ui, sans-serif; }
            .tlc-primary { background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 50%, #1a5a8a 100%); }
            .tlc-card { background: var(--tlc-cream); backdrop-filter: blur(10px); }
        </style>
    </head>
    <body class="antialiased min-h-screen tlc-primary">
        <div class="relative min-h-screen flex items-center justify-center px-6">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="#faf0ca" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 max-w-md w-full">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-2" style="color: var(--tlc-gold);">TLC</h1>
                    <h2 class="text-xl mb-1" style="color: var(--tlc-cream);">Professional Learning</h2>
                    @if($activePDDay)
                        <p class="text-lg" style="color: rgba(250, 240, 202, 0.8);">{{ $activePDDay->date_range }}</p>
                    @else
                        <p class="text-lg" style="color: rgba(250, 240, 202, 0.8);">No active event</p>
                    @endif
                </div>

                <!-- Login Card -->
                <div class="tlc-card rounded-2xl shadow-2xl p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-semibold mb-2" style="color: var(--tlc-navy);">Welcome Back</h3>
                        <p style="color: #4a5568;">Sign in to access your personalized schedule and wellness sessions.</p>
                    </div>

                    @if (session('error'))
                        <div class="mb-4 p-4 rounded-lg" style="background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c;">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 rounded-lg" style="background-color: #d1fae5; border: 1px solid #34d399; color: #047857;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-lg" style="background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c;">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @auth
                        <div class="text-center">
                            <p class="mb-4" style="color: #4a5568;">Welcome back, {{ auth()->user()->name }}!</p>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-3 text-white font-medium rounded-lg transition duration-200 mb-3" style="background-color: var(--tlc-navy);">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Admin Dashboard
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-3 font-medium rounded-lg transition duration-200" style="background-color: var(--tlc-orange); color: white;">
                                Go to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            <!-- Google Login for Staff -->
                            <a href="{{ route('google.login') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white border rounded-lg shadow-sm hover:shadow-md transition duration-200" style="border-color: var(--tlc-navy);">
                                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                    <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span class="font-medium" style="color: var(--tlc-navy);">Continue with Google</span>
                            </a>
                            
                            <div class="text-center">
                                <p class="text-xs" style="color: #718096;">
                                    Please use your AES school email account
                                </p>
                            </div>

                            <!-- Divider -->
                            <div class="relative my-6">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t" style="border-color: var(--tlc-navy); opacity: 0.2;"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2" style="background-color: var(--tlc-cream); color: var(--tlc-navy);">Admin Login</span>
                                </div>
                            </div>

                            <!-- Admin Login Form -->
                            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        autocomplete="email" 
                                        required 
                                        value="{{ old('email') }}"
                                        class="appearance-none block w-full px-3 py-2 rounded-lg shadow-sm sm:text-sm"
                                        style="border: 1px solid var(--tlc-navy); background: white; color: var(--tlc-navy);"
                                        placeholder="Admin email"
                                    >
                                </div>
                                <div>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        autocomplete="current-password" 
                                        required
                                        class="appearance-none block w-full px-3 py-2 rounded-lg shadow-sm sm:text-sm"
                                        style="border: 1px solid var(--tlc-navy); background: white; color: var(--tlc-navy);"
                                        placeholder="Password"
                                    >
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        id="remember" 
                                        name="remember" 
                                        type="checkbox" 
                                        class="h-4 w-4 rounded"
                                        style="color: var(--tlc-orange); border-color: var(--tlc-navy);"
                                    >
                                    <label for="remember" class="ml-2 block text-sm" style="color: var(--tlc-navy);">
                                        Remember me
                                    </label>
                                </div>
                                <button 
                                    type="submit" 
                                    class="btn-navy-lighten w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Admin Sign In
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Event Info -->
                <div class="mt-8 text-center" style="color: var(--tlc-cream);">
                    <p class="text-sm mb-2">🎯 Personalized Schedules • 🧘 Wellness Sessions </p>
                    <p class="text-xs" style="color: rgba(250, 240, 202, 0.7);">Questions? Contact TLC or email rmckinnie@aes.ac.in</p>
                </div>
            </div>
        </div>
    </body>
</html>
