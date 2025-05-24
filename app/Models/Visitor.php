<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'organization'
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
