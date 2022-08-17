<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlanCost;
use App\Domain\Benefit\Models\BenefitPlanCoverage;

class GetPlanCostForBenefitIfExists
{
    /**
     * @param $group_plan_id
     * @param $costOf
     * @return mixed
     */
    public function execute($group_plan_id, $costOf)
    {
        $groupPlanCost = BenefitGroupPlanCost::where('group_plan_id', $group_plan_id)->first();
        if (isset($groupPlanCost)) {
            $benefitCost[$costOf . 'PaysAmount'] = $groupPlanCost[$costOf . '_cost'];
            $benefitCost[$costOf . 'CurrencyCode'] = BenefitPlanCoverage::where('id', $groupPlanCost['coverage_id'])->pluck('cost_currency')->first();
            $benefitCost[$costOf . 'PaysType'] = 'currency';
        }
        return $benefitCost;
    }
}
