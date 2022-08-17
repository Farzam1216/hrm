<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlanType;

class GetBenefitPlanTypes
{
    /**
     * @return BenefitPlanType[]|Collection
     */
    public function execute()
    {
        return BenefitPlanType::has('plans')->get();
    }
}
