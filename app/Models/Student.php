<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // This tells Laravel to use your 'students' table
    protected $table = 'students';

    // Allow these fields to be filled
    protected $fillable = ['student_id_number', 'qr_token', 'first_name', 'last_name'];
}
