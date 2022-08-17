<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeOffTransaction extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'assign_time_off_id',
        'action',
        'accrual_date',
        'balance',
        'hours_accrued',
        'employee_id'
    ];

    /**
     * @return BelongsTo
     */
    public function assignTimeOff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\AssignTimeOffType', 'assign_time_off_id');
    }
}
