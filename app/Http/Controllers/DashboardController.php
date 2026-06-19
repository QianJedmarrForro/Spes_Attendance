<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = Carbon::today('Asia/Manila')->toDateString();

        $students = Student::with(['attendances' => fn($q) =>
            $q->whereDate('created_at', $date)
        ])->get();

        $metrics = $this->computeMetrics($students);

        return view('dashboard', [
            'students'      => $students,
            'totalEnrolled' => $metrics['total'],
            'ontimeToday'   => $metrics['ontime'],
            'lateToday'     => $metrics['late'],
            'presentToday'  => $metrics['present'],
            'absentToday'   => $metrics['absent'],
        ]);
    }

    private function computeMetrics($students)
    {
        $total  = $students->count();
        $ontime = 0;
        $late   = 0;

        foreach ($students as $s) {
            $log = $s->attendances->first();

            if (!$log || !$log->time_in) {
                continue; 
            }

            if (Carbon::parse($log->time_in)->format('H:i') > '08:00') {
                $late++;
            } else {
                $ontime++;
            }
        }

        $present = $ontime + $late;
        $absent  = max($total - $present, 0);

        return compact('total', 'ontime', 'late', 'present', 'absent');
    }
}