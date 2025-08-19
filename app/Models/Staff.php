<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_completed',
        'okta_id'
    ];

    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Override the password mutator for Okta users
    public function setPasswordAttribute($value)
    {
        // Only hash password if it's not null (for non-Okta users)
        $this->attributes['password'] = $value ? bcrypt($value) : null;
    }

    // Check if this is an Okta user
    public function isOktaUser()
    {
        return !is_null($this->okta_id);
    }
}
