<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class GetEmployeeBenefitPlan
{
    public function execute($benefitGroupPlan_ID)
    {
        $benefitGroupPlan = BenefitGroupPlan::where('id', $benefitGroupPlan_ID)->with('plans')->first();
        return $benefitGroupPlan['plans'];
    }
}
