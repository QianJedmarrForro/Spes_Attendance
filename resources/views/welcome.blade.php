<x-layout>
    <x-slot:title>SPES 2026 - Claim QR Code</x-slot:title>

    <div class="bg-white/90 backdrop-blur-md p-10 rounded-2xl shadow-xl text-center max-w-md w-full border border-slate-100 relative">

        <h1 class="text-2xl font-bold text-slate-800 mb-1 tracking-tight">SCAN QR CODE</h1>
        <h2 class="text-2xl font-bold text-slate-800 mb-2 tracking-tight">OR SIGN IN AS ADMIN</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm text-left border border-red-100">
                @foreach ($errors->all() as $error)
                    <p class="flex items-start gap-2">
                        <span>⚠️</span>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <!-- Visual Divider Line -->
        <div class="relative flex py-4 items-center">
            <div class="flex-grow border-t border-slate-100"></div>
            <span class="flex-shrink mx-4 text-slate-400 text-xs font-semibold uppercase tracking-wider">Options</span>
            <div class="flex-grow border-t border-slate-100"></div>
        </div>

        <!-- System Controls Group Button Layout -->
        <div class="space-y-3">
            <!-- Scan Attendance QR Code Button -->
            <a href="{{ route('terminal.scanner') }}"
               class="inline-flex items-center justify-center gap-2 w-full bg-amber-50 hover:bg-amber-100 text-amber-600 text-sm font-semibold py-3 rounded-xl transition duration-200 border border-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4h4v4H4V4zm0 12h4v4H4v-4zm12-12h4v4h-4V4zm0 6h4v4h-4v-4zm0 6h4v4h-4v-4zM10 4h1v1h-1V4zm0 6h1v1h-1v-1zm6 6h1v1h-1v-1z" />
                </svg>
                Scan Attendance QR
            </a>

            <!-- Admin Login Button -->
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold py-3 rounded-xl transition duration-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Log in as Admin
            </a>
        </div>

       
      
    </div>
</x-layout>