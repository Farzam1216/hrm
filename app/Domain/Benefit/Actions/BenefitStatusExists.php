<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefitStatus;

class BenefitStatusExists
{
    /**
     * @param $employeeBenefit_ID
     * @param $enrollmentStatus
     * @return bool
     */
    public function execute($employeeBenefit_ID, $enrollmentStatus)
    {
        if (EmployeeBenefitStatus::where(['employee_benefit_id' => $employeeBenefit_ID, 'enrollment_status' => $enrollmentStatus])->first()) {
            return true;
        } else {
            return false;
        }
    }
}
