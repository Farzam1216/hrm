<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class DeleteGroupPlan
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        //Delete a plan from group
        $benefitGroupPlan = BenefitGroupPlan::where('id', $data['groupPlanId']);
        $benefitGroupPlan->delete();
    }
}
