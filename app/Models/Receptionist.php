<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Receptionist extends Authenticatable
{
    protected $fillable = [
        'username', 'name', 'email', 'password'
    ];

    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
    ];
}
