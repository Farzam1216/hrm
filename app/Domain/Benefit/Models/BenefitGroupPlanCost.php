<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitGroupPlanCost extends Model
{
    //
    protected $fillable = [
        'group_plan_id',
        'coverage_id',
        'employee_cost',
        'company_cost',
    ];
    public function planCostCoverage()
    {
        return $this->belongsTo(BenefitPlanCoverage::class, 'coverage_id');
    }
}
