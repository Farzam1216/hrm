<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;
use Illuminate\Database\Eloquent\Builder;

class GetPlanDetail
{
    /**
     * @param $request
     * @return BenefitPlan[]|Builder[]|Collection
     */
    public function execute($request)
    {
        if (isset($request->id)) {
            return BenefitPlan::with('planCoverages')->where('id', $request->id)->get();
        }
    }
}
