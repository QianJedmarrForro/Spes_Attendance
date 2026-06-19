<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SPES 2026 Attendance System' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // SPES logo palette
                        navy:    { DEFAULT: '#0A1628', soft: '#0F2040' },
                        sblue:   { DEFAULT: '#1E90D4', light: '#38BDF8' },
                        gold:    { DEFAULT: '#E2A53E', soft: '#FEF3DC', text: '#8A5D00' },
                        // Keep legacy names for compatibility
                        ink:     { DEFAULT: '#0A1628', soft: '#0F2040' },
                        paper:   '#EBF3FB',
                        cardline:'#D0E4F5',
                        slate:   { DEFAULT: '#6B8EAE', light: '#8AADC8' },
                        success: { DEFAULT: '#1A7A4A', soft: '#DCFCE7', text: '#166534' },
                        danger:  { DEFAULT: '#991B1B', soft: '#FEE2E2' },
                    },
                    fontFamily: {
                        sans:    ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono:    ['"JetBrains Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>

    <style>
        @keyframes scan {
            0%, 100% { top: 6px; opacity: .9; }
            50%       { top: 56px; opacity: .4; }
        }
        @keyframes blink { 50% { opacity: 0; } }

        .scan-line {
            position: absolute; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, #38BDF8, transparent);
            animation: scan 2.6s ease-in-out infinite;
        }
        .cursor-blink {
            display: inline-block; width: 7px; height: 13px;
            background: #38BDF8; margin-left: 4px; vertical-align: -2px;
            animation: blink 1s step-end infinite;
        }

        /* Premium top accent bar */
        .nav-accent {
            height: 3px;
            background: linear-gradient(90deg, #0A1628 0%, #1E90D4 40%, #38BDF8 70%, #E2A53E 100%);
        }

        /* Nav logo ring pulse */
        @keyframes ring-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(56,189,248,0.4); }
            50%       { box-shadow: 0 0 0 6px rgba(56,189,248,0); }
        }
        .logo-ring { animation: ring-pulse 3s ease-in-out infinite; }
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans"
      style="background-color:#EBF3FB;
             background-image: radial-gradient(circle at 1px 1px, rgba(30,144,212,0.07) 1px, transparent 0);
             background-size: 22px 22px;
             color:#0A1628">

    {{-- Premium accent line --}}
    <div class="nav-accent"></div>

    {{-- BRAND BAR --}}
    <header style="background: linear-gradient(135deg, #0A1628 0%, #0F2040 60%, #1a3060 100%)"
            class="text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between gap-3">

            
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">
        <main class="p-6 flex-1 flex justify-center items-start">
            {{ $slot }}
        </main>
    </div>

    {{-- FOOTER --}}
    <footer style="border-top:1px solid #D0E4F5; background:white">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row
                    items-center justify-between gap-2 text-xs font-mono"
             style="color:#6B8EAE">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/SPES_LOGO.png') }}"
                     alt="SPES"
                     class="w-5 h-5 rounded-full object-cover opacity-70">
                <p>Special Program for Employment of Students</p>
            </div>
            <p class="flex items-center gap-2 px-2.5 py-1 rounded-full cursor-default
                       transition-colors duration-200 hover:bg-green-50 hover:text-green-700">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full
                                 bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                System Live
            </p>
        </div>
    </footer>

    <script>
        function updateLayoutClock() {
            const el = document.getElementById('layout-clock');
            if (el) {
                el.textContent = new Date().toLocaleTimeString('en-PH', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
                });
            }
        }
        setInterval(updateLayoutClock, 1000);
        updateLayoutClock();
    </script>

</body>
</html>