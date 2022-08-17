<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;

class UpdateBenefitGroup
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($data, $id)
    {
        $benefitGroup = BenefitGroup::find($id);
        $benefitGroup->name = $data->name;
        $benefitGroup->save();
        (new RemoveEmployeesFromBenefitGroup())->execute($benefitGroup);
        (new UpdateBenefitGroupEmployee())->execute($benefitGroup, $data);
    }
}
