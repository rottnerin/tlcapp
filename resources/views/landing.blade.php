<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TLC Professional Learning</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/landing.js'])
    <style>
        :root {
            --tlc-navy: #0d3b66;
            --tlc-cream: #faf0ca;
            --tlc-gold: #f4d35e;
            --tlc-orange: #ee964b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 100%; height: 100%; overflow: hidden; }
        body.landing-page {
            font-family: 'Lexend', ui-sans-serif, system-ui, sans-serif;
            background: #000 !important;
            background-color: #000 !important;
            -webkit-tap-highlight-color: transparent;
        }
        #landing-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
        }
        #landing-scene canvas {
            display: block;
            width: 100%;
            height: 100%;
        }
        .landing-cta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 1.5rem 1.5rem max(1.5rem, env(safe-area-inset-bottom));
            display: flex;
            justify-content: center;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        body.landing-formed .landing-cta {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .landing-cta a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 2.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            background: var(--tlc-orange);
            border-radius: 9999px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .landing-cta a:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        .landing-cta a:focus {
            outline: none;
            box-shadow: 0 0 0 3px var(--tlc-gold);
        }
    </style>
</head>
<body class="landing-page">
    <div id="landing-scene" aria-hidden="true"></div>
    <nav class="landing-cta" aria-label="Continue to sign in">
        <a href="{{ route('login') }}">Continue</a>
    </nav>
</body>
</html>
