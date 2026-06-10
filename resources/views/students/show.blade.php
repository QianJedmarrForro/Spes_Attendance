<x-layout>
    <x-slot:title>Your Permanent QR Code</x-slot:title>

    <div class="bg-white p-8 rounded-2xl shadow-md border border-pastelBlue-100 text-center max-w-sm w-full mx-auto">
        <!-- Success Badge -->
        <span class="bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded-full border border-green-100">
            ✓ Registration Complete
        </span>
        
        <!-- REMOVED "Student 8299" AND REPLACED WITH DYNAMIC FULL NAME -->
        <h2 class="text-2xl font-bold text-gray-800 mt-4">
            {{ $student->first_name }} {{ $student->last_name }}
        </h2>
        <p class="text-sm text-gray-500 mb-6 font-medium">ID: {{ $student->student_id_number }}</p>

        <!-- Permanent QR Code Display -->
        <div class="flex justify-center p-4 bg-pastelBlue-50/50 rounded-xl border border-dashed border-pastelBlue-200 my-4">
            {!! QrCode::size(220)->margin(1)->generate($student->qr_token) !!}
        </div>

        <p class="text-xs text-gray-400 mt-2 tracking-wide">
            Scan this terminal code to check-in / check-out
        </p>

        <!-- Action Options -->
        <div class="mt-6 pt-4 border-t border-pastelBlue-100 flex gap-2">
            <button onclick="window.print()" 
                    class="flex-1 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2.5 rounded-xl shadow-sm transition">
                Print Badge
            </button>
            <a href="{{ route('students.create') }}" 
               class="flex-1 bg-pastelBlue-100 text-pastelBlue-600 hover:bg-pastelBlue-200 text-sm font-medium py-2.5 rounded-xl text-center transition">
                Go Back
            </a>
        </div>
    </div>
</x-layout>
