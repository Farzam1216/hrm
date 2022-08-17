<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupEmployee;
use App\Domain\Benefit\Models\BenefitGroupPlan;
use App\Domain\Benefit\Models\BenefitGroupPlanCost;

class DeleteBenefitGroupEntries
{
    /**
     * @param $id
     */

    public function execute($id)
    {
        //delete employee associated with groupID
        $benefitGroupEmployee = BenefitGroupEmployee::where('benefit_group_id', $id)->get();
        if (isset($benefitGroupEmployee)) {
            foreach ($benefitGroupEmployee as $groupEmployee) {
                $groupEmployee->delete();
            }
        }
        //delete group plan associated with groupID
        $benefitGroupPlan = BenefitGroupPlan::where('group_id', $id)->get();
        if (isset($benefitGroupPlan)) {
            foreach ($benefitGroupPlan as $groupPlan) {
                //delete group plan costs associated with group plan ID
                $benefitGroupPlanCost = BenefitGroupPlanCost::where('group_plan_id', $groupPlan->id)->get();
                if (isset($benefitGroupPlanCost)) {
                    foreach ($benefitGroupPlanCost as $groupPlanCost) {
                        $groupPlanCost->delete();
                    }
                }
                $groupPlan->delete();
            }
        }
    }
}
