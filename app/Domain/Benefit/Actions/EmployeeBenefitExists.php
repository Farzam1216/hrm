<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;

class EmployeeBenefitExists
{
    /**
     * @param $request
     * @return bool
     */
    public function execute($request)
    {
        if (EmployeeBenefit::where('benefit_group_plan_id', $request['group_plan_ID'])->where(
            'employee_id',
            $request['employee_ID']
        )->first()) {
            return true;
        } else {
            false;
        }
        return false;
    }
}
