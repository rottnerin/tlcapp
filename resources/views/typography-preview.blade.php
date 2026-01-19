<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Typography - TLC 2.0</title>

    <!-- Lexend - Our Chosen Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
        }

        .weight-sample {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(13, 59, 102, 0.1);
        }
        .weight-sample:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .weight-sample {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
    </style>
</head>
<body class="antialiased" style="background-color: var(--tlc-cream);">
    <!-- Header -->
    <header class="shadow-lg" style="background-color: var(--tlc-navy);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">TLC 2.0 Typography</h1>
                    <p class="text-sm mt-1" style="color: var(--tlc-gold);">Lexend - Optimized for reading fluency</p>
                </div>
                <a href="/" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                    <i class="fas fa-arrow-left mr-2"></i>Back to App
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Why Lexend -->
        <section class="mb-8">
            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4" style="border-color: var(--tlc-gold);">
                <h2 class="text-xl font-semibold mb-3" style="color: var(--tlc-navy);">Why Lexend?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" style="color: #4a5568;">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-mobile-alt mt-1" style="color: var(--tlc-orange);"></i>
                        <div>
                            <strong style="color: var(--tlc-navy);">Mobile-First Design</strong>
                            <p>Optimized letter spacing for small screens</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-eye mt-1" style="color: var(--tlc-orange);"></i>
                        <div>
                            <strong style="color: var(--tlc-navy);">Reading Fluency</strong>
                            <p>Designed to reduce visual crowding</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-universal-access mt-1" style="color: var(--tlc-orange);"></i>
                        <div>
                            <strong style="color: var(--tlc-navy);">Accessibility</strong>
                            <p>Better for users with reading difficulties</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-sparkles mt-1" style="color: var(--tlc-orange);"></i>
                        <div>
                            <strong style="color: var(--tlc-navy);">Fresh Look</strong>
                            <p>Modern, distinctive appearance</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Live Preview -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4" style="color: var(--tlc-navy);">Live Preview</h2>
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <nav class="px-6 py-4" style="background-color: var(--tlc-navy);">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4 sm:space-x-6">
                            <span class="text-xl font-bold text-white">TLC</span>
                            <div class="flex flex-wrap gap-2 sm:gap-4">
                                <span class="text-sm font-medium px-3 py-2 rounded" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">My PL</span>
                                <span class="text-sm font-medium text-white">Fall PL Day</span>
                                <span class="text-sm font-medium text-white hidden sm:inline">Spring PL Days</span>
                            </div>
                        </div>
                        <div class="text-sm text-white">Jane Teacher</div>
                    </div>
                </nav>

                <div class="p-6 sm:p-8">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2" style="color: var(--tlc-navy);">Welcome to Professional Learning</h1>
                    <p class="text-base sm:text-lg mb-6" style="color: #666;">Explore sessions, track your learning journey, and grow professionally.</p>

                    <!-- Session Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                        <div class="p-4 sm:p-5 rounded-lg" style="background-color: var(--tlc-cream);">
                            <h3 class="text-base sm:text-lg font-semibold mb-2" style="color: var(--tlc-navy);">Workshop Session</h3>
                            <p class="text-sm mb-3" style="color: #666;">Learn innovative teaching strategies for the modern classroom environment.</p>
                            <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">Elementary</span>
                        </div>
                        <div class="p-4 sm:p-5 rounded-lg" style="background-color: var(--tlc-cream);">
                            <h3 class="text-base sm:text-lg font-semibold mb-2" style="color: var(--tlc-navy);">Collaborative Planning</h3>
                            <p class="text-sm mb-3" style="color: #666;">Work with colleagues to develop cross-curricular connections.</p>
                            <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-orange); color: white;">Middle School</span>
                        </div>
                        <div class="p-4 sm:p-5 rounded-lg sm:col-span-2 lg:col-span-1" style="background-color: var(--tlc-cream);">
                            <h3 class="text-base sm:text-lg font-semibold mb-2" style="color: var(--tlc-navy);">Tech Integration</h3>
                            <p class="text-sm mb-3" style="color: #666;">Discover new tools to enhance student engagement and learning.</p>
                            <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-navy); color: white;">High School</span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-wrap gap-3 sm:gap-4">
                        <button class="px-4 sm:px-6 py-3 rounded-lg font-semibold text-white text-sm sm:text-base" style="background-color: var(--tlc-navy);">
                            View Schedule
                        </button>
                        <button class="px-4 sm:px-6 py-3 rounded-lg font-semibold text-sm sm:text-base" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                            My Selections
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Typography Scale -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4" style="color: var(--tlc-navy);">Typography Scale</h2>
            <div class="bg-white rounded-xl p-6 sm:p-8 shadow-lg">
                <div class="space-y-4">
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">4xl</span>
                        <span class="text-3xl sm:text-4xl font-bold" style="color: var(--tlc-navy);">Professional Learning</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">3xl</span>
                        <span class="text-2xl sm:text-3xl font-bold" style="color: var(--tlc-navy);">Fall PL Day Schedule</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">2xl</span>
                        <span class="text-xl sm:text-2xl font-semibold" style="color: var(--tlc-navy);">Welcome, Jane Teacher</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">xl</span>
                        <span class="text-lg sm:text-xl font-semibold" style="color: var(--tlc-navy);">Workshop Sessions</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">lg</span>
                        <span class="text-base sm:text-lg font-medium" style="color: var(--tlc-navy);">Collaborative Learning</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">base</span>
                        <span class="text-sm sm:text-base" style="color: #333;">Explore innovative teaching methods and connect with colleagues.</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">sm</span>
                        <span class="text-xs sm:text-sm" style="color: #666;">Session runs from 9:00 AM to 10:30 AM in Room 204</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Font Weights -->
        <section>
            <h2 class="text-xl font-semibold mb-4" style="color: var(--tlc-navy);">Font Weights</h2>
            <div class="bg-white rounded-xl p-6 sm:p-8 shadow-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-4 rounded-lg" style="background-color: var(--tlc-cream);">
                        <p class="text-2xl font-normal mb-2" style="color: var(--tlc-navy);">Aa</p>
                        <p class="text-sm" style="color: #666;">Regular 400</p>
                        <p class="text-xs mt-1" style="color: #999;">Body text</p>
                    </div>
                    <div class="text-center p-4 rounded-lg" style="background-color: var(--tlc-cream);">
                        <p class="text-2xl font-medium mb-2" style="color: var(--tlc-navy);">Aa</p>
                        <p class="text-sm" style="color: #666;">Medium 500</p>
                        <p class="text-xs mt-1" style="color: #999;">Navigation</p>
                    </div>
                    <div class="text-center p-4 rounded-lg" style="background-color: var(--tlc-cream);">
                        <p class="text-2xl font-semibold mb-2" style="color: var(--tlc-navy);">Aa</p>
                        <p class="text-sm" style="color: #666;">Semibold 600</p>
                        <p class="text-xs mt-1" style="color: #999;">Subheadings</p>
                    </div>
                    <div class="text-center p-4 rounded-lg" style="background-color: var(--tlc-cream);">
                        <p class="text-2xl font-bold mb-2" style="color: var(--tlc-navy);">Aa</p>
                        <p class="text-sm" style="color: #666;">Bold 700</p>
                        <p class="text-xs mt-1" style="color: #999;">Headlines</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-8" style="background-color: var(--tlc-navy);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm" style="color: var(--tlc-cream);">TLC 2.0 Typography System</p>
            <p class="text-xs mt-2" style="color: var(--tlc-gold);">Lexend - Mobile-optimized for reading fluency</p>
        </div>
    </footer>
</body>
</html>
