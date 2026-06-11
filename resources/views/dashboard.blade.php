<x-layout>
    <x-slot:title>SPES 2026 Admin Dashboard</x-slot:title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --ink:#162241;
            --ink-soft:#26365A;
            --gold:#E2A53E;
        }
        .font-display{ font-family:'Space Grotesk', sans-serif; }
        .font-mono-c{ font-family:'JetBrains Mono', monospace; }
        body{ font-family:'Inter', sans-serif; }

        @keyframes blink{ 50%{ opacity:0; } }
        .cursor-blink{
            display:inline-block; width:7px; height:13px;
            background: var(--gold); margin-left:4px; vertical-align:-2px;
            animation: blink 1s step-end infinite;
        }

        .stat-card{ transition: transform .2s ease, box-shadow .2s ease; }
        .stat-card:hover{ transform: translateY(-2px); box-shadow: 0 10px 25px -10px rgba(22,34,65,0.18); }
    </style>

    <div class="min-h-screen bg-[#F5F2EC] font-sans pb-12 [background-image:radial-gradient(circle_at_1px_1px,rgba(22,34,65,0.05)_1px,transparent_0)] [background-size:22px_22px]">

        {{-- TOP BAR --}}
        <nav class="bg-gradient-to-br from-[#162241] to-[#26365A] px-6 py-4 flex flex-wrap gap-3 justify-between items-center shadow-md text-white w-full">
            <div class="flex items-center space-x-3">
                <div class="bg-[#E2A53E] text-[#162241] p-2.5 rounded-xl shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                        <line x1="12" y1="20" x2="12" y2="10"/>
                        <line x1="18" y1="20" x2="18" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-display font-bold tracking-tight leading-tight">SPES 2026 Attendance Console</h1>
                    <p class="text-xs text-[#AEB9D6] tracking-wide">System Dashboard Overview</p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <span id="live-time" class="flex items-center justify-center gap-2 text-xs font-mono-c bg-white/10 border border-white/10 px-3 py-1.5 rounded-full min-w-[88px]">
                    --:--:-- --
                </span>
                <span class="flex items-center gap-2 text-xs font-mono-c bg-white/10 border border-white/10 px-3 py-1.5 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ now()->format('F j, Y') }}
                </span>
                <a href="{{ url('/') }}" class="text-xs bg-[#FBEAE7] text-[#D5594A] hover:bg-[#fadcd8] px-4 py-1.5 rounded-full font-bold transition">
                    Exit Panel
                </a>
            </div>
        </nav>

        <div class="px-6 max-w-7xl mx-auto mt-6">
            <div class="flex items-end justify-between flex-wrap gap-2">
                <div>
                    <h2 class="font-display text-2xl font-bold text-[#162241]">Welcome back, Admin</h2>
                    <p class="text-sm text-[#74808F]">Here's today's attendance summary at a glance.</p>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-[#E6E2D8] p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-4 h-0.5 bg-[#E2A53E] rounded-full inline-block"></span>
                        <h3 class="text-[11px] font-bold text-[#74808F] uppercase tracking-[0.14em] font-mono-c">Student ID Verification</h3>
                    </div>

                    @if(isset($student) && $student)
                        <div class="text-center py-4" id="printSection">
                            <div id="badgeToPrint">
                                <h2 class="font-display font-bold text-[#162241] text-lg leading-snug">{{ $student->name }}</h2>
                                <p class="font-mono-c text-sm text-[#74808F] mb-4">{{ $student->student_id_number }}</p>

                                <div class="flex justify-center p-3 border border-dashed border-[#E6E2D8] rounded-xl bg-[#FCFBF8]">
                                    @if(!empty($student->qr_token))
                                        {!! QrCode::size(160)->generate($student->qr_token) !!}
                                    @else
                                        <div class="p-4 text-center">
                                            <p class="text-[10px] text-[#D5594A] font-bold uppercase">Token Missing</p>
                                            <p class="text-[10px] text-[#74808F]">Record lacks scan token.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <button onclick="printBadge()" class="mt-4 w-full bg-[#162241] hover:bg-[#26365A] text-white font-semibold text-sm py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <polyline points="6 9 6 2 18 2 18 9"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <rect x="6" y="14" width="12" height="8"/>
                                </svg>
                                Print Badge
                            </button>
                            <a href="{{ route('dashboard.index') }}" class="mt-3 block text-[11px] text-[#74808F] underline hover:text-[#162241]">Verify another ID</a>
                        </div>
                    @else
                        <h2 class="font-display font-semibold text-[#162241] text-base mb-1 mt-2">Generate Beneficiary Badge</h2>
                        <p class="text-xs text-[#74808F] mb-4">Enter a student ID to generate a printable QR badge.</p>
                        <form action="{{ route('dashboard.store') }}" method="POST" class="w-full space-y-2.5">
                            @csrf
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#AEB9D6]">
                                    <rect x="3" y="3" width="7" height="7"/>
                                    <rect x="14" y="3" width="7" height="7"/>
                                    <rect x="3" y="14" width="7" height="7"/>
                                </svg>
                                <input type="text" name="student_id_number"
                                    placeholder="e.g., SA-DOR-2026-8299"
                                    class="w-full text-sm pl-9 pr-3 py-2.5 border border-[#E6E2D8] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E2A53E]/40 font-mono-c bg-[#FCFBF8] placeholder:text-[#C7C2B6]">
                            </div>
                            <button type="submit" class="w-full bg-[#162241] hover:bg-[#26365A] text-white font-display font-semibold text-sm py-2.5 rounded-xl transition">
                                Generate QR
                            </button>
                        </form>
                    @endif
                </div>

                {{-- ATTENDANCE METRICS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-[#E6E2D8] p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-4 h-0.5 bg-[#E2A53E] rounded-full inline-block"></span>
                        <h3 class="text-[11px] font-bold text-[#74808F] uppercase tracking-[0.14em] font-mono-c">Attendance Metrics</h3>
                    </div>

                    @php
                        $total = $totalEnrolled ?? 0;
                        $present = $presentToday ?? 0;
                        $absent = max($total - $present, 0);
                        $rate = $total > 0 ? round(($present / $total) * 100) : 0;
                    @endphp

                    <div class="space-y-3">
                        <div class="stat-card border border-[#E6E2D8] rounded-xl p-4 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-[#74808F] mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#E2A53E] inline-block"></span> Total Beneficiaries
                                </div>
                                <div class="font-display text-3xl font-bold text-[#162241]">{{ $total }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#E2A53E" stroke-width="1.5" class="w-9 h-9 opacity-70">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 4v-2a4 4 0 00-3-3.87M9 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="stat-card bg-[#E6F5EC] rounded-xl p-4">
                                <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-[#74808F] mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#3E9C6D] inline-block"></span> Present
                                </div>
                                <div class="font-display text-3xl font-bold text-[#3E9C6D]">{{ $present }}</div>
                            </div>
                            <div class="stat-card bg-[#FBEAE7] rounded-xl p-4">
                                <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-[#74808F] mb-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#D5594A] inline-block"></span> Absent
                                </div>
                                <div class="font-display text-3xl font-bold text-[#D5594A]">{{ $absent }}</div>
                            </div>
                        </div>

                        <div class="border border-[#E6E2D8] rounded-xl p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#74808F]">Attendance Rate</span>
                                <span class="font-mono-c text-xs font-semibold text-[#162241]">{{ $rate }}%</span>
                            </div>
                            <div class="w-full h-2 bg-[#F0EDE5] rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#E2A53E] to-[#3E9C6D] rounded-full transition-all duration-500" style="width: {{ $rate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-[#E6E2D8] overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E6E2D8] flex justify-between items-center bg-[#FCFBF8]">
                        <div class="flex items-center space-x-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <h3 class="font-display font-semibold text-[#162241]">Live Terminal Events</h3>
                        </div>
                        <span class="text-xs font-mono-c text-[#74808F]">{{ count($liveEvents ?? []) }} events today</span>
                    </div>
                    <div class="overflow-y-auto max-h-[28rem]">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-[#FCFBF8] z-10 border-b border-[#E6E2D8]">
                                <tr class="text-[11px] font-bold text-[#74808F] uppercase tracking-wider font-mono-c">
                                    <th class="p-3 pl-4">Beneficiary</th>
                                    <th class="p-3">Time</th>
                                    <th class="p-3 pr-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-[#F0EDE5] text-[#74808F]">
                                @forelse($liveEvents ?? [] as $event)
                                    <tr class="hover:bg-[#FCFBF8] transition">
                                        <td class="p-3 pl-4">
                                            <div class="font-semibold text-[#162241] leading-snug">{{ $event->user->name ?? 'System Guest' }}</div>
                                            <div class="font-mono-c text-xs text-[#9CA6BC]">{{ $event->user->student_id_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="p-3 font-mono-c text-xs">{{ \Carbon\Carbon::parse($event->scanned_at)->format('h:i:s A') }}</td>
                                        <td class="p-3 pr-4">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#E6F5EC] rounded text-[10px] uppercase font-bold text-[#3E9C6D]">
                                                <span class="w-1 h-1 rounded-full bg-[#3E9C6D]"></span> Verified
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-10 text-center">
                                            <p class="font-mono-c text-xs text-[#74808F]">No scan transactions logged today<span class="cursor-blink"></span></p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="max-w-7xl mx-auto px-6 mt-2 flex flex-wrap justify-between items-center text-xs text-[#9CA6BC] font-mono-c">
            <span>&copy; {{ date('Y') }} SPES 2026 — Special Program for Employment of Students</span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span> System Live
            </span>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-PH', {
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            document.getElementById('live-time').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        function printBadge() {
            const printContent = document.getElementById('badgeToPrint').innerHTML;
            const printWindow = window.open('', '_blank', 'width=420,height=600');

            printWindow.document.write(`
                <html>
                    <head>
                        <title>Attendance Badge</title>
                        <style>
                            body { font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
                            .badge { text-align: center; border: 1px solid #E6E2D8; padding: 32px; border-radius: 16px; width: 260px; }
                            h2 { color: #162241; margin: 0 0 4px; font-size: 18px; }
                            p { color: #74808F; font-size: 13px; margin: 0 0 16px; }
                        </style>
                    </head>
                    <body>
                        <div class="badge">${printContent}</div>
                    </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();
            printWindow.onload = function () {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
</x-layout>