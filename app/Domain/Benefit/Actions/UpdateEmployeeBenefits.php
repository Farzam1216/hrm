<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;

class UpdateEmployeeBenefits
{
    /**
     * @param $employeeID
     * @param $groupPlanID
     * @return mixed
     */
    public function execute($employeeID, $groupPlanID)
    {
        $employeeBenefitID = EmployeeBenefit::create(
            [
                'benefit_group_plan_id' => $groupPlanID,
                'employee_id' => $employeeID,
            ]
        );
        return $employeeBenefitID;
    }
}
