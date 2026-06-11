<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SPES 2026 Attendance System' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: {
                            DEFAULT: '#162241',
                            soft: '#26365A',
                        },
                        gold: {
                            DEFAULT: '#E2A53E',
                            soft: '#FBEBCB',
                            text: '#9A6B14',
                        },
                        paper: '#F5F2EC',
                        cardline: '#E6E2D8',
                        slate: {
                            DEFAULT: '#74808F',
                            light: '#9AA3AF',
                        },
                        success: {
                            DEFAULT: '#3E9C6D',
                            soft: '#E6F5EC',
                            text: '#2E7D52',
                        },
                        danger: {
                            DEFAULT: '#D5594A',
                            soft: '#FBEAE7',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>

    <style>
        @keyframes scan{
            0%, 100%{ top: 6px; opacity:.9; }
            50%{ top: 56px; opacity:.4; }
        }
        @keyframes blink{ 50%{ opacity:0; } }
        .scan-line{
            position:absolute; left:0; right:0; height:2px;
            background: linear-gradient(90deg, transparent, #E2A53E, transparent);
            animation: scan 2.6s ease-in-out infinite;
        }
        .cursor-blink{
            display:inline-block; width:7px; height:13px;
            background: #E2A53E; margin-left:4px; vertical-align:-2px;
            animation: blink 1s step-end infinite;
        }
    </style>
</head>
<body class="bg-paper min-h-screen flex flex-col font-sans text-ink [background-image:radial-gradient(circle_at_1px_1px,rgba(22,34,65,0.05)_1px,transparent_0)] [background-size:22px_22px]">

    {{-- BRAND BAR --}}
    <header class="bg-gradient-to-br from-ink to-ink-soft text-white">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between gap-3">
            <a href="{{ url('/dashboard') }}" class="group flex items-center gap-3 rounded-lg -mx-2 px-2 py-1 transition-colors duration-200 hover:bg-white/10">
                <div class="bg-gold text-ink p-2 rounded-lg shadow-md flex items-center justify-center transition-transform duration-300 ease-out group-hover:scale-110 group-hover:-rotate-6 group-hover:shadow-gold/40">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <line x1="12" y1="20" x2="12" y2="10"/>
                        <line x1="18" y1="20" x2="18" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="16"/>
                    </svg>
                </div>
                <div>
                    <p class="font-display font-semibold text-sm leading-tight transition-colors duration-200 group-hover:text-gold">SPES 2026</p>
                    <p class="text-[11px] text-[#AEB9D6] tracking-wide leading-tight transition-colors duration-200 group-hover:text-white">Attendance Management System</p>
                </div>
            </a>
        </div>
    </header>

    <div class="flex-1 flex flex-col">
        <main class="p-6 flex-1 flex justify-center items-start">
            {{ $slot }}
        </main>
    </div>

    {{-- FOOTER --}}
    <footer class="border-t border-cardline bg-white">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate font-mono">
            <p>&copy; {{ date('Y') }} SPES 2026 — Special Program for Employment of Students</p>
            <p class="flex items-center gap-2 px-2.5 py-1 rounded-full transition-colors duration-200 hover:bg-success-soft hover:text-success-text cursor-default">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                System Live
            </p>
        </div>
    </footer>

</body>
</html>