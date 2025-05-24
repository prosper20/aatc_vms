<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id', 'staff_id', 'visit_date', 'reason', 'status', 'unique_code', 'floor_of_visit'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
