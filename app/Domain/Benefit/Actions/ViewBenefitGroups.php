<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;

class ViewBenefitGroups
{
    /**
     * @return BenefitGroup[]|Collection
     */
    public function execute()
    {
        return BenefitGroup::all();
    }
}
