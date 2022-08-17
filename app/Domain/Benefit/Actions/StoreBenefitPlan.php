<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\BenefitPlanCoverage;
use Carbon\Carbon;

class StoreBenefitPlan
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        $dateRange = explode(' ', $data['date_range']);
        $benefitPlan = new BenefitPlan();
        $benefitPlan->name = $data['plan_name'];
        $benefitPlan->start_date = Carbon::parse($dateRange[0])->toDateString();
        $benefitPlan->plan_type_id = $data['plan_id'];
        $benefitPlan->end_date = Carbon::parse($dateRange[2])->toDateString();
        if (isset($data['rate_select'])) {
            $benefitPlan->plan_cost_rate = $data['rate_select'];
        }
        $benefitPlan->description = $data['description'];
        $benefitPlan->plan_URL = $data['plan_URL'];
        if (isset($data['reimbursement_amount'])) {
            $benefitPlan->reimbursement_amount = $data['reimbursement_amount'];
        }
        if (isset($data['reimbursement_frequncy'])) {
            $benefitPlan->reimbursement_frequency = $data['reimbursement_frequncy'];
        }
        if (isset($data['reimbursement_currency'])) {
            $benefitPlan->reimbursement_currency = $data['reimbursement_currency'];
        }
        $benefitPlan->save();
        (new ManageBenefitPlanPermissions())->execute("benefitplan", $benefitPlan->id, "create");
        if (!empty($data['plan_coverages'])) {
            foreach ($data['plan_coverages'] as $coverage) {
                $benefitPlanCoverages = new BenefitPlanCoverage();
                $benefitPlanCoverages->coverage_name = $coverage['plan_coverage_type'];
                $benefitPlanCoverages->plan_id = $benefitPlan->id;
                if (isset($coverage['total_cost'])) {
                    $benefitPlanCoverages->total_cost = $coverage['total_cost'];
                }
                if (isset($coverage['cost_currency'])) {
                    $benefitPlanCoverages->cost_currency = $coverage['cost_currency'];
                }
                $benefitPlanCoverages->save();

            }
        }
    }
}
