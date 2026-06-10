<x-layout>
    <x-slot name="title">My Attendance Badge</x-slot>
    <x-slot name="content">
        @include('students.badge')
    </x-slot
    <body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">
    <div class="bg-white p-8 rounded-2xl shadow-md text-center max-w-sm border border-gray-200">
        <h1 class="text-xl font-bold text-gray-800 mb-1">{{ $student->name }}</h1>
        <p class="text-gray-500 text-xs uppercase tracking-wider mb-6">Student ID Badge</p>
        <div class="flex justify-center p-4 bg-gray-50 rounded-xl border border-gray-100">
            {!! $qrCode !!}
        </div>
        <p class="mt-4 text-xs text-gray-400">Present this QR code to the computer scanner to check-in.</p>
        <button onclick="window.print()" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition print:hidden">
            Print Badge
        </button>
    </div>
</body>
</html>
</x-layout>