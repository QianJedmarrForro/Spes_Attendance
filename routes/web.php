<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IndividualAttendanceController;

Route::get('/', [StudentController::class, 'index'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

Route::get('/login', function () {
    if ($user = \App\Models\User::first()) {
        auth()->login($user);
        return redirect()->route('IndividualAttendance.index');
    }
    return response('No users found. Please run a seeder.', 200);
})->name('login');

 Route::middleware(['auth'])->group(function () {
  
    Route::post('/dashboard', [IndividualAttendanceController::class, 'verifyStudentId'])->name('dashboard.store');
    
    Route::post('/terminal/verify', [IndividualAttendanceController::class, 'verifyScan'])->name('terminal.verify');
    Route::get('/terminal/scanner', function() {
        return view('teachers.terminal');
    })->name('terminal.scanner');
});
Route::get('/camera-test', function () {
    return view('camera-test');
});
Route::get('/csrf-test', function () {
    return response()->json(['token' => csrf_token()]);
});
    
Route::get('/scan-test', function () {
    $token = csrf_token();
    return response()->json(['csrf' => $token]);
});

Route::get('/dashboard/attendance', [IndividualAttendanceController::class, 'getAttendanceByDate']);

Route::get('/dashboard', [IndividualAttendanceController::class, 'studentCard'])->name('IndividualAttendance.index');

Route::middleware(['auth'])->group(function () {
   
});