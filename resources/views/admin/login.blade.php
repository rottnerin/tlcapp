<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - TLC Professional Learning</title>
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
        body {
            font-family: 'Lexend', ui-sans-serif, system-ui, sans-serif;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }
        .admin-gradient {
            background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
        }
        ::selection {
            background-color: var(--tlc-gold);
            color: var(--tlc-navy);
        }
        .btn-orange-to-navy {
            background-color: var(--tlc-orange);
            transition: background-color 0.2s ease;
        }
        @media (hover: hover) {
            .btn-orange-to-navy:hover {
                background-color: var(--tlc-navy);
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen admin-gradient flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="rounded-full p-4 shadow-lg" style="background-color: var(--tlc-cream);">
                        <svg class="w-12 h-12" style="color: var(--tlc-navy);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold mb-2" style="color: var(--tlc-gold);">
                    Admin Login
                </h2>
                <p style="color: var(--tlc-cream);">
                    TLC Professional Learning
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-lg shadow-2xl p-8">
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none sm:text-sm @error('email') border-red-500 @enderror"
                            style="--tw-ring-color: var(--tlc-gold);"
                            placeholder="admin@example.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 text-gray-900 bg-white focus:outline-none sm:text-sm @error('password') border-red-500 @enderror"
                            style="--tw-ring-color: var(--tlc-gold);"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 border-gray-300 rounded"
                            style="color: var(--tlc-orange);"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="btn-orange-to-navy w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2"
                            style="--tw-ring-color: var(--tlc-orange);"
                        >
                            Sign in
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Not an admin?
                            </span>
                        </div>
                    </div>
                </div>

                <!-- User Login Link -->
                <div class="mt-6">
                    <a 
                        href="{{ route('google.login') }}" 
                        class="w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"
                        style="border-color: var(--tlc-navy); color: var(--tlc-navy); --tw-ring-color: var(--tlc-gold);"
                    >
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Sign in with Google (Staff)
                    </a>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="text-center">
                <p class="text-xs" style="color: var(--tlc-cream);">
                    🔒 Admin access is protected and monitored for security
                </p>
            </div>
        </div>
    </div>
</body>
</html>
