<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCard extends Model
{
    protected $fillable = [
        'serial_number',
        'card_type',
        'access_level',
        'issued_to',
        'issued_at',
        'issued_by',
        'is_active',
        'is_issued',
        'valid_until'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'is_issued' => 'boolean'
    ];

    public function currentVisit()
    {
        return $this->hasOne(Visit::class);
    }

    public function scopeAccessCards($query)
    {
        return $query->where('card_type', 'access_card');
    }

    public function scopeVisitorPasses($query)
    {
        return $query->where('card_type', 'visitor_pass');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_issued', false)
                     ->where('is_active', true)
                     ->where(function($q) {
                         $q->whereNull('valid_until')
                           ->orWhere('valid_until', '>', now());
                     });
    }
}
