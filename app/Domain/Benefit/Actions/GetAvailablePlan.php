<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;

class GetAvailablePlan
{
    /**
     * get available plans to add in Benefit Group
     * @param $groupId
     * @return Collection
     */
    public function execute($groupId)
    {
        $benefitPlanInGroup = BenefitPlan::whereHas('benefitGroup', function ($query) use ($groupId) {
            $query->where('group_id', $groupId);
        })->get();
        $benefitPlanInGroupId = $benefitPlanInGroup->pluck('id');
        $availableBenefitPlans = BenefitPlan::with('benefitGroup')->whereNotIn('id', $benefitPlanInGroupId)->get();
        return $availableBenefitPlans;
    }
}
