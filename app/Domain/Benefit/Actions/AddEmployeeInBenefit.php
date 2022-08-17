<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;

class AddEmployeeInBenefit
{
    /**
     * @param $request
     * @param $employeeCost
     * @param $companyCost
     * @return EmployeeBenefit
     */
    public function execute($request, $employeeCost, $companyCost)
    {
        $employeeBenefit = new EmployeeBenefit();
        $employeeBenefit->benefit_group_plan_id = $request['group_plan_ID'];
        $employeeBenefit->employee_id = $request['employee_ID'];
        $employeeBenefit->employee_benefit_plan_coverage = $request['coverage'];
        $employeeBenefit->deduction_frequency = $request['deduction_frequency'];
        $employeeBenefit->employee_payment = $employeeCost;
        $employeeBenefit->company_payment = $companyCost;
        $employeeBenefit->save();
        return $employeeBenefit;
    }
}
