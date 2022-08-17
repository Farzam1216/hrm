<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefitStatus;

class UpdateBenefitStatusTransaction
{
    /**
     * @param $employeeBenefitID
     * @param $request
     * @param $trackingFieldData
     */
    public function execute($employeeBenefitID, $request, $trackingFieldData)
    {
        $employeeBenefitStatus = EmployeeBenefitStatus::where('employee_benefit_id', $employeeBenefitID)->where('enrollment_status', $request['status'])->first();
        $employeeBenefitStatus->effective_date = $request['effective_date'];
        $employeeBenefitStatus->enrollment_status_tracking_field = $trackingFieldData;
        $employeeBenefitStatus->save();
    }
}
