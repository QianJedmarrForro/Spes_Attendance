<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class IndividualAttendanceController extends Controller
{
    private function getDashboardData()
{
    $today = Carbon::now('Asia/Manila')->toDateString();

    $students = Student::with(['attendances' => function ($q) use ($today) {
        $q->where('attendance_date', $today);
    }])->get();

    $metrics = $this->computeMetrics($students);

    return [
        'students'      => $students,
        'totalEnrolled' => $metrics['total'],
        'presentToday'  => $metrics['present'],
        'absentToday'   => $metrics['absent'],
        'amOntime'      => $metrics['am']['ontime'],
        'amLate'        => $metrics['am']['late'],
        'amAbsent'      => $metrics['am']['absent'],
        'pmOntime'      => $metrics['pm']['ontime'],
        'pmLate'        => $metrics['pm']['late'],
        'pmAbsent'      => $metrics['pm']['absent'],
        'completedLogs' => Attendance::where('attendance_date', $today)->whereNotNull('time_out')->count(),
        'liveEvents'    => Attendance::with('student')->where('attendance_date', $today)->latest('scanned_at')->take(5)->get(),
        'registryLogs'  => Attendance::with('student')->where('attendance_date', $today)->get()
    ];
}

    public function studentCard()
    {
        return view('dashboard', array_merge(['student' => null], $this->getDashboardData()));
    }

    public function verifyStudentId(Request $request)
    {
        $request->validate(['student_id_number' => 'required']);

        $student = Student::where('student_id_number', trim($request->student_id_number))->first();

        if (!$student) {
            return back()->with('error', 'Student not found.');
        }

        return view('dashboard', array_merge(['student' => $student], $this->getDashboardData()));
    }

    public function verifyScan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_token' => 'required|string|exists:students,qr_token',
            'mode'     => 'required|string|in:am_in,am_out,pm_in,pm_out'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $validator->errors()->all())
            ], 422);
        }

        try {
            $student    = Student::where('qr_token', trim($request->qr_token))->firstOrFail();
            $today      = Carbon::now('Asia/Manila')->toDateString();
            $now        = Carbon::now('Asia/Manila')->toTimeString();

            $attendance = Attendance::firstOrNew([
                'student_id'      => $student->id,
                'attendance_date' => $today
            ]);

            $statusMessage = '';

            switch ($request->mode) {
                case 'am_in':
                    $attendance->time_in = $attendance->time_in ?? $now;
                    $statusMessage = 'AM In';
                    break;
                case 'am_out':
                    $attendance->time_out = $attendance->time_out ?? $now;
                    $statusMessage = 'AM Out';
                    break;
                case 'pm_in':
                    $attendance->time_in_pm = $attendance->time_in_pm ?? $now;
                    $statusMessage = 'PM In';
                    break;
                case 'pm_out':
                    $attendance->time_out_pm = $attendance->time_out_pm ?? $now;
                    $statusMessage = 'PM Out';
                    break;
            }

            $attendance->scanned_at = Carbon::now('Asia/Manila');
            $attendance->save();

            return response()->json([
                'success'      => true,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'status'       => $statusMessage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAttendanceByDate(Request $request)
{
    $date = $request->query('date', Carbon::today('Asia/Manila')->toDateString());

    $students = Student::with(['attendances' => function ($q) use ($date) {
        $q->where('attendance_date', $date);
    }])->get();

    $records = $students->map(function ($s) {
        $log = $s->attendances->first();
        return [
            'student_id_number' => $s->student_id_number,
            'first_name'        => $s->first_name,
            'last_name'         => $s->last_name,
            'time_in'           => $log?->time_in     ? Carbon::parse($log->time_in)->format('h:i A')     : null,
            'time_out'          => $log?->time_out    ? Carbon::parse($log->time_out)->format('h:i A')    : null,
            'time_in_pm'        => $log?->time_in_pm  ? Carbon::parse($log->time_in_pm)->format('h:i A')  : null,
            'time_out_pm'       => $log?->time_out_pm ? Carbon::parse($log->time_out_pm)->format('h:i A') : null,
            'time_in_late'      => $log?->time_in     ? (Carbon::parse($log->time_in)->format('H:i') > '08:00')  : false,
            'time_in_pm_late'   => $log?->time_in_pm  ? (Carbon::parse($log->time_in_pm)->format('H:i') > '13:00') : false,
        ];
    })->values();

    return response()->json([
        'records' => $records,
        'metrics' => $this->computeMetrics($students),
    ]);
}
private function computeMetrics($students)
{
    $total = $students->count();

    $amOntime = $amLate = $amAbsent = 0;
    $pmOntime = $pmLate = $pmAbsent = 0;
    $present  = 0;

    foreach ($students as $s) {
        $log = $s->attendances->first();

        // AM
        if (!$log || !$log->time_in) {
            $amAbsent++;
        } elseif (Carbon::parse($log->time_in)->format('H:i') > '08:00') {
            $amLate++;
        } else {
            $amOntime++;
        }

        // PM
        if (!$log || !$log->time_in_pm) {
            $pmAbsent++;
        } elseif (Carbon::parse($log->time_in_pm)->format('H:i') > '13:00') {
            $pmLate++;
        } else {
            $pmOntime++;
        }

        // Overall present = any session attended
        if ($log && ($log->time_in || $log->time_in_pm)) {
            $present++;
        }
    }

    $absent = max($total - $present, 0);

    return [
        'total'   => $total,
        'present' => $present,
        'absent'  => $absent,
        'am'      => ['ontime' => $amOntime, 'late' => $amLate, 'absent' => $amAbsent],
        'pm'      => ['ontime' => $pmOntime, 'late' => $pmLate, 'absent' => $pmAbsent],
    ];
}
    
}