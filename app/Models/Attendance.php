<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id', 
        'attendance_date',
        'scanned_at',
        'time_in',
        'time_out',
        'time_in_pm',   
        'time_out_pm',  
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'scanned_at'      => 'datetime',
    ];

    /**
     * Get the student that owns the attendance record.
     * @return BelongsTo
     */
    public function student(): BelongsTo 
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}