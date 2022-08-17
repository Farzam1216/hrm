<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id', 'leave_type', 'datefrom', 'dateto','hourslogged','reason','status', 'cc_to', 'point_of_contact', 'description', 'line_manager', 'subject'
    ];

    public function leaveType()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\LeaveType', 'leave_type', 'id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id', 'id');
    }

    public function pointOfContact()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'point_of_contact', 'id');
    }

    public function lineManager()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'line_manager', 'id');
    }
}
