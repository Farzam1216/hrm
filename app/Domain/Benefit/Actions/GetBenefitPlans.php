<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;
use Illuminate\Database\Eloquent\Builder;

class GetBenefitPlans
{
    /**
     * @return BenefitPlan[]|Builder[]|Collection
     */
    public function execute()
    {
        return BenefitPlan::with('planCoverages')->get();
    }
}
