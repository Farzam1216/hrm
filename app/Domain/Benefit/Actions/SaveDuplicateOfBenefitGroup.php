<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitGroupPlan;

class SaveDuplicateOfBenefitGroup
{
    public function execute($id)
    {
        $benefitGroup = BenefitGroup::where('id', $id)->first();
        $duplicateGroup = BenefitGroup::create([
            'name' => $benefitGroup->name . ' - Copy',
            'payperiod' => $benefitGroup->payperiod
        ]);
        // duplicate plans for the group
        $benefitGroupPlan = BenefitGroupPlan::where('group_id', $benefitGroup->id)->get();
        if (isset($benefitGroupPlan)) {
            foreach ($benefitGroupPlan as $duplicateGroupPlan) {
                $duplicateGroupPlan = BenefitGroupPlan::create([
                    'group_id' => $duplicateGroup->id,
                    'plan_id' => $duplicateGroupPlan->plan_id,
                    'eligibility' => $duplicateGroupPlan->eligibility,
                    'wait_period' => $duplicateGroupPlan->wait_period,
                    'type_of_period' => $duplicateGroupPlan->type_of_period

                ]);
            }
        } //endif
    }
}
