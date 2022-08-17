<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;

class GetPlanName
{
    /**
     * @param $plan_ID
     * @return mixed
     */
    public function execute($plan_ID)
    {
        return BenefitPlan::where('id', $plan_ID)->pluck('name')->first();
    }
}
