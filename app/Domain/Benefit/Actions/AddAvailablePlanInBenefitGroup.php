<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class AddAvailablePlanInBenefitGroup
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        $benefitGroupPlan = new BenefitGroupPlan();
        $benefitGroupPlan->group_id = $request->group_id;
        $benefitGroupPlan->plan_id = $request->plan_id;
        $benefitGroupPlan->eligibility = $request->eligibilityType;
        $benefitGroupPlan->wait_period = (new GroupWaitingPeriod())->execute($request->all());
        $benefitGroupPlan->type_of_period = (new GetDeductionExceptionType())->execute($request->all());
        $benefitGroupPlan->save();
        //save group plan coverage cost
        (new SaveGroupPlanCost())->execute($benefitGroupPlan, $request->all());
    }
}
