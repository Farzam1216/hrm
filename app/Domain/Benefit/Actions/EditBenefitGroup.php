<?php


namespace App\Domain\Benefit\Actions;

class EditBenefitGroup
{
    public function execute($id)
    {
        $data['benefitGroup'] = (new GetBenefitGroup())->execute($id);
        $data['employeesInBenefitGroup'] = (new GetEmployeesInBenefitGroup())->execute($id);
        $data['employeesNotInBenefitGroup'] = (new AvailableEmployees())->execute($id);
        return $data;
    }
}
