<?php

namespace Database\Seeders;

use App\Models\Student;
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
                'first_name' => 'Emmanuel',
                'last_name' => 'Remis',
                'student_id_number' => 'SA-DOR-2026-8301',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
            [
                'first_name' => 'Bruno',
                'last_name' => 'Mars',
                'student_id_number' => 'SA-DOR-2026-8302',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Taylor',
                'last_name' => 'Swift',
                'student_id_number' => 'SA-DOR-2026-8303',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Ariana',
                'last_name' => 'Grande',
                'student_id_number' => 'SA-DOR-2026-8304',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Ed',
                'last_name' => 'Sheeran',
                'student_id_number' => 'SA-DOR-2026-8305',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Billie',
                'last_name' => 'Eilish',
                'student_id_number' => 'SA-DOR-2026-8306',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Justin',
                'last_name' => 'Bieber',
                'student_id_number' => 'SA-DOR-2026-8307',
                'qr_token' => Str::random(32), // Using the correct column name
            ],
             [
                'first_name' => 'Dua',
                'last_name' => 'Lipa',
                'student_id_number' => 'SA-DOR-2026-8308',
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