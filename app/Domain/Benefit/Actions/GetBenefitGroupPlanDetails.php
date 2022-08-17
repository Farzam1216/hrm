<?php

namespace App\Domain\Benefit\Actions;

class GetBenefitGroupPlanDetails
{
    /**
     * @param $request
     * @return mixed
     */

    public function execute($request)
    {
        if (isset($request)) {
            //$request[1] Group Plan ID
            $employeeBenefits = (new GetEmployeeGroupWithAttachedPlan())->execute($request[1]);
            return $employeeBenefits;
        }
    }
}
