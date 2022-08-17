<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlanCost;

class SaveGroupPlanCost
{
    /**
     * save group plan cost for each plan
     * @param $benefitGroupPlan
     * @param $level
     */
    public function execute($benefitGroupPlan, $level)
    {
        if (isset($level['plan'])) {
            foreach ($level['plan'] as $plan) {
                $benefitGroupPlanCost = new BenefitGroupPlanCost();
                $benefitGroupPlanCost->group_plan_id = $benefitGroupPlan->id;
                $benefitGroupPlanCost->coverage_id = $plan['coverage_id'];
                $benefitGroupPlanCost->employee_cost = $plan['employee_cost'];
                $benefitGroupPlanCost->company_cost = $plan['company_cost'];
                $benefitGroupPlanCost->save();
            }
        }
    }
}
