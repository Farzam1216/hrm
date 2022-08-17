<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlanCost;
use App\Domain\Benefit\Models\BenefitPlanCoverage;

class DeleteBenefitPlanCoverages
{
    /**
     * @param $plan_id
     */
    public function execute($plan_id)
    {
        $benefitPlanCoverages = BenefitPlanCoverage::where('plan_id', $plan_id)->get();
        if (isset($benefitPlanCoverages)) {
            foreach ($benefitPlanCoverages as $coverage) {
                $benefitGroupPlanCost = BenefitGroupPlanCost::where('coverage_id', $coverage->id)->get();
                if (isset($benefitGroupPlanCost)) {
                    foreach ($benefitGroupPlanCost as $groupPlanCost) {
                        //deleting all benefitGroupPlanCost associated with benefitPlanCoverages coverage_id
                        $groupPlanCost->delete();
                    }
                }
                $coverage->delete(); //deleting all benefitPlanCoverages
            }
        }
    }
}
