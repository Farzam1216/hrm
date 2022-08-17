<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitGroupEmployee extends Model
{
    protected $fillable = [
        'benefit_group_id',
        'employee_id',
        'start_date',
        'end_date',
    ];
    public function benefitGroup()
    {
        return $this->belongsTo(BenefitGroup::class, 'benefit_group_id');
    }
    public function employees()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id');
    }
}
