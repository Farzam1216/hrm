<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;

class GetEmployeeGroupWithAttachedPlan
{
    /**
     * @param $groupPlanID
     * @return mixed
     */
    public function execute($groupPlanID)
    {
        $details['groupPlan'] = BenefitGroupPlan::where('id', $groupPlanID)->with('groups')->first();
        $details['benefitPlanDetails'] = (new GetBenefitPlanWithTypeAndCoverages())->execute($details['groupPlan']);
        $details['planFields'] = (new GetFieldListByPlanType)->execute($details['benefitPlanDetails']);
        return $details;
    }
}
