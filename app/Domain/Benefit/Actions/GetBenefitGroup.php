<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;

class GetBenefitGroup
{
    /**
     * get benefit group
     * @param $id
     * @return benefitGroup
     */
    public function execute($id)
    {
        return BenefitGroup::where('id', $id)->first();
    }
}
