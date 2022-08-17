<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\BenefitPlanCoverage;
use Carbon\Carbon;

class SaveDuplicateOfBenefitPlan
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        $benefitPlan = BenefitPlan::where('id', $data['plan_id'])->first();
        $dateRange = explode(' ', $data['date_range']);
        $start_date = Carbon::parse($dateRange[0])->toDateString();
        $end_date = Carbon::parse($dateRange[2])->toDateString();
        $duplicatePlan = BenefitPlan::create([
            'name' => $data['duplicatePlanName'],
            'start_date' => $start_date,
            'plan_type_id' => $benefitPlan->plan_type_id,
            'end_date' => $end_date,
            'plan_cost_rate' => $benefitPlan->plan_cost_rate,
            'description' => $benefitPlan->description,
            'plan_URL' => $benefitPlan->plan_URL,
            'reimbursement_amount' => $benefitPlan->reimbursement_amount,
            'reimbursement_frequency' => $benefitPlan->reimbursement_frequency
        ]);
        //create duplicate coverages for plan w.r.t plan_id
        $benefitPlanCoverage = BenefitPlanCoverage::where('plan_id', $data['plan_id'])->get();
        if (isset($benefitPlanCoverage)) {
            foreach ($benefitPlanCoverage as $duplicatePlanCoverage) {
                $duplicatePlanCoverage = BenefitPlanCoverage::create([
                    'coverage_name' => $duplicatePlanCoverage->coverage_name,
                    'plan_id' => $duplicatePlan->id,
                    'total_cost' => $duplicatePlanCoverage->total_cost,
                    'cost_currency' => $duplicatePlanCoverage->cost_currency
                ]);
            }
        } //endif
    }
}
