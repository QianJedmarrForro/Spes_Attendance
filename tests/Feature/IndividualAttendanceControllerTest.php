<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndividualAttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_logs_attendance_for_valid_qr_token(): void
    {
        $student = Student::create([
            'student_id_number' => 'SPES-001',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'email' => 'juan@example.com',
            'qr_token' => 'token-123',
        ]);

        $response = $this->postJson(route('terminal.verify'), [
            'qr_token' => 'token-123',
            'mode' => 'am_in',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('student_name', 'Juan Dela Cruz');

        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('attendance_date', now('Asia/Manila')->toDateString())
            ->first();

        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->time_in);
    }
}
