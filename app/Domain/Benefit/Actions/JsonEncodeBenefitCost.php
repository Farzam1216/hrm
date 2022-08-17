<?php

namespace App\Domain\Benefit\Actions;

class JsonEncodeBenefitCost
{
    /**
     * @param $request
     * @return false|string|null
     */
    public function execute($request, $costOf)
    {
        if ($request[$costOf . 'PaysAmount'] == null) {
            if ($request['status'] == 'enroll') {
                $benefitCost = (new GetPlanCostForBenefitIfExists())->execute($request['group_plan_ID'], $costOf);
                return json_encode($benefitCost);
            }
            return null;
        } else {
            $benefitCost[$costOf . 'PaysType'] = $request[$costOf . 'PaysType'];
            $benefitCost[$costOf . 'PaysAmount'] = $request[$costOf . 'PaysAmount'];
            $benefitCost[$costOf . 'CurrencyCode'] = $request[$costOf . 'CurrencyCode'];
            return json_encode($benefitCost);
        }
    }
}
