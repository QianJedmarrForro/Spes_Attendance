<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    
    protected $fillable = [
        'student_id_number', 
        'first_name', 
        'last_name', 
        'email', 
        'qr_token'
    ];

    protected static function booted()
    {
        static::creating(function ($student) {
            $student->qr_token = (string) Str::uuid(); 
        });
    }
}
