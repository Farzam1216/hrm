<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupEmployee;

class UpdateBenefitGroupEmployee
{
    /**
     * Update employees in benefit group
     * @param $data
     * @param $benefitGroup
     */
    public function execute($benefitGroup, $data)
    {
        if (isset($data->employees_Id)) {
            foreach ($data->employees_Id as $employee) {
                $benefitGroupEmployee = new BenefitGroupEmployee();
                $benefitGroupEmployee->benefit_group_id = $benefitGroup->id;
                $benefitGroupEmployee->employee_id = $employee;
                $benefitGroupEmployee->save();
            }
        }
    }
}
