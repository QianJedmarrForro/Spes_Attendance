<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IndividualAttendanceController extends Controller
{
    public function studentCard()
    {
        $student = auth()->user();

        if (!$student->attendance_token) {
            $student->update([
                'attendance_token' => Str::random(16)
            ]);
        }

        $qrCode = QrCode::size(250)
            ->color(31, 41, 55)
            ->generate($student->attendance_token);

        return view('student.badge', compact('student', 'qrCode'));
    }

    public function verifyScan(Request $request)
    {
        $request->validate([
            'attendance_token' => 'required|string|exists:users,attendance_token'
        ]);

        $student = User::where('attendance_token', $request->attendance_token)->firstOrFail();
        $today = Carbon::today()->toDateString();

        $alreadyCheckedIn = Attendance::where('user_id', $student->id)
            ->where('attendance_date', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return response()->json([
                'status' => 'warning',
                'message' => "{$student->name} has already checked in today."
            ], 200);
        }

        Attendance::create([
            'user_id' => $student->id,
            'attendance_date' => $today,
            'scanned_at' => Carbon::now(),
            'status' => 'present'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Welcome, {$student->name}! Attendance recorded."
        ]);
    }
}
