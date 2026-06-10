<x-layout>
    <x-slot:title>SPES 2026 - Claim QR Code</x-slot:title>

    <div class="bg-white/90 backdrop-blur-md p-10 rounded-2xl shadow-xl text-center max-w-md w-full border border-blue-100 relative">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Get Your QR Code</h1>
        <p class="text-gray-600 mb-6">Enter your pre-assigned Student ID Number below to open your attendance badge.</p>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm text-left">
                @foreach ($errors->all() as $error)
                    <p>⚠️ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST" class="space-y-4 text-left mb-6">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Student ID Number</label>
                <input type="text" name="student_id_number" required placeholder="e.g., SA-DOR-2026-8299"
                       class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl shadow-md transition">
                Verify ID & Show QR
            </button>
        </form>

        <!-- Divider Decorator -->
        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-gray-100"></div>
            <span class="flex-shrink mx-4 text-gray-400 text-xs font-medium uppercase tracking-wider">System Control</span>
            <div class="flex-grow border-t border-gray-100"></div>
        </div>

        <!-- Admin Option Navigation Button Trigger -->
        <div class="mt-4">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center justify-center gap-1.5 w-full bg-pastelBlue-50 hover:bg-pastelBlue-100 text-pastelBlue-500 text-sm font-semibold py-2.5 rounded-xl transition duration-200 border border-pastelBlue-100/50">
                <svg xmlns="http://w3.org" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Log in as Admin
            </a>
        </div>

    </div>
</x-layout>
