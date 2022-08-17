<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlanCost;

class UpdateGroupPlanCost
{
    /**
     * save group plan cost for each plan
     * @param $benefitGroupPlan
     * @param $level
     */
    public function execute($benefitGroupPlan, $request)
    {
        if (isset($request['plan'])) {
            foreach ($request['plan'] as $plan) {
                $benefitGroupPlanCost = BenefitGroupPlanCost::find($plan['group_plan_cost_id']);
                $benefitGroupPlanCost->employee_cost = $plan['employee_cost'];
                $benefitGroupPlanCost->company_cost = $plan['company_cost'];
                $benefitGroupPlanCost->save();
            }
        }
    }
}
