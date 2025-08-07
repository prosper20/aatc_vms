<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id', 'staff_id', 'visit_date', 'reason', 'status', 'unique_code',
        'floor_of_visit', 'checked_in_at', 'checked_out_at',
        'checkin_by', 'checkout_by', 'is_checked_in', 'is_checked_out',
        'arrived_at_gate', 'verification_passed', 'verification_message', 'verified_by',
        'mode_of_arrival', 'plate_number', 'vehicle_type', 'inquiry', 'notes',
        'access_card_id', 'card_issued_at', 'card_retrieved_at',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function accessCard()
    {
        return $this->belongsTo(AccessCard::class);
    }
}

