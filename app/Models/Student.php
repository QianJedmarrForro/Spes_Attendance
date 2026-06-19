<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'student_id_number',
        'first_name',
        'last_name',
        'email',
        'qr_token',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}