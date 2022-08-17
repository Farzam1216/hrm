<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;

class GetBenefitPlanWithTypeAndCoverages
{
    /**
     * @param $groupPlan
     * @return BenefitPlan|Builder|Model|object|null
     */
    public function execute($groupPlan)
    {
        return BenefitPlan::with('planType', 'planCoverages')->where('id', $groupPlan->plan_id)->first();
    }
}
