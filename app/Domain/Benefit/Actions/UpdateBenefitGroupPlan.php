<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class UpdateBenefitGroupPlan
{
    /**
     * Update Plan attached with Benefitroup
     * @param $request
     */
    public function execute($request)
    {
        $benefitGroupPlan = BenefitGroupPlan::find($request->group_plan_id);
        $benefitGroupPlan->eligibility = $request->eligibilityType;
        $benefitGroupPlan->wait_period = (new GroupWaitingPeriod())->execute($request->all());
        $benefitGroupPlan->type_of_period = (new GetDeductionExceptionType())->execute($request->all());
        $benefitGroupPlan->save();
        (new UpdateGroupPlanCost())->execute($benefitGroupPlan, $request->all());
    }
}
