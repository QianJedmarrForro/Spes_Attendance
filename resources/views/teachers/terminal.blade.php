<x-layout>
    <x-slot:title>Attendance Scanner Terminal</x-slot:title>

    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-lg w-full border border-blue-100 text-center mx-auto mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Live Attendance Terminal</h2>
        <p class="text-sm text-gray-500 mb-6">
            Position the student's permanent QR code inside the camera frame.
        </p>

        <div class="flex items-center justify-center space-x-4 mb-6 bg-gray-50 p-2 rounded-xl border border-gray-100 max-w-xs mx-auto">
            <label class="flex items-center space-x-2 cursor-pointer font-semibold text-sm">
                <input type="radio" name="scan_mode" value="time-in" checked class="text-blue-600 focus:ring-blue-500">
                <span>📥 Time-In</span>
            </label>

            <span class="text-gray-300">|</span>

            <label class="flex items-center space-x-2 cursor-pointer font-semibold text-sm">
                <input type="radio" name="scan_mode" value="time-out" class="text-blue-600 focus:ring-blue-500">
                <span>📤 Time-Out</span>
            </label>
        </div>

        <div class="overflow-hidden rounded-xl bg-gray-900 border-2 border-dashed border-blue-300 relative aspect-video w-full mb-4">
            <div id="interactive-reader" class="w-full h-full"></div>
        </div>

        <div id="scanner-feedback"
             class="p-3 bg-blue-50 text-blue-600 rounded-xl text-sm font-semibold tracking-wide hidden animate-pulse">
        </div>

        <div class="mt-6 pt-4 border-t border-blue-100">
            <a href="{{ route('students.create') }}"
               class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition">
                Return to Home Portal
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let scanningActive = true;

            const feedbackContainer = document.getElementById("scanner-feedback");
            const html5QrcodeScanner = new Html5Qrcode("interactive-reader");

            // Responsive QR scanner box
            const config = {
                fps: 15,
                qrbox: (viewfinderWidth, viewfinderHeight) => {
                    const isMobile = window.innerWidth < 768;

                    return isMobile
                        ? {
                            width: Math.floor(viewfinderWidth * 1.5),
                            height: Math.floor(viewfinderWidth * 1.5)
                        }
                        : {
                            width: Math.floor(viewfinderWidth * 1.0),
                            height: Math.floor(viewfinderWidth * 1.0)
                        };
                }
            };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess
            ).catch(err => {
                feedbackContainer.classList.remove("hidden");
                feedbackContainer.classList.add("bg-red-50", "text-red-600");
                feedbackContainer.innerText =
                    "Error: Camera permissions denied or unavailable.";
            });

            function onScanSuccess(decodedText) {

                if (!scanningActive) return;

                scanningActive = false;

                feedbackContainer.classList.remove("hidden");
                feedbackContainer.className =
                    "p-3 bg-blue-50 text-blue-600 rounded-xl text-sm font-semibold tracking-wide animate-pulse";

                feedbackContainer.innerText = "Processing scan...";

                const activeMode =
                    document.querySelector('input[name="scan_mode"]:checked').value;

                fetch("{{ route('terminal.verify') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        qr_token: decodedText,
                        mode: activeMode
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {
                        feedbackContainer.className =
                            "p-3 bg-green-50 text-green-600 rounded-xl text-sm font-bold mt-2";

                        feedbackContainer.innerText =
                            `✓ Logged: ${data.student_name} (${data.status.toUpperCase()})`;

                    } else {
                        feedbackContainer.className =
                            "p-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold mt-2";

                        feedbackContainer.innerText =
                            `⚠️ Error: ${data.message}`;
                    }

                    setTimeout(() => {
                        scanningActive = true;
                        feedbackContainer.classList.add("hidden");
                    }, 3000);
                })
                .catch(() => {
                    scanningActive = true;

                    feedbackContainer.className =
                        "p-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold mt-2";

                    feedbackContainer.innerText =
                        "⚠️ HARRY PISOT. Please try again.";

                    setTimeout(() => {
                        feedbackContainer.classList.add("hidden");
                    }, 3000);
                });
            }
        });
    </script>
</x-layout>