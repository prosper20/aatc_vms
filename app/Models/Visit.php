<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_id', 'employee_id', 'visit_date', 'reason', 'status', 'unique_code'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
