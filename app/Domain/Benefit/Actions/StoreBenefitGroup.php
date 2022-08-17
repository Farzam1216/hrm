<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;

class StoreBenefitGroup
{
    /**
     * @param $data
     */

    public function execute($data)
    {
        $benefitGroup = new BenefitGroup();
        $benefitGroup->name = $data->name;
        $benefitGroup->payperiod = $data->payperiod;
        $benefitGroup->save();
        //save employees in benefit group
        (new CreateBenefitGroupEmployee())->execute($benefitGroup, $data);
        //save plan in benefit group plan
        (new CreateBenefitGroupPlan())->execute($benefitGroup, $data);
    }
}
