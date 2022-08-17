<?php


namespace App\Domain\Benefit\Actions;

class AddEmployeeInBenefitGroup
{
    /**
     * Add employees in benefitgroup
     * @param $data
     * @param $benefitGroupPlan
     */
    public function execute($data, $benefitGroupPlan)
    {
        if (isset($data['employees_Id'])) {
            foreach ($data['employees_Id'] as $employee_id) {
                $eligibilityDate = (new CalculateEmployeeBenefitEligibility())->execute($employee_id, $benefitGroupPlan);
                $employeeBenefit = (new UpdateEmployeeBenefits)->execute($employee_id, $benefitGroupPlan->id);
                (new UpdateEligibility())->execute($employeeBenefit->id, $eligibilityDate, 'notEligible', 'eligible', $benefitGroupPlan->plan_id);
            }
        }
    }
}
