<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'profile_completed'
    ];

    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
    ];
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Employee extends Model
// {
//     protected $fillable = ['email', 'password', 'name', 'profile_completed'];
//     public $timestamps = true;
// }
