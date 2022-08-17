<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupEmployee;

class CreateBenefitGroupEmployee
{
    /**
     * Add employees in benefit group
     * @param $data
     * @param $benefitGroup
     */
    public function execute($benefitGroup, $data)
    {
        if (isset($data->employees_Id)) {
            (new DeleteEmployeeBenefits())->execute($data);
            foreach ($data->employees_Id as $employeeId) {
                $benefitGroupEmployee = BenefitGroupEmployee::updateOrCreate(
                    ['employee_id' => $employeeId],
                    ['benefit_group_id' => $benefitGroup->id]
                );
            }
        }
    }
}
