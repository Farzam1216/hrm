<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class DeleteBenefitGroupPlan
{
    /**
     * @param $plan_id
     */
    public function execute($plan_id)
    {
        //deleting all benefitGroupPlan associated with $plan_id
        $benefitGroupPlan = BenefitGroupPlan::where('plan_id', $plan_id)->get();
        if (isset($benefitGroupPlan)) {
            foreach ($benefitGroupPlan as $groupPlan) {
                $groupPlan->delete();
            }
        }
    }
}
