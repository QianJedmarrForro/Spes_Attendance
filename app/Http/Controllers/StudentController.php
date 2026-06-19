<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
{
    $request->validate([
        'student_id_number' => 'required|string',
    ]);

    $idNumber = trim($request->student_id_number);
    
    $student = \App\Models\Student::where('student_id_number', $idNumber)->first();

    if (!$student) {
        return back()->withErrors([
            'student_id_number' => 'Your Student ID Number is not registered in the master list.'
        ])->withInput();
    }

    return view('dashboard', [
        'student' => $student,
        'liveEvents' => \App\Models\Attendance::latest()->take(10)->get(), 
        'totalEnrolled' => \App\Models\Student::count(), 
        'presentToday' => \App\Models\Attendance::whereDate('scanned_at', now())->count(),
    ]);
}
}