<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;

class AssignPolicyManualByTimeOffType
{
    /**
     * @param $assignTimeOffType
     */
    public function execute($assignTimeOffType)
    {
        $policy = Policy::where('time_off_type', $assignTimeOffType->type_id)->where('policy_name', 'Manual Updated Balance')->first();
        AssignTimeOffType::updateOrCreate(
            [
                'id' => $assignTimeOffType->id
            ],
            [
                'accrual_option' => 'Manual', 'attached_policy_id' => $policy->id
            ]
        );
    }
}
