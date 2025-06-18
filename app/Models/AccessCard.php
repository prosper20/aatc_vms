<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCard extends Model
{
    protected $fillable = [
        'serial_number', 'access_level', 'issued_to', 'issued_at', 'is_issued'
    ];

    public function currentVisit()
    {
        return $this->hasOne(Visit::class);
    }
}
