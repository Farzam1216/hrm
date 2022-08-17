<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;

class UpdateEmployeeBenefit
{
    /**
     * @param $request
     * @param $employeeCost
     * @param $companyCost
     * @return mixed
     */
    public function execute($request, $employeeCost, $companyCost)
    {
        $employeeBenefit = EmployeeBenefit::where('benefit_group_plan_id', $request['group_plan_ID'])->where('employee_id', $request['employee_ID'])->first();
        if ($request['status'] == 'enroll') {
            $employeeBenefit->employee_benefit_plan_coverage = $request['coverage'];
            $employeeBenefit->deduction_frequency = $request['deduction_frequency'];
            $employeeBenefit->employee_payment = $employeeCost;
            $employeeBenefit->company_payment = $companyCost;
            $employeeBenefit->save();
        }
        return $employeeBenefit;
    }
}
