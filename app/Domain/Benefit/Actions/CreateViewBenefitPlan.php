<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlanType;

class CreateViewBenefitPlan
{

    /**
     * @param $data
     * @param $planType
     * @return mixed
     */
    public function execute($data, $planType)
    {
        $planType = str_replace('_', ' ', $planType);
        $benefitPlanType = BenefitPlanType::where('name', $planType)->first();
        return $benefitPlanType;
    }
}
