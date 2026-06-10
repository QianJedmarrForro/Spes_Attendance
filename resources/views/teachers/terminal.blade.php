<x-layout>
    <x-slot:title>Attendance Scanner Terminal</x-slot:title>

    <div class="bg-white p-6 rounded-2xl shadow-xl max-w-lg w-full border border-pastelBlue-100 text-center mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Live Attendance Terminal</h2>
        <p class="text-sm text-gray-500 mb-6">Position the student's permanent QR code inside the camera frame.</p>

        <div class="overflow-hidden rounded-xl bg-gray-900 border-2 border-dashed border-pastelBlue-300 relative aspect-video w-full mb-4">
            <div id="interactive-reader" class="w-full h-full"></div>
        </div>

        <div id="scanner-feedback" class="p-3 bg-pastelBlue-50 text-pastelBlue-600 rounded-xl text-sm font-semibold tracking-wide hidden animate-pulse"></div>

        <div class="mt-6 pt-4 border-t border-pastelBlue-100">
            <a href="{{ route('students.create') }}" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition">
                Return to Home Portal
            </a>
        </div>
    </div>

    <script src="https://unpkg.com" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let scanningActive = true;
            const feedbackContainer = document.getElementById('scanner-feedback');
            const html5QrcodeScanner = new Html5Qrcode("interactive-reader");
            
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess
            ).catch(err => {
                feedbackContainer.classList.remove('hidden', 'bg-pastelBlue-50', 'text-pastelBlue-600');
                feedbackContainer.classList.add('bg-red-50', 'text-red-600');
                feedbackContainer.innerText = "Error: Camera permissions denied or missing.";
            });

            function onScanSuccess(decodedText, decodedResult) {
                if (!scanningActive) return;
                scanningActive = false;

                feedbackContainer.classList.remove('hidden', 'bg-red-50', 'text-red-600');
                feedbackContainer.classList.add('bg-pastelBlue-50', 'text-pastelBlue-600');
                feedbackContainer.innerText = "Processing scan asset token...";

                fetch("{{ route('terminal.verify') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ qr_token: decodedText })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        feedbackContainer.className = "p-3 bg-green-50 text-green-600 rounded-xl text-sm font-bold mt-2";
                        feedbackContainer.innerText = `✓ Logged: ${data.student_name} (${data.status.toUpperCase()})`;
                    } else {
                        feedbackContainer.className = "p-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold mt-2";
                        feedbackContainer.innerText = `⚠️ Error: ${data.message}`;
                    }
                    
                    setTimeout(() => {
                        scanningActive = true;
                        feedbackContainer.innerText = "";
                        feedbackContainer.className = "p-3 bg-pastelBlue-50 text-pastelBlue-600 rounded-xl text-sm font-semibold tracking-wide hidden animate-pulse";
                    }, 3000);
                })
                .catch(error => {
                    scanningActive = true;
                });
            }
        });
    </script>
</x-layout>
