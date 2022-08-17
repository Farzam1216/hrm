<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;
use Illuminate\Database\Eloquent\Builder;

class GetBenefitPlanType
{
    /**
     * @return BenefitPlan[]|Builder[]|Collection
     */
    public function execute($id)
    {
        return BenefitPlan::find($id);;
    }
}