<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndividualAttendanceController;
use App\Http\Controllers\StudentController;

// --- Public Routes ---
Route::get('/', [StudentController::class, 'index'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

// --- Auth Gateway ---
Route::get('/login', function () {
    if ($user = \App\Models\User::first()) {
        auth()->login($user);
        return redirect()->route('dashboard.index');
    }
    return response('No users found. Please run a seeder.', 200);
})->name('login');

// --- Protected Workspace ---
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [IndividualAttendanceController::class, 'studentCard'])->name('dashboard.index');
    Route::post('/dashboard', [IndividualAttendanceController::class, 'verifyStudentId'])->name('dashboard.store');
});

// --- API & Scanner Routes ---
// The scanner needs to be accessible, usually without the 'auth' middleware 
// because you want your phone to just load the camera immediately.
Route::post('/api/terminal/verify', [IndividualAttendanceController::class, 'verifyScan'])->name('terminal.verify');

// Pointing directly to your teachers.terminal view
Route::get('/terminal/scanner', function() {
    return view('teachers.terminal');
})->name('terminal.scanner');
