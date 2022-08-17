<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyLevel extends Model
{
    protected $fillable = [
        'policy_id',
        'level_start_status',
        'amount_accrued',
        'max_accrual',
        'carry_over_amount',
    ];
    public function policy()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\Policy', 'policy_id');
    }
}
