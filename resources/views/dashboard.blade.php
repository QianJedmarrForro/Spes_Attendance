<x-layout>
    <x-slot:title>SPES 2026 Admin Dashboard</x-slot:title>

    <!-- ⭐ CRITICAL: ADD THIS VIEWPORT META TAG -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SPES 2026 Attendance Console">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --navy:   #0A1628;
            --navy2:  #0F2040;
            --blue:   #1E90D4;
            --cyan:   #38BDF8;
            --gold:   #E2A53E;
            --light:  #F0F6FF;
            --border: #D0E4F5;
            --muted:  #6B8EAE;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1c4064;
            font-size: 16px; /* Better for mobile */
        }

        /* Mobile-first typography */
        h2 { font-size: clamp(18px, 5vw, 24px); }
        h3 { font-size: clamp(14px, 4vw, 18px); }
        p { font-size: clamp(12px, 3.5vw, 14px); }

        /* Responsive padding */
        .px-6 { padding-left: clamp(12px, 4vw, 24px); padding-right: clamp(12px, 4vw, 24px); }

        #calendar-dropdown {
            display: none; position: absolute; z-index: 50;
            background: white; border: 0.5px solid var(--border);
            border-radius: 14px; padding: 16px;
            box-shadow: 0 12px 32px rgba(10,22,40,0.15);
            width: 280px; right: 0; top: calc(100% + 8px);
        }

        @media (max-width: 640px) {
            #calendar-dropdown {
                width: calc(100vw - 32px);
                right: auto;
                left: -40px;
            }
        }

        #calendar-dropdown.open { display: block; }

        .cal-day {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 12px; cursor: pointer;
            transition: background .15s; color: var(--navy);
        }

        .cal-day:hover  { background: var(--light); }
        .cal-day.today  { background: var(--navy); color: white; font-weight: 600; }
        .cal-day.selected { background: var(--blue); color: white; font-weight: 600; }
        .cal-day.empty  { cursor: default; }

        .stat-card { transition: transform .2s ease, box-shadow .2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(30,144,212,0.12); }

        .nav-glow { height: 3px; background: linear-gradient(90deg, var(--navy) 0%, var(--blue) 40%, var(--cyan) 70%, var(--gold) 100%); }

        tbody tr:hover { background: #F5FAFF; }

        .badge-present { background: #DCFCE7; color: #166534; }
        .badge-late    { background: #FEF9C3; color: #854D0E; }
        .badge-absent  { background: #FEE2E2; color: #991B1B; }

        /* ⭐ MOBILE OPTIMIZATIONS */

        /* Navbar responsive */
        nav {
            flex-direction: column;
            gap: 12px !important;
        }

        @media (min-width: 768px) {
            nav {
                flex-direction: row;
                gap: 20px !important;
            }
        }

        /* Navbar items wrap better */
        nav .flex {
            width: 100%;
            justify-content: space-between;
        }

        @media (min-width: 768px) {
            nav .flex {
                width: auto;
            }
        }

        /* Table toolbar - stack on mobile */
        .table-toolbar {
            flex-direction: column;
            gap: 12px;
        }

        @media (min-width: 768px) {
            .table-toolbar {
                flex-direction: row;
                gap: 16px;
            }
        }

        /* Search and filter inputs */
        .toolbar-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .toolbar-controls {
                flex-direction: row;
                width: auto;
            }
        }

        #searchInput {
            width: 100%;
        }

        @media (min-width: 768px) {
            #searchInput {
                width: 200px;
            }
        }

        /* Table responsive */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Smooth scroll on iOS */
        }

        table {
            font-size: clamp(10px, 2.5vw, 12px);
        }

        /* Hide columns on mobile, show on tablet+ */
        @media (max-width: 640px) {
            th:nth-child(4),
            th:nth-child(5),
            th:nth-child(6),
            th:nth-child(7),
            td:nth-child(4),
            td:nth-child(5),
            td:nth-child(6),
            td:nth-child(7) {
                display: none;
            }

            /* Reduce column widths on mobile */
            th:nth-child(1), td:nth-child(1) { min-width: 80px; }
            th:nth-child(2), td:nth-child(2) { min-width: 120px; }
            th:nth-child(3), td:nth-child(3) { min-width: 70px; }
            th:nth-child(8), td:nth-child(8) { min-width: 70px; }
        }

        /* Metrics grid - responsive */
        .metrics-grid {
            grid-template-columns: 1fr;
        }

        @media (min-width: 640px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .metrics-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Card responsiveness */
        .card {
            border-radius: 12px;
            padding: clamp(12px, 4vw, 20px);
        }

        /* Button responsiveness */
        button {
            font-size: clamp(12px, 3vw, 14px);
            padding: clamp(8px, 2.5vw, 12px) clamp(12px, 3vw, 20px);
        }

        .btn-full {
            width: 100%;
        }

        @media (min-width: 640px) {
            .btn-full {
                width: auto;
            }
        }

        /* Print styles */
        @media print {
            body { background: white; margin: 0; padding: 40px 20px; width: 100%; }
            .nav-glow, nav,
            .px-6.max-w-7xl.mx-auto.mt-7.mb-5,
            .px-6.max-w-7xl.mx-auto.grid.grid-cols-1.md\:grid-cols-2.gap-5.mb-5 > :last-child,
            .px-6.max-w-7xl.mx-auto:last-of-type { display: none !important; }
            .px-6.max-w-7xl.mx-auto.grid.grid-cols-1.md\:grid-cols-2.gap-5.mb-5 {
                display: block !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important;
            }
            .px-6.max-w-7xl.mx-auto.grid.grid-cols-1.md\:grid-cols-2.gap-5.mb-5 > :first-child {
                display: block !important; border: none !important; padding: 0 !important;
                background: white !important; border-radius: 0 !important; box-shadow: none !important;
            }
            svg { display: block !important; }
            img { display: block !important; }
            p, h2, span, div, button, a, form, input { display: block !important; }
            button, a.flex-1 { display: inline-block !important; }
            .flex { display: flex !important; }
            .text-center { text-align: center !important; }
        }

        /* Touch-friendly elements */
        @media (max-width: 768px) {
            button, a, input {
                min-height: 44px; /* Apple's recommended touch target */
            }
        }
    </style>

    <div class="min-h-screen pb-16">

        <div class="nav-glow"></div>

        <nav style="background: var(--navy); border-radius: 0 0 16px 16px;"
            class="px-6 py-4 flex flex-wrap gap-3 justify-between items-center shadow-lg">

            <div class="flex items-center gap-3">
                <img src="{{ asset('images/SPES_LOGO.png') }}" alt="SPES Logo" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full">
                <div>
                    <p class="text-[13px] sm:text-[15px] font-bold text-white leading-tight tracking-tight">SPES 2026 Attendance Console</p>
                    <p class="text-[9px] sm:text-[11px]" style="color: var(--cyan)">Special Program for Employment of Students</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
                <span id="live-time" class="text-[10px] sm:text-xs font-mono px-2 sm:px-3 py-1 sm:py-1.5 rounded-full border"
                    style="color:var(--cyan); border-color:rgba(56,189,248,0.3); background:rgba(56,189,248,0.08); white-space: nowrap;"></span>
                <span class="text-[10px] sm:text-xs px-2 sm:px-3 py-1 sm:py-1.5 rounded-full border font-mono hidden sm:inline"
                    style="color:#94A3B8; border-color:rgba(255,255,255,0.1); background:rgba(255,255,255,0.05)">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y') }}
                </span>
                <a href="{{ url('/') }}" class="text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full transition"
                    style="background:rgba(226,165,62,0.15); color:var(--gold); border: 1px solid rgba(226,165,62,0.3); white-space: nowrap;">
                    Exit
                </a>
            </div>
        </nav>

        <div class="px-6 max-w-7xl mx-auto mt-5 sm:mt-7 mb-4 sm:mb-5">
            <h2 class="font-bold" style="color:var(--navy)">Welcome to the Attendance Console</h2>
            <p class="text-xs sm:text-sm mt-0.5" style="color:var(--muted)">Here's today's attendance summary at a glance.</p>
        </div>

        {{-- Top cards --}}
        <div class="px-6 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 mb-4 sm:mb-5">

            {{-- ID Verification --}}
            <div class="bg-white rounded-2xl border p-4 sm:p-5" style="border-color:var(--border)">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-block w-1 h-4 rounded-full" style="background:var(--blue)"></span>
                    <p class="text-[9px] sm:text-[11px] font-semibold uppercase tracking-[.07em]" style="color:var(--muted)">SPES ID Verification</p>
                </div>

                @if(isset($student) && $student)
                    <div class="text-center py-2">
                        <p class="font-bold text-base sm:text-lg" style="color:var(--navy)">{{ $student->first_name }} {{ $student->last_name }}</p>
                        <p class="font-mono text-xs sm:text-sm mb-3 sm:mb-4" style="color:var(--muted)">{{ $student->student_id_number }}</p>
                        <div class="flex justify-center p-3 sm:p-4 rounded-xl border border-dashed" style="border-color:var(--border); background:var(--light)">
                            {!! QrCode::size(200)->generate($student->qr_token) !!}
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 mt-3 sm:mt-4">
                            <button onclick="window.print()" class="flex-1 text-white py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition hover:opacity-85"
                                style="background:var(--navy)">Print Badge</button>
                            <a href="{{ route('IndividualAttendance.index') }}" class="flex-1 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-center border transition hover:opacity-75"
                                style="color:var(--navy); border-color:var(--border); background:var(--light)">Generate Another</a>
                        </div>
                    </div>
                @else
                    <form action="{{ route('dashboard.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="student_id_number" placeholder="Enter SPES ID"
                            class="w-full text-xs sm:text-sm px-3 py-2.5 rounded-xl outline-none transition"
                            style="border:1px solid var(--border); background:var(--light); color:var(--navy)" required>
                        <button type="submit" class="w-full text-white py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:opacity-85 transition"
                            style="background: linear-gradient(135deg, var(--navy) 0%, var(--blue) 100%)">
                            Generate QR Badge
                        </button>
                    </form>
                @endif
            </div>

            {{-- Metrics --}}
            <div class="bg-white rounded-2xl border p-4 sm:p-5" style="border-color:var(--border)">
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-block w-1 h-4 rounded-full" style="background:var(--blue)"></span>
                    <p class="text-[9px] sm:text-[11px] font-semibold uppercase tracking-[.07em]" style="color:var(--muted)">Attendance Metrics</p>
                </div>

                <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-3 sm:mb-4">
                    <div class="stat-card rounded-xl p-3 sm:p-4 text-center" style="background:var(--light); border:1px solid var(--border)">
                        <div class="text-[8px] sm:text-[10px] uppercase font-semibold mb-1" style="color:var(--muted)">Total Beneficiary</div>
                        <div class="text-2xl sm:text-3xl font-bold" id="metric-total" style="color:var(--navy)">{{ $totalEnrolled ?? 0 }}</div>
                    </div>
                    <div class="stat-card rounded-xl p-3 sm:p-4 text-center" style="background:#EFF8FF; border:1px solid #BFDBFE">
                        <div class="text-[8px] sm:text-[10px] uppercase font-semibold mb-1" style="color:var(--blue)">Present</div>
                        <div class="text-2xl sm:text-3xl font-bold" id="metric-present" style="color:var(--blue)">{{ $presentToday ?? 0 }}</div>
                    </div>
                </div>

                <p class="text-[8px] sm:text-[10px] font-semibold uppercase tracking-[.06em] mb-2" style="color:var(--muted)">AM Session</p>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div class="stat-card rounded-xl p-2 sm:p-3 text-center" style="background:#ECFDF5; border:1px solid #A7F3D0">
                        <div class="text-[7px] sm:text-[9px] uppercase font-semibold mb-1" style="color:#166534">Ontime</div>
                        <div class="text-xl sm:text-2xl font-bold" id="metric-am-ontime" style="color:#166534">{{ $amOntime ?? 0 }}</div>
                    </div>
                    <div class="stat-card rounded-xl p-2 sm:p-3 text-center" style="background:#FEFCE8; border:1px solid #FDE68A">
                        <div class="text-[7px] sm:text-[9px] uppercase font-semibold mb-1" style="color:#92400E">Late</div>
                        <div class="text-xl sm:text-2xl font-bold" id="metric-am-late" style="color:#92400E">{{ $amLate ?? 0 }}</div>
                    </div>
                    <div class="stat-card rounded-xl p-2 sm:p-3 text-center" style="background:#FEF2F2; border:1px solid #FECACA">
                        <div class="text-[7px] sm:text-[9px] uppercase font-semibold mb-1" style="color:#991B1B">Absent</div>
                        <div class="text-xl sm:text-2xl font-bold" id="metric-am-absent" style="color:#991B1B">{{ $amAbsent ?? 0 }}</div>
                    </div>
                </div>

                <p class="text-[8px] sm:text-[10px] font-semibold uppercase tracking-[.06em] mb-2" style="color:var(--muted)">PM Session</p>
                <div class="grid grid-cols-3 gap-2">
                    <div class="stat-card rounded-xl p-2 sm:p-3 text-center" style="background:#ECFDF5; border:1px solid #A7F3D0">
                        <div class="text-[7px] sm:text-[9px] uppercase font-semibold mb-1" style="color:#166534">Ontime</div>
                        <div class="text-xl sm:text-2xl font-bold" id="metric-pm-ontime" style="color:#166534">{{ $pmOntime ?? 0 }}</div>
                    </div>
                    <div class="stat-card rounded-xl p-2 sm:p-3 text-center" style="background:#FEFCE8; border:1px solid #FDE68A">
                        <div class="text-[7px] sm:text-[9px] uppercase font-semibold mb-1" style="color:#92400E">Late</div>
                        <div class="text-xl sm:text-2xl font-bold" id="metric-pm-late" style="color:#92400E">{{ $pmLate ?? 0 }}</div>
                    </div>
                    <div class="stat-card rounded-xl p-2 sm:p-3 text-center" style="background:#FEF2F2; border:1px solid #FECACA">
                        <div class="text-[7px] sm:text-[9px] uppercase font-semibold mb-1" style="color:#991B1B">Absent</div>
                        <div class="text-xl sm:text-2xl font-bold" id="metric-pm-absent" style="color:#991B1B">{{ $pmAbsent ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Table --}}
        <div class="px-6 max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:var(--border)">

                {{-- Table toolbar --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b table-toolbar"
                    style="border-color:var(--border); background:var(--light)">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/SPES_LOGO.png') }}" alt="SPES" class="w-6 h-6 rounded-full object-cover">
                        <h3 class="font-bold text-sm" style="color:var(--navy)">SPES Attendance History</h3>
                    </div>
                    <div class="toolbar-controls">

                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search Name or ID"
                            class="text-xs sm:text-sm px-3 py-2 rounded-xl outline-none transition"
                            style="border:1px solid var(--border); background:white; color:var(--navy)">

                        <div class="relative" id="date-picker-wrap">
                            <button onclick="toggleCalendar()" class="flex items-center gap-2 text-xs sm:text-sm font-medium px-3 py-2 rounded-xl transition hover:opacity-80 w-full sm:w-auto"
                                style="border:1px solid var(--border); background:white; color:var(--navy)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:var(--blue)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span id="selected-date-label">{{ \Carbon\Carbon::today()->format('M d, Y') }}</span>
                            </button>

                            <div id="calendar-dropdown">
                                <div class="flex items-center justify-between mb-3">
                                    <button onclick="changeMonth(-1)" class="p-1 rounded-lg hover:bg-blue-50" style="color:var(--navy)">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                                    </button>
                                    <span id="cal-month-label" class="text-xs sm:text-sm font-semibold" style="color:var(--navy)"></span>
                                    <button onclick="changeMonth(1)" class="p-1 rounded-lg hover:bg-blue-50" style="color:var(--navy)">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center mb-1">
                                    @foreach(['S','M','T','W','T','F','S'] as $d)
                                        <div class="text-[9px] sm:text-[10px] font-semibold" style="color:var(--muted)">{{ $d }}</div>
                                    @endforeach
                                </div>
                                <div id="cal-days" class="grid grid-cols-7 gap-1 text-center"></div>
                                <div class="mt-3 pt-3" style="border-top:1px solid var(--border)">
                                    <button onclick="goToToday()" class="w-full text-xs font-semibold py-2 rounded-lg transition hover:opacity-80"
                                        style="background:var(--light); color:var(--navy)">
                                        Go to Today
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button onclick="printTable()" class="flex items-center justify-center gap-2 text-xs sm:text-sm font-semibold text-white px-3 sm:px-4 py-2 rounded-xl transition hover:opacity-85 w-full sm:w-auto"
                            style="background: linear-gradient(135deg, var(--navy) 0%, var(--blue) 100%)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            <span class="hidden sm:inline">Print Register</span>
                            <span class="sm:hidden">Print</span>
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-wrapper">
                    <table class="w-full text-sm text-left" style="table-layout:fixed">
                        <thead style="background:var(--light)">
                            <tr>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[80px]" style="color:var(--muted)">SPES ID</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[120px]" style="color:var(--muted)">Name</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[70px]" style="color:var(--muted)">AM In</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[70px]" style="color:var(--muted)">AM Out</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[70px]" style="color:var(--muted)">AM Status</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[70px]" style="color:var(--muted)">PM In</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[70px]" style="color:var(--muted)">PM Out</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-[8px] sm:text-[10px] uppercase font-semibold min-w-[70px]" style="color:var(--muted)">PM Status</th>
                            </tr>
                        </thead>
                        <tbody id="logsTable" class="divide-y text-[10px] sm:text-[11px]" style="border-color:#EBF3FB">
                            @foreach($students as $s)
                                @php
                                    $log = $s->attendances->first();
                                    $mStatus = !$log || !$log->time_in ? 'Absent'
                                        : (\Carbon\Carbon::parse($log->time_in)->format('H:i') > '08:00' ? 'Late' : 'Present');
                                    $aStatus = !$log || !$log->time_in_pm ? 'Absent'
                                        : (\Carbon\Carbon::parse($log->time_in_pm)->format('H:i') > '13:00' ? 'Late' : 'Present');
                                    $mBadge = $mStatus == 'Present' ? 'badge-present' : ($mStatus == 'Late' ? 'badge-late' : 'badge-absent');
                                    $aBadge = $aStatus == 'Present' ? 'badge-present' : ($aStatus == 'Late' ? 'badge-late' : 'badge-absent');
                                @endphp
                                <tr style="border-color:#EBF3FB; transition: background .15s ease">
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 font-mono text-[10px] sm:text-[12px]" style="color:var(--muted)">{{ $s->student_id_number }}</td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 font-semibold" style="color:var(--navy)">{{ $s->first_name }} {{ $s->last_name }}</td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3" style="color:var(--navy)">{{ $log?->time_in    ? \Carbon\Carbon::parse($log->time_in)->format('h:i A')    : '--' }}</td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3" style="color:var(--navy)">{{ $log?->time_out   ? \Carbon\Carbon::parse($log->time_out)->format('h:i A')   : '--' }}</td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3"><span class="px-2 py-0.5 rounded-full text-[8px] sm:text-[9px] font-bold {{ $mBadge }}">{{ $mStatus }}</span></td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3" style="color:var(--navy)">{{ $log?->time_in_pm  ? \Carbon\Carbon::parse($log->time_in_pm)->format('h:i A')  : '--' }}</td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3" style="color:var(--navy)">{{ $log?->time_out_pm ? \Carbon\Carbon::parse($log->time_out_pm)->format('h:i A') : '--' }}</td>
                                    <td class="px-3 sm:px-4 py-2 sm:py-3"><span class="px-2 py-0.5 rounded-full text-[8px] sm:text-[9px] font-bold {{ $aBadge }}">{{ $aStatus }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function updateClock() {
            document.getElementById('live-time').textContent =
                new Date().toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:true });
        }
        setInterval(updateClock, 1000);
        updateClock();

        function filterTable() {
            const filter = document.getElementById("searchInput").value.toLowerCase();
            const rows   = document.getElementById("logsTable").getElementsByTagName("tr");
            for (let r of rows) {
                const id   = r.cells[0]?.textContent ?? '';
                const name = r.cells[1]?.textContent ?? '';
                r.style.display = (id + name).toLowerCase().includes(filter) ? '' : 'none';
            }
        }

        let currentDate  = new Date();
        let selectedDate = new Date();
        const today      = new Date();

        function toggleCalendar() {
            document.getElementById('calendar-dropdown').classList.toggle('open');
        }

        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('date-picker-wrap');
            if (!wrap.contains(e.target))
                document.getElementById('calendar-dropdown').classList.remove('open');
        });

        function changeMonth(dir) {
            currentDate.setMonth(currentDate.getMonth() + dir);
            renderCalendar();
        }

        function goToToday() {
            currentDate = selectedDate = new Date();
            renderCalendar();
            fetchAttendanceForDate(formatDate(selectedDate));
            updateDateLabel(selectedDate);
            document.getElementById('calendar-dropdown').classList.remove('open');
        }

        function updateMetrics(m) {
            document.getElementById('metric-total').textContent      = m.total ?? 0;
            document.getElementById('metric-present').textContent    = m.present ?? 0;
            document.getElementById('metric-am-ontime').textContent  = m.am?.ontime ?? 0;
            document.getElementById('metric-am-late').textContent    = m.am?.late ?? 0;
            document.getElementById('metric-am-absent').textContent  = m.am?.absent ?? 0;
            document.getElementById('metric-pm-ontime').textContent  = m.pm?.ontime ?? 0;
            document.getElementById('metric-pm-late').textContent    = m.pm?.late ?? 0;
            document.getElementById('metric-pm-absent').textContent  = m.pm?.absent ?? 0;
        }

        function renderCalendar() {
            const y = currentDate.getFullYear();
            const m = currentDate.getMonth();
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            document.getElementById('cal-month-label').textContent = months[m] + ' ' + y;

            const firstDay    = new Date(y, m, 1).getDay();
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            const container   = document.getElementById('cal-days');
            container.innerHTML = '';

            for (let i = 0; i < firstDay; i++) {
                const b = document.createElement('div');
                b.className = 'cal-day empty';
                container.appendChild(b);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const el   = document.createElement('div');
                const date = new Date(y, m, d);
                el.className  = 'cal-day';
                el.textContent = d;
                if (isSameDay(date, today))        el.classList.add('today');
                if (isSameDay(date, selectedDate)) el.classList.add('selected');
                el.addEventListener('click', () => {
                    selectedDate = new Date(y, m, d);
                    updateDateLabel(selectedDate);
                    renderCalendar();
                    fetchAttendanceForDate(formatDate(selectedDate));
                    document.getElementById('calendar-dropdown').classList.remove('open');
                });
                container.appendChild(el);
            }
        }

        function isSameDay(a, b) {
            return a.getFullYear() === b.getFullYear() &&
                   a.getMonth()    === b.getMonth()    &&
                   a.getDate()     === b.getDate();
        }

        function formatDate(d) {
            return d.getFullYear() + '-' +
                   String(d.getMonth() + 1).padStart(2, '0') + '-' +
                   String(d.getDate()).padStart(2, '0');
        }

        function updateDateLabel(d) {
            document.getElementById('selected-date-label').textContent =
                d.toLocaleDateString('en-PH', { month:'short', day:'2-digit', year:'numeric' });
        }

        function fetchAttendanceForDate(dateStr) {
            fetch(`{{ url('/dashboard/attendance') }}?date=${dateStr}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(data => {
                rebuildTable(data.records);
                updateMetrics(data.metrics);
            })
            .catch(() => console.error('Failed to load attendance for', dateStr));
        }

        function rebuildTable(rows) {
            const tbody = document.getElementById('logsTable');
            tbody.innerHTML = '';

            if (!rows.length) {
                tbody.innerHTML = `<tr><td colspan="8" style="padding:20px 16px; text-align:center; color:#6B8EAE; font-size:12px;">No attendance records for this date.</td></tr>`;
                return;
            }

            rows.forEach(r => {
                const mStatus = !r.time_in     ? 'Absent' : (r.time_in_late     ? 'Late' : 'Present');
                const aStatus = !r.time_in_pm  ? 'Absent' : (r.time_in_pm_late  ? 'Late' : 'Present');
                const mBadge  = mStatus === 'Present' ? 'badge-present' : (mStatus === 'Late' ? 'badge-late' : 'badge-absent');
                const aBadge  = aStatus === 'Present' ? 'badge-present' : (aStatus === 'Late' ? 'badge-late' : 'badge-absent');
                tbody.innerHTML += `
                    <tr style="border-bottom:1px solid #EBF3FB; transition:background .15s">
                        <td style="padding:8px 12px; font-family:monospace; font-size:10px; color:#6B8EAE">${r.student_id_number}</td>
                        <td style="padding:8px 12px; font-weight:600; color:#0A1628; font-size:11px">${r.first_name} ${r.last_name}</td>
                        <td style="padding:8px 12px; color:#0A1628; font-size:10px">${r.time_in    ?? '--'}</td>
                        <td style="padding:8px 12px; color:#0A1628; font-size:10px">${r.time_out   ?? '--'}</td>
                        <td style="padding:8px 12px"><span class="${mBadge}" style="padding:2px 8px; border-radius:99px; font-size:8px; font-weight:700">${mStatus}</span></td>
                        <td style="padding:8px 12px; color:#0A1628; font-size:10px">${r.time_in_pm  ?? '--'}</td>
                        <td style="padding:8px 12px; color:#0A1628; font-size:10px">${r.time_out_pm ?? '--'}</td>
                        <td style="padding:8px 12px"><span class="${aBadge}" style="padding:2px 8px; border-radius:99px; font-size:8px; font-weight:700">${aStatus}</span></td>
                    </tr>`;
            });
        }

        function printTable() {
            const date  = document.getElementById('selected-date-label').textContent;
            const table = document.querySelector('table').outerHTML;

            const win = window.open('', '_blank', 'width=1100,height=750');
            win.document.write(`
                <!DOCTYPE html><html><head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>SPES 2026 Attendance — ${date}</title>
                <style>
                    body { font-family: sans-serif; padding: 28px; color: #0A1628; }
                    .header { display:flex; align-items:center; gap:14px; margin-bottom:6px; }
                    .header img { width:52px; height:52px; border-radius:50%; object-fit:cover; }
                    h2 { font-size:18px; margin:0 0 2px; color:#0A1628; }
                    .sub { font-size:12px; color:#6B8EAE; margin:0 0 4px; }
                    .meta { font-size:11px; color:#6B8EAE; margin-bottom:14px; }
                    .summary { display:flex; gap:16px; margin-top:20px; padding:12px 16px; background:#F0F6FF; border-radius:10px; border:1px solid #D0E4F5; flex-wrap:wrap; }
                    .summary span { font-size:12px; font-weight:700; }
                    .summary .dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px; vertical-align:middle; }
                    table { width:100%; border-collapse:collapse; font-size:11px; }
                    thead { background:#F0F6FF; }
                    th { padding:9px 12px; text-align:left; font-size:9px; text-transform:uppercase; color:#6B8EAE; border-bottom:1px solid #D0E4F5; letter-spacing:.06em; }
                    td { padding:9px 12px; border-bottom:1px solid #EBF3FB; color:#0A1628; }
                    .badge { padding:2px 8px; border-radius:99px; font-size:9px; font-weight:700; }
                    .badge-present { background:#DCFCE7; color:#166534; }
                    .badge-late    { background:#FEF9C3; color:#854D0E; }
                    .badge-absent  { background:#FEE2E2; color:#991B1B; }
                    .print-btn { margin-bottom:18px; padding:9px 20px; background:#0A1628; color:white; border:none; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600; }
                    @media print { .print-btn { display:none; } }
                </style>
                </head><body>
                <div class="header">
                    <div>
                        <h2>SPES 2026 — Attendance Register</h2>
                        <p class="sub">Special Program for Employment of Students</p>
                    </div>
                </div>
                <p class="meta">Date: <strong>${date}</strong> &nbsp;|&nbsp; Printed: ${new Date().toLocaleString('en-PH')}</p>

                <button class="print-btn" onclick="window.print()">🖨 Print this page</button>
                ${table}
                </body></html>
            `);
            win.document.close();
        }

        renderCalendar();
        fetchAttendanceForDate(formatDate(new Date()));

        setInterval(() => {
            if (isSameDay(selectedDate, today)) {
                fetchAttendanceForDate(formatDate(selectedDate));
            }
        }, 1);
    </script>
</x-layout>