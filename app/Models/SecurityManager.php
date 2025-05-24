<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SecurityManager extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'profile_completed'
    ];

    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
    ];
}
