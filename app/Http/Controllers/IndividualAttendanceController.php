<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Str;

class IndividualAttendanceController extends Controller
{
    public function studentCard(Request $request)
    {
        $today = Carbon::today()->toDateString();
        
        // Dynamic payload parsing: fallback checks on active query values
        $student = session('searched_student') ?? ($request->filled('student_id_number') 
        ? User::where('student_id_number', trim($request->student_id_number))->first() 
        : null);

        // Verification handshake check
        if ($student && !$student->qr_token) {
            $student->update(['qr_token' => Str::random(16)]);
        }

        return view('dashboard', [
            'student'        => $student,
            'totalEnrolled'  => User::count(),
            'presentToday'   => Attendance::where('attendance_date', $today)->count(),
            'completedLogs'  => Attendance::where('attendance_date', $today)->whereNotNull('time_out')->count(),
            'liveEvents'     => Attendance::with('user')->where('attendance_date', $today)->latest('scanned_at')->take(5)->get(),
            'registryLogs'   => Attendance::with('user')->where('attendance_date', $today)->get()
        ]);
    }

    public function verifyStudentId(Request $request)
{
    $request->validate(['student_id_number' => 'required|string']);

    $student = \App\Models\Student::where('student_id_number', $request->student_id_number)->first();

    if (!$student) {
        return back()->withErrors(['student_id_number' => 'ID not registered.']);
    }

    return view('dashboard', [
        'student' => $student,
    ]);
}
 
    public function verifyScan(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string|exists:users,qr_token',
            'mode'     => 'required|string|in:time-in,time-out'
        ]);

        $student = User::where('qr_token', $request->qr_token)->firstOrFail();
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('user_id', $student->id)->where('attendance_date', $today)->first();

        // Transaction Process Router
        if ($request->mode === 'time-in') {
            if ($attendance) {
                return response()->json(['success' => false, 'message' => "{$student->name} already timed in today."]);
            }

            Attendance::create([
                'user_id'         => $student->id,
                'attendance_date' => $today,
                'scanned_at'      => Carbon::now(),
                'time_in'         => Carbon::now()->toTimeString(),
                'status'          => 'present'
            ]);
        } else {
            if (!$attendance) {
                return response()->json(['success' => false, 'message' => "No active Time-In record found for today."]);
            }
            if ($attendance->time_out) {
                return response()->json(['success' => false, 'message' => "Already timed out today."]);
            }

            $attendance->update(['time_out' => Carbon::now()->toTimeString()]);
        }

        return response()->json([
            'success'      => true,
            'student_name' => $student->name,
            'status'       => $request->mode
        ]);
    }
}