<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User; // FIXED: Imported the User model namespace

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'attendance_date',
        'scanned_at',
        'time_in',
        'time_out',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance_date' => 'date',
        'scanned_at' => 'datetime',
    ];

    /**
     * Fetch the owning user/student record linked to this log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}