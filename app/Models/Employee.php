<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'employee_id', 'name', 'email', 'contact_no', 'role', 'password',
    ];

    protected $hidden = [
        'password',
    ];
}
