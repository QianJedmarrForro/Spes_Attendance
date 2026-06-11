<?php

namespace Database\Seeders;

use App\Models\Student; // Ensure you use the Student model
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'first_name' => 'Shawn Harry',
                'last_name' => 'Yuson',
                'student_id_number' => 'SA-DOR-2026-8299',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
            [
                'first_name' => 'Qian Jedmarr',
                'last_name' => 'Forro',
                'student_id_number' => 'SA-DOR-2026-8300',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Papi cholo',
                'last_name' => 'Remis',
                'student_id_number' => 'SA-DOR-2026-8301',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['student_id_number' => $student['student_id_number']],
                $student
            );
        }
    }
}