<?php


namespace App\Domain\Benefit\Actions;

class EditBenefitGroupPlan
{
    public function execute($groupId)
    {
        $data['availableBenefitPlans'] = (new GetAvailablePlan())->execute($groupId);
        $data['benefitGroup'] = (new GetBenefitGroup())->execute($groupId);
        return $data;
    }
}
