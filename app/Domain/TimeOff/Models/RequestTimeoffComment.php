<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class RequestTimeoffComment extends Model
{
    protected $fillable = ['request_time_off_id', 'comment', 'commented_by'];

    public function timeOff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\RequestTimeOff', 'request_time_off_id');
    }
    public function requestTimeOff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\RequestTimeOff', 'request_time_off_id');
    }
}
