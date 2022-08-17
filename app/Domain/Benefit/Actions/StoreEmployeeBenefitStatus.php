<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefitStatus;

class StoreEmployeeBenefitStatus
{
    /**
     * @param $employeeBenefit_ID
     * @param $request
     * @param $trackingField
     */

    public function execute($employeeBenefit_ID, $request, $trackingField)
    {
        $employeeBenefitStatus = new EmployeeBenefitStatus();
        $employeeBenefitStatus->employee_benefit_id = $employeeBenefit_ID;
        $employeeBenefitStatus->effective_date = $request['effective_date'];
        $employeeBenefitStatus->enrollment_status = $request['status'];
        $employeeBenefitStatus->enrollment_status_tracking_field = $trackingField;
        $employeeBenefitStatus->save();
    }
}
