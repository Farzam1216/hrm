<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class RequestTimeOffStatus extends Model
{
    protected $fillable = ['request_time_off_id', 'status', 'status_change_by'];


    public function timeOff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\RequestTimeOff', 'request_time_off_id');
    }
}
