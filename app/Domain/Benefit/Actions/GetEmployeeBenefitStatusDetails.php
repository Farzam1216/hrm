<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeBenefitStatus;

class GetEmployeeBenefitStatusDetails
{
    public function execute($request)
    {
        //employeeID_GoupPlanID_Status
        $status = $request[2];

        $employeeBenefit['employee'] = EmployeeBenefit::where(['employee_id' => $request[0], 'benefit_group_plan_id' => $request[1]])->first();
        $employeeBenefit['status'] = EmployeeBenefitStatus::where(['employee_benefit_id' => $employeeBenefit['employee']->id, 'enrollment_status' => $status])->first();
        if (isset($employeeBenefit['employee']->employee_payment)) {
            $employeeBenefit['employee_payment'] = json_decode($employeeBenefit['employee']->employee_payment, true);
        }
        if (isset($employeeBenefit['employee']->company_payment)) {
            $employeeBenefit['company_payment'] = json_decode($employeeBenefit['employee']->company_payment, true);
        }

        return $employeeBenefit;
    }
}
