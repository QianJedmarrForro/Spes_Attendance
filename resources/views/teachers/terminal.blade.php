<x-layout>
    <x-slot:title>Attendance Scanner Terminal</x-slot:title>

    <style>
        #interactive-reader { width: 100% !important; height: 100% !important; }
        #interactive-reader video { width: 100% !important; height: 100% !important; object-fit: cover !important; }
    </style>

    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-lg w-full border border-blue-100 text-center mx-auto mt-10 relative">

        <div id="toast-notification" class="absolute top-4 left-1/2 -translate-x-1/2 px-4 py-2 rounded-xl text-xs font-bold shadow-md hidden z-50 transition-all duration-300"></div>
    <center>
         <img src="{{ asset('images/SPES_LOGO.png') }}" alt="SPES" class="w-16 h-16 rounded-full object-cover mb-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Live Attendance Terminal</h2>
        <p class="text-sm text-gray-500 mb-5">Position the student's QR code inside the camera frame.</p>

        {{-- Mode values must match controller: am_in, am_out, pm_in, pm_out --}}
        <div class="grid grid-cols-4 gap-2 mb-4 bg-gray-100 p-1.5 rounded-xl">
            <label class="cursor-pointer">
                <input type="radio" name="scan_mode" value="am_in" checked class="sr-only peer">
                <div class="py-2 px-3 rounded-lg text-[10px] font-bold text-gray-500 text-center
                            peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition">
                    AM IN
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="scan_mode" value="am_out" class="sr-only peer">
                <div class="py-2 px-3 rounded-lg text-[10px] font-bold text-gray-500 text-center
                            peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition">
                    AM OUT
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="scan_mode" value="pm_in" class="sr-only peer">
                <div class="py-2 px-3 rounded-lg text-[10px] font-bold text-gray-500 text-center
                            peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition">
                    PM IN
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="scan_mode" value="pm_out" class="sr-only peer">
                <div class="py-2 px-3 rounded-lg text-[10px] font-bold text-gray-500 text-center
                            peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition">
                    PM OUT
                </div>
            </label>
        </div>

        {{-- Camera viewport --}}
        <div class="overflow-hidden rounded-xl bg-gray-900 border-2 border-dashed border-blue-300 relative h-[500px] w-full mb-4">
            <div id="interactive-reader"></div>
        </div>

        {{-- Status feedback --}}
        <div id="scanner-feedback" class="hidden p-3 rounded-xl text-sm font-semibold tracking-wide"></div>

        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('IndividualAttendance.index') }}"
               class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition">
                Return to Dashboard
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let scanningActive = true;
            const feedback = document.getElementById("scanner-feedback");
            const toast    = document.getElementById("toast-notification");

            function setFeedback(msg, type = 'info') {
                const styles = {
                    info:    'bg-blue-50 text-blue-600',
                    success: 'bg-green-50 text-green-700',
                    error:   'bg-red-50 text-red-600',
                    pulse:   'bg-blue-50 text-blue-600 animate-pulse',
                };
                feedback.className = `p-3 rounded-xl text-sm font-semibold tracking-wide ${styles[type] ?? styles.info}`;
                feedback.innerText = msg;
                feedback.classList.remove('hidden');
            }

            function hideFeedback() {
                feedback.classList.add('hidden');
            }

            function showToast(msg, type = 'success') {
                toast.innerText = msg;
                toast.classList.remove('hidden', 'bg-green-500', 'bg-red-500', 'text-white');
                toast.classList.add(type === 'success' ? 'bg-green-500' : 'bg-red-500', 'text-white');
                setTimeout(() => toast.classList.add('hidden'), 3500);
            }

           
            function refreshDashboardData() {
                if (window.opener && !window.opener.closed) {
                    try {
                        // Call the parent window's refresh function if it exists
                        if (typeof window.opener.refreshAttendance === 'function') {
                            window.opener.refreshAttendance();
                        }
                    } catch (e) {
                        console.log('Parent window refresh not available');
                    }
                }
            }

            const scanner = new Html5Qrcode("interactive-reader", { verbose: false });

            scanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    }
                },
                onScanSuccess,
                (_errorMsg) => { /* ignore tick errors */ }
            )
            .then(() => {
                setFeedback("Scanner ready — point at a QR code.", 'info');
            })
            .catch(err => {
                setFeedback("Camera failed to start: " + (err?.message ?? err), 'error');
            });

            function onScanSuccess(decodedText) {
                if (!scanningActive) return;
                scanningActive = false;

                
                const mode = document.querySelector('input[name="scan_mode"]:checked').value;
                setFeedback("Processing...", 'pulse');

                fetch("{{ route('terminal.verify') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept":       "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "ngrok-skip-browser-warning": "true"
                    },
                    body: JSON.stringify({ qr_token: decodedText, mode: mode })
                })
                .then(res => {
                    console.log('[Scanner] HTTP status:', res.status);
                    return res.json().then(data => ({ ok: res.ok, status: res.status, data }));
                })
                .then(({ ok, status, data }) => {
                    console.log('[Scanner] Response:', data);

                    if (ok && data.success) {
                        setFeedback(`✓ ${data.student_name} — ${data.status}`, 'success');
                        showToast(`Logged: ${data.student_name}`, 'success');
                        
                        // Refresh parent dashboard data
                        refreshDashboardData();
                    } else {
                        const msg = data?.message ?? `Server error (${status})`;
                        setFeedback(`⚠ ${msg}`, 'error');
                        showToast(msg, 'error');
                    }

                    setTimeout(() => {
                        scanningActive = true;
                        hideFeedback();
                    }, 2500);
                })
                .catch(err => {
                    console.error('[Scanner] Fetch error:', err);
                    setFeedback("Network error — check connection.", 'error');
                    showToast("Network error.", 'error');

                    setTimeout(() => {
                        scanningActive = true;
                        hideFeedback();
                    }, 2500);
                });
            }
        });
    </script>
</x-layout>