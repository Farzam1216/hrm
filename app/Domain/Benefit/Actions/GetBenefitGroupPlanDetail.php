<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupPlan;
use Illuminate\Database\Eloquent\Collection;

class GetBenefitGroupPlanDetail
{
    /**
     * @param $request
     * @return Collection
     */
    public function execute($request)
    {
        if (isset($request->planId, $request->groupId)) {
            return BenefitGroupPlan::with('groupPlanCost.planCostCoverage', 'groupPlans')->where('plan_id', $request->planId)->where('group_id', $request->groupId)->get();
        }
    }
}
