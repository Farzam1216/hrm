<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitPlanType extends Model
{
    protected $fillable = ['name', 'type_details','icon'];

    public function planTypeDetail()
    {
        return $this->belongsTo(BenefitPlanTypeDetail::class, 'plan_type_details_id');
    }

    public function plans()
    {
        return $this->hasMany(BenefitPlan::class, 'plan_type_id');
    }
}
