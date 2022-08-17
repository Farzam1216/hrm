<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefitStatus;

class CreateBenefitStatusTransaction
{
    /**
     * @param $employeeBenefitID
     * @param $effectiveDate
     * @param $status
     * @param $trackingFieldData
     */
    public function execute($employeeBenefitID, $effectiveDate, $status, $trackingFieldData)
    {
        EmployeeBenefitStatus::create(
            [
                'employee_benefit_id' => $employeeBenefitID,
                'effective_date' => $effectiveDate,
                'enrollment_status' => $status,
                'enrollment_status_tracking_field' => $trackingFieldData
            ]
        );
    }
}
