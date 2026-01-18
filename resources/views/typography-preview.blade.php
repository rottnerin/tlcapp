<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Typography Exploration - TLC 2.0</title>

    <!-- Current Font: DM Sans -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Exploration Fonts -->
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700&display=swap" rel="stylesheet" />

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

        .font-dm-sans { font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif; }
        .font-space-grotesk { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; }
        .font-lexend { font-family: 'Lexend', ui-sans-serif, system-ui, sans-serif; }
        .font-sora { font-family: 'Sora', ui-sans-serif, system-ui, sans-serif; }

        .font-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .font-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(13, 59, 102, 0.2);
        }
        .font-card.selected {
            border-color: var(--tlc-gold);
            box-shadow: 0 0 0 4px rgba(244, 211, 94, 0.3);
        }

        .preview-section {
            transition: font-family 0.3s ease;
        }

        .weight-sample {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(13, 59, 102, 0.1);
        }
        .weight-sample:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="antialiased" style="background-color: var(--tlc-cream);">
    <!-- Header -->
    <header class="shadow-lg" style="background-color: var(--tlc-navy);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Typography Exploration</h1>
                    <p class="text-sm mt-1" style="color: var(--tlc-gold);">Compare fonts for TLC 2.0's fresh new look</p>
                </div>
                <a href="/" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                    <i class="fas fa-arrow-left mr-2"></i>Back to App
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Font Selection Cards -->
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-6" style="color: var(--tlc-navy);">Select a Font to Preview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- DM Sans (Current) -->
                <div class="font-card bg-white rounded-xl p-6 cursor-pointer" onclick="selectFont('dm-sans')" id="card-dm-sans">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-navy); color: white;">Current</span>
                        <i class="fas fa-check-circle text-xl hidden" style="color: var(--tlc-gold);" id="check-dm-sans"></i>
                    </div>
                    <h3 class="font-dm-sans text-2xl font-bold mb-2" style="color: var(--tlc-navy);">DM Sans</h3>
                    <p class="font-dm-sans text-sm mb-4" style="color: #666;">Clean, geometric, friendly</p>
                    <div class="font-dm-sans space-y-1">
                        <p class="font-normal text-sm">Regular 400</p>
                        <p class="font-medium text-sm">Medium 500</p>
                        <p class="font-semibold text-sm">Semibold 600</p>
                        <p class="font-bold text-sm">Bold 700</p>
                    </div>
                </div>

                <!-- Space Grotesk -->
                <div class="font-card bg-white rounded-xl p-6 cursor-pointer" onclick="selectFont('space-grotesk')" id="card-space-grotesk">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-orange); color: white;">Distinctive</span>
                        <i class="fas fa-check-circle text-xl hidden" style="color: var(--tlc-gold);" id="check-space-grotesk"></i>
                    </div>
                    <h3 class="font-space-grotesk text-2xl font-bold mb-2" style="color: var(--tlc-navy);">Space Grotesk</h3>
                    <p class="font-space-grotesk text-sm mb-4" style="color: #666;">Futuristic, geometric, bold</p>
                    <div class="font-space-grotesk space-y-1">
                        <p class="font-normal text-sm">Regular 400</p>
                        <p class="font-medium text-sm">Medium 500</p>
                        <p class="font-semibold text-sm">Semibold 600</p>
                        <p class="font-bold text-sm">Bold 700</p>
                    </div>
                </div>

                <!-- Lexend -->
                <div class="font-card bg-white rounded-xl p-6 cursor-pointer" onclick="selectFont('lexend')" id="card-lexend">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">Readable</span>
                        <i class="fas fa-check-circle text-xl hidden" style="color: var(--tlc-gold);" id="check-lexend"></i>
                    </div>
                    <h3 class="font-lexend text-2xl font-bold mb-2" style="color: var(--tlc-navy);">Lexend</h3>
                    <p class="font-lexend text-sm mb-4" style="color: #666;">Optimized for reading fluency</p>
                    <div class="font-lexend space-y-1">
                        <p class="font-normal text-sm">Regular 400</p>
                        <p class="font-medium text-sm">Medium 500</p>
                        <p class="font-semibold text-sm">Semibold 600</p>
                        <p class="font-bold text-sm">Bold 700</p>
                    </div>
                </div>

                <!-- Sora -->
                <div class="font-card bg-white rounded-xl p-6 cursor-pointer" onclick="selectFont('sora')" id="card-sora">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: #10b981; color: white;">Fresh</span>
                        <i class="fas fa-check-circle text-xl hidden" style="color: var(--tlc-gold);" id="check-sora"></i>
                    </div>
                    <h3 class="font-sora text-2xl font-bold mb-2" style="color: var(--tlc-navy);">Sora</h3>
                    <p class="font-sora text-sm mb-4" style="color: #666;">Modern, geometric, contemporary</p>
                    <div class="font-sora space-y-1">
                        <p class="font-normal text-sm">Regular 400</p>
                        <p class="font-medium text-sm">Medium 500</p>
                        <p class="font-semibold text-sm">Semibold 600</p>
                        <p class="font-bold text-sm">Bold 700</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Live Preview Section -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold" style="color: var(--tlc-navy);">Live Preview</h2>
                <span class="text-sm px-3 py-1 rounded-full" style="background-color: var(--tlc-navy); color: white;" id="current-font-label">DM Sans</span>
            </div>

            <!-- Mock Navigation -->
            <div class="preview-section bg-white rounded-xl overflow-hidden shadow-lg" id="preview-container">
                <nav class="px-6 py-4" style="background-color: var(--tlc-navy);">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <span class="text-xl font-bold text-white">TLC</span>
                            <div class="flex space-x-4">
                                <a class="text-sm font-medium px-3 py-2 rounded" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">My PL</a>
                                <a class="text-sm font-medium text-white hover:text-yellow-300">Fall PL Day</a>
                                <a class="text-sm font-medium text-white hover:text-yellow-300">Spring PL Days</a>
                            </div>
                        </div>
                        <div class="text-sm text-white">Jane Teacher</div>
                    </div>
                </nav>

                <!-- Mock Content -->
                <div class="p-8">
                    <h1 class="text-3xl font-bold mb-2" style="color: var(--tlc-navy);">Welcome to Professional Learning</h1>
                    <p class="text-lg mb-6" style="color: #666;">Explore sessions, track your learning journey, and grow professionally.</p>

                    <!-- Mock Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="p-5 rounded-lg" style="background-color: var(--tlc-cream);">
                            <h3 class="text-lg font-semibold mb-2" style="color: var(--tlc-navy);">Workshop Session</h3>
                            <p class="text-sm mb-3" style="color: #666;">Learn innovative teaching strategies for the modern classroom environment.</p>
                            <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">Elementary</span>
                        </div>
                        <div class="p-5 rounded-lg" style="background-color: var(--tlc-cream);">
                            <h3 class="text-lg font-semibold mb-2" style="color: var(--tlc-navy);">Collaborative Planning</h3>
                            <p class="text-sm mb-3" style="color: #666;">Work with colleagues to develop cross-curricular connections.</p>
                            <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-orange); color: white;">Middle School</span>
                        </div>
                        <div class="p-5 rounded-lg" style="background-color: var(--tlc-cream);">
                            <h3 class="text-lg font-semibold mb-2" style="color: var(--tlc-navy);">Tech Integration</h3>
                            <p class="text-sm mb-3" style="color: #666;">Discover new tools to enhance student engagement and learning.</p>
                            <span class="text-xs font-medium px-2 py-1 rounded" style="background-color: var(--tlc-navy); color: white;">High School</span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button class="px-6 py-3 rounded-lg font-semibold text-white transition-colors" style="background-color: var(--tlc-navy);">
                            View Schedule
                        </button>
                        <button class="px-6 py-3 rounded-lg font-semibold transition-colors" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                            My Selections
                        </button>
                        <button class="px-6 py-3 rounded-lg font-semibold border-2 transition-colors" style="border-color: var(--tlc-navy); color: var(--tlc-navy);">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Typography Scale -->
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-6" style="color: var(--tlc-navy);">Typography Scale</h2>
            <div class="preview-section bg-white rounded-xl p-8 shadow-lg" id="scale-container">
                <div class="space-y-6">
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">4xl / 36px</span>
                        <span class="text-4xl font-bold" style="color: var(--tlc-navy);">Professional Learning Center</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">3xl / 30px</span>
                        <span class="text-3xl font-bold" style="color: var(--tlc-navy);">Fall PL Day Schedule</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">2xl / 24px</span>
                        <span class="text-2xl font-semibold" style="color: var(--tlc-navy);">Welcome, Jane Teacher</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">xl / 20px</span>
                        <span class="text-xl font-semibold" style="color: var(--tlc-navy);">Workshop Sessions</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">lg / 18px</span>
                        <span class="text-lg font-medium" style="color: var(--tlc-navy);">Collaborative Learning Strategies</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">base / 16px</span>
                        <span class="text-base" style="color: #333;">Explore innovative teaching methods and connect with colleagues across divisions.</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">sm / 14px</span>
                        <span class="text-sm" style="color: #666;">Session runs from 9:00 AM to 10:30 AM in Room 204</span>
                    </div>
                    <div class="weight-sample">
                        <span class="text-xs w-20 shrink-0" style="color: #999;">xs / 12px</span>
                        <span class="text-xs" style="color: #999;">Last updated: January 18, 2026</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Character Comparison -->
        <section>
            <h2 class="text-xl font-semibold mb-6" style="color: var(--tlc-navy);">Character Comparison</h2>
            <div class="bg-white rounded-xl p-8 shadow-lg overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2" style="border-color: var(--tlc-navy);">
                            <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--tlc-navy);">Font</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--tlc-navy);">Alphabet</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--tlc-navy);">Numbers</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--tlc-navy);">Special</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b" style="border-color: #eee;">
                            <td class="py-4 px-4 font-medium font-dm-sans" style="color: var(--tlc-navy);">DM Sans</td>
                            <td class="py-4 px-4 font-dm-sans">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz</td>
                            <td class="py-4 px-4 font-dm-sans">0123456789</td>
                            <td class="py-4 px-4 font-dm-sans">@#$%&*!?</td>
                        </tr>
                        <tr class="border-b" style="border-color: #eee;">
                            <td class="py-4 px-4 font-medium font-space-grotesk" style="color: var(--tlc-navy);">Space Grotesk</td>
                            <td class="py-4 px-4 font-space-grotesk">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz</td>
                            <td class="py-4 px-4 font-space-grotesk">0123456789</td>
                            <td class="py-4 px-4 font-space-grotesk">@#$%&*!?</td>
                        </tr>
                        <tr class="border-b" style="border-color: #eee;">
                            <td class="py-4 px-4 font-medium font-lexend" style="color: var(--tlc-navy);">Lexend</td>
                            <td class="py-4 px-4 font-lexend">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz</td>
                            <td class="py-4 px-4 font-lexend">0123456789</td>
                            <td class="py-4 px-4 font-lexend">@#$%&*!?</td>
                        </tr>
                        <tr>
                            <td class="py-4 px-4 font-medium font-sora" style="color: var(--tlc-navy);">Sora</td>
                            <td class="py-4 px-4 font-sora">ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz</td>
                            <td class="py-4 px-4 font-sora">0123456789</td>
                            <td class="py-4 px-4 font-sora">@#$%&*!?</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-8" style="background-color: var(--tlc-navy);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm" style="color: var(--tlc-cream);">Typography Exploration for TLC 2.0</p>
            <p class="text-xs mt-2" style="color: var(--tlc-gold);">Select a font above to see it applied throughout the preview</p>
        </div>
    </footer>

    <script>
        const fonts = {
            'dm-sans': { name: 'DM Sans', class: 'font-dm-sans' },
            'space-grotesk': { name: 'Space Grotesk', class: 'font-space-grotesk' },
            'lexend': { name: 'Lexend', class: 'font-lexend' },
            'sora': { name: 'Sora', class: 'font-sora' }
        };

        let currentFont = 'dm-sans';

        function selectFont(fontKey) {
            // Remove selection from all cards
            document.querySelectorAll('.font-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.querySelectorAll('[id^="check-"]').forEach(check => {
                check.classList.add('hidden');
            });

            // Add selection to clicked card
            document.getElementById('card-' + fontKey).classList.add('selected');
            document.getElementById('check-' + fontKey).classList.remove('hidden');

            // Update preview sections
            const previewContainer = document.getElementById('preview-container');
            const scaleContainer = document.getElementById('scale-container');

            // Remove old font class
            Object.values(fonts).forEach(font => {
                previewContainer.classList.remove(font.class);
                scaleContainer.classList.remove(font.class);
            });

            // Add new font class
            previewContainer.classList.add(fonts[fontKey].class);
            scaleContainer.classList.add(fonts[fontKey].class);

            // Also set the font-family directly for immediate effect
            const fontFamily = getComputedStyle(document.querySelector('.' + fonts[fontKey].class)).fontFamily;
            previewContainer.style.fontFamily = fontFamily;
            scaleContainer.style.fontFamily = fontFamily;

            // Update label
            document.getElementById('current-font-label').textContent = fonts[fontKey].name;

            currentFont = fontKey;
        }

        // Select DM Sans by default
        document.addEventListener('DOMContentLoaded', function() {
            selectFont('dm-sans');
        });
    </script>
</body>
</html>
