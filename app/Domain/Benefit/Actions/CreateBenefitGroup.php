<?php


namespace App\Domain\Benefit\Actions;

class CreateBenefitGroup
{
    public function execute()
    {
        $data['employees'] = (new GetEmployees())->execute();
        $data['benefitPlans'] = (new GetBenefitPlans())->execute();
        return $data;
    }
}
