<?php


namespace App\Domain\Benefit\Actions;

use Illuminate\Support\Facades\DB;

class CreateBenefitPlan
{
    public function execute($request, $planType)
    {
        $benefitViewFields = [];
        $benefitViewFields['benefitFields'] = (new CreateViewBenefitPlan)->execute($request->all(), $planType);
        $benefitViewFields['currency'] = DB::Table('currencies')->get();
        return $benefitViewFields;
    }
}
