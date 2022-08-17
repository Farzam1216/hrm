<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Models\Employee;

class GetEmployeeBenefitDetails
{
    /**
     * @param $userID
     * @return mixed
     */
    public function execute($userID)
    {
        $BenefitDetails['employee'] = Employee::with('employeeInBenefitGroup', 'employeeInBenefitGroup.benefitGroup', 'benefitGroupPlan.employeeBenefitDetails.employeeBenefitStatuses')
            ->where('id', $userID)->first();
        $count = 1;
        foreach ($BenefitDetails['employee']->benefitGroupPlan as $plan) {
            foreach ($plan->employeeBenefitDetails as $employeeBenefit) {
                //We only need benefit details of given employee
                if ($employeeBenefit->employee_id == $userID) {
                    $BenefitDetails[$count]['groupPlan'] = $plan;
                    $BenefitDetails[$count]['employeeBenefit'] = $employeeBenefit;
                    $BenefitDetails[$count]['status'] = $employeeBenefit->employeeBenefitStatuses->sortBy('effective_date');
                    $BenefitDetails[$count]['list'] = (new GetStatusList())->execute($BenefitDetails[$count]['status']);
                    $count++;
                }
            }
        }
        return $BenefitDetails;
    }
}
