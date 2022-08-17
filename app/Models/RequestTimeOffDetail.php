<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestTimeOffDetail extends Model
{
    protected $fillable = ['request_time_off_id','date','hours' ];
    
    public function requestTimeOff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\RequestTimeOff', 'request_time_off_id');
    }
}
