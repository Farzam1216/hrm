<?php


namespace App\Domain\Benefit\Actions;

use Carbon\Carbon;

class UpdateEligibility
{
    /**
     * @param $employeeBenefitID
     * @param $effectiveDate
     * @param $statusBeforeEligibilityDate
     * @param $statusAfterEligibilityDate
     * @param $plan_ID
     */
    public function execute($employeeBenefitID, $effectiveDate, $statusBeforeEligibilityDate, $statusAfterEligibilityDate, $plan_ID)
    {
        $currentDate = Carbon::now()->startOfDay();
        $planName = (new GetPlanName())->execute($plan_ID);
        if ($effectiveDate == null) {
            (new CreateTransaction)->execute($currentDate, $statusBeforeEligibilityDate, $planName, $employeeBenefitID, 1);
            return;
        }
        $effectiveDate = $effectiveDate->startOfDay();
        if ($currentDate->gte($effectiveDate)) {
            (new CreateTransaction)->execute($effectiveDate, $statusAfterEligibilityDate, $planName, $employeeBenefitID, 1);
        } else {
            (new CreateTransaction)->execute($currentDate, $statusBeforeEligibilityDate, $planName, $employeeBenefitID, 1);
            (new CreateTransaction)->execute($effectiveDate, $statusAfterEligibilityDate, $planName, $employeeBenefitID, 0);
        }
    }
}
