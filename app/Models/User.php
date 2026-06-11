<?php

namespace App\Models;

    use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Point this model to the table containing your QR tokens
    protected $table = 'students'; 

     protected $fillable = [
        'name', 'email', 'password', 'student_id_number', 'qr_token',
    ];
}