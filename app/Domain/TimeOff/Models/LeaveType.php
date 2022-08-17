<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name', 'amount', 'status',
    ];

    public function employees()
    {
        return $this->belongsToMany('App\Domain\Employee\Models\Employee')
            ->withTimestamps();
    }
}
