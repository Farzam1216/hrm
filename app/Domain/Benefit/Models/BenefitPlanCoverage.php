<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitPlanCoverage extends Model
{
    protected $fillable = ['coverage_name', 'plan_id', 'total_cost', 'cost_currency'];

    public function benefitPlan()
    {
        return $this->belongsTo(BenefitPlan::class, 'plan_id');
    }
    public function costCoverage()
    {
        return $this->hasMany(BenefitGroupPlanCost::class, 'coverage_id');
    }
}
