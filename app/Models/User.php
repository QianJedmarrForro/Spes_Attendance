<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{   
    protected $table = 'students'; 

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'student_id_number', 
        'qr_token',
    ];

    
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id', 'id');
    }
}