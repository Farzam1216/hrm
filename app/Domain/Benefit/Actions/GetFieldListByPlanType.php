<?php

namespace App\Domain\Benefit\Actions;

class GetFieldListByPlanType
{
    /**
     * @param $benefitPlanDetails
     * @return null
     */
    public function execute($benefitPlanDetails)
    {
        if ((new PlanCoverageExists)->execute($benefitPlanDetails->planCoverages->first())) {
            {
                $fields['requireCoverage'] = true;
                foreach ($benefitPlanDetails->planCoverages as $benefitPlanCoverage) {
                    if (!isset($benefitPlanCoverage->total_cost)) {
                        $fields['requireCost'] = true;
                    }
                }
            }
        } else {
            $planTypeRequiredData = \GuzzleHttp\json_decode($benefitPlanDetails->planType['type_details']);
            $fields = null;
            if (!(in_array('reimbursement_plan_amount', $planTypeRequiredData))) {
                $fields['hasMultiplePaymentOptions'] = true;
            }
        }
        return $fields;
    }
}
