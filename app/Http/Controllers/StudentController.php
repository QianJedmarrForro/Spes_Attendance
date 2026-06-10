<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id_number' => 'required|string',
        ]);

        $idNumber = trim($request->student_id_number);

        $student = Student::where('student_id_number', $idNumber)->first();

        if (!$student) {
            return back()->withErrors([
                'student_id_number' => 'Your Student ID Number is not registered in the system master list.'
            ]);
        }

        return redirect()->route('students.show', $student->id);
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }
}
