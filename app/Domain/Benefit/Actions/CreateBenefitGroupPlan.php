<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class CreateBenefitGroupPlan
{
    /**
     * Add plans in benefitgroup
     * @param $benefitGroup
     * @param $data
     */
    public function execute($benefitGroup, $data)
    {
        if (isset($data->levels)) {
            foreach ($data->levels as $level) {
                $benefitGroupPlan = new BenefitGroupPlan();
                $benefitGroupPlan->group_id = $benefitGroup->id;
                $benefitGroupPlan->plan_id = $level['plan_id'];
                $benefitGroupPlan->eligibility = $level['eligibilityType'];
                $benefitGroupPlan->wait_period = (new GroupWaitingPeriod())->execute($level);
                $benefitGroupPlan->type_of_period = (new GroupWaitingPeriodType())->execute($level);
                $benefitGroupPlan->save();
                //save group plan coverage cost
                (new SaveGroupPlanCost())->execute($benefitGroupPlan, $level);
                //save employee benefit
                (new AddEmployeeInBenefitGroup())->execute($data, $benefitGroupPlan);
            }
        }
    }
}
