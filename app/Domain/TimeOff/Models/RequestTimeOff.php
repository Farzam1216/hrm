<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class RequestTimeOff extends Model
{
    protected $fillable = ['assign_timeoff_type_id', 'to', 'from', 'note', 'status', 'employee_id' ];

    public function timeOffStatus()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\RequestTimeOff', 'request_time_off_id');
    }
    public function assignTimeOff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\AssignTimeOffType', 'assign_timeoff_type_id');
    }
    public function requestTimeOffDetail()
    {
        return $this->hasMany('App\Models\RequestTimeOffDetail', 'request_time_off_id');
    }
    public function requestTimeOffComment()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\RequestTimeoffComment', 'request_time_off_id');
    }
    public function requestTimeOffNotification()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\RequestTimeoffNotification', 'id', 'request_time_off_id');
    }
}
