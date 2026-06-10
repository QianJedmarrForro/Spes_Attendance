
@extends('layouts.layout')
@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-pastelBlue-500">Attendance Dashboard</h1>
            <p class="text-gray-500 text-sm">Live list of scanned QR attendance records</p>
        </div>

        <div class="text-sm text-gray-600 bg-pastelBlue-100 px-3 py-2 rounded-lg">
            Today: {{ date('F d, Y') }}
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="bg-pastelBlue-50 border border-pastelBlue-100 p-4 rounded-xl">
            <p class="text-gray-500 text-sm">Total Present</p>
            <h2 class="text-2xl font-bold text-pastelBlue-500">120</h2>
        </div>

        <div class="bg-pastelBlue-50 border border-pastelBlue-100 p-4 rounded-xl">
            <p class="text-gray-500 text-sm">Morning On Time</p>
            <h2 class="text-2xl font-bold text-green-500">85</h2>
        </div>

        <div class="bg-pastelBlue-50 border border-pastelBlue-100 p-4 rounded-xl">
            <p class="text-gray-500 text-sm">Late Arrivals</p>
            <h2 class="text-2xl font-bold text-red-400">18</h2>
        </div>

        <div class="bg-pastelBlue-50 border border-pastelBlue-100 p-4 rounded-xl">
            <p class="text-gray-500 text-sm">Afternoon Completed</p>
            <h2 class="text-2xl font-bold text-blue-500">76</h2>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl border border-pastelBlue-100 shadow-md overflow-x-auto">

        <table class="min-w-full text-sm">

            <!-- HEADER -->
            <thead class="bg-pastelBlue-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Student Name</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Time Scanned</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>

            <!-- BODY (DYNAMIC LOOP) -->
            <tbody class="divide-y divide-pastelBlue-100">

                @foreach($attendances as $attendance)

                @php
                    $time = \Carbon\Carbon::parse($attendance->time);

                    // Determine status rules
                    $status = 'Late';
                    $color = 'text-red-500';

                    if ($attendance->type == 'Morning IN' && $time->between('07:30', '07:59')) {
                        $status = 'On Time';
                        $color = 'text-green-500';
                    }

                    if ($attendance->type == 'Morning OUT' && $time->between('12:01', '12:19')) {
                        $status = 'On Time';
                        $color = 'text-green-500';
                    }

                    if ($attendance->type == 'Afternoon IN' && $time->between('12:41', '12:59')) {
                        $status = 'On Time';
                        $color = 'text-green-500';
                    }

                    if ($attendance->type == 'Afternoon OUT' && $time->between('17:01', '17:19')) {
                        $status = 'On Time';
                        $color = 'text-green-500';
                    }
                @endphp

                <tr class="hover:bg-pastelBlue-50 transition">

                    <td class="px-4 py-3 font-medium text-gray-700">
                        {{ $attendance->student_name }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $attendance->type }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ \Carbon\Carbon::parse($attendance->date)->format('F d, Y') }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ \Carbon\Carbon::parse($attendance->time)->format('h:i A') }}
                    </td>

                    <td class="px-4 py-3 font-semibold {{ $color }}">
                        {{ $status }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>
    </div>

</div>

@endsection