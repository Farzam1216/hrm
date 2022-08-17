<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Model;

class AssignTimeOffType extends Model
{
    /**
     * @var array
     */
    protected $fillable=['employee_id','type_id','accrual_option','attached_policy_id' ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeOffType()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\TimeOffType', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function policy()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\Policy', 'attached_policy_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestTimeOff()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\RequestTimeOff', 'assign_timeoff_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Transaction()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\RequestTimeOff', 'assign_timeoff_type_id');
    }
    public function timeofftransaction()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\TimeOffTransaction', 'assign_time_off_id');
    }
}
