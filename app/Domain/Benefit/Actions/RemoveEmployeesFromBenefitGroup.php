<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupEmployee;

class RemoveEmployeesFromBenefitGroup
{
    /**
     * remove employees from Group
     * @param $benefitGroup
     */
    public function execute($benefitGroup)
    {
        BenefitGroupEmployee::where('benefit_group_id', $benefitGroup->id)->delete();
    }
}
