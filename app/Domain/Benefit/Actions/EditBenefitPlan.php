<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\BenefitPlanCoverage;
use App\Domain\Benefit\Models\BenefitPlanType;
use Illuminate\Support\Facades\DB;

class EditBenefitPlan
{
    /**
     * @param $planType
     * @param $id
     * @return array
     */
    public function execute($planType, $id)
    {
        $benefitPlanData = [];
        $benefitPlanData['benefitPlan'] = BenefitPlan::find($id);
        $benefitPlanData['planCoverages'] = BenefitPlanCoverage::where('plan_id', $id)->get();
        $benefitPlanData['planCoveragesName'] = [];
        foreach ($benefitPlanData['planCoverages'] as $planCoverage) {
            array_push($benefitPlanData['planCoveragesName'], $planCoverage->coverage_name);
        }
        $benefitPlanData['dateRange'] = $benefitPlanData['benefitPlan']['start_date'] . ' - ' . $benefitPlanData['benefitPlan']['end_date'];
        $benefitPlanData['planType'] = BenefitPlanType::where('name', $planType)->first();
        $benefitPlanData['currency'] = DB::Table('currencies')->get();
        return $benefitPlanData;
    }
}
