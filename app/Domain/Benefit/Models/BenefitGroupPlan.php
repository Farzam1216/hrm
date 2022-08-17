<?php

namespace App\Domain\Benefit\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitGroupPlan extends Model
{
    protected $fillable = [
        'group_id',
        'plan_id',
        'eligibility',
        'wait_period',
        'type_of_period',
    ];
    public function employeeBenefitDetails()
    {
        return $this->hasMany(EmployeeBenefit::class, 'benefit_group_plan_id');
    }
    public function plans()
    {
        return $this->belongsTo(BenefitPlan::class, 'plan_id');
    }
    public function groups()
    {
        return $this->belongsTo(BenefitGroup::class, 'group_id');
    }
    public function employee()
    {
        return $this->belongsToMany('App\Domain\Employee\Models\Employee', 'employee_benefits', 'benefit_group_plan_id', 'employee_id')->withPivot('employee_benefit_plan_coverage', 'deduction_frequency', 'employee_payment', 'company_payment');
    }
    public function groupPlanCost()
    {
        return $this->hasMany(BenefitGroupPlanCost::class, 'group_plan_id');
    }
    public function groupPlans()
    {
        return $this->belongsTo(BenefitPlan::class, 'plan_id');
    }
}
