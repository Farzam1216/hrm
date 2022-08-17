<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;

class DeleteBenefitPlan
{
    /**
     * @param $id
     */
    public function execute($id)
    {
        (new DeleteBenefitPlanCoverages())->execute($id);
        (new DeleteBenefitGroupPlan())->execute($id);
        $benefitPlan = BenefitPlan::where('id', $id);
        $benefitPlan->delete();
        (new ManageBenefitPlanPermissions())->execute("benefitplan", $id, "delete");
    }
}
