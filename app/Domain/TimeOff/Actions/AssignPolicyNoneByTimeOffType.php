<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;

class AssignPolicyNoneByTimeOffType
{
    /**
     * @param $assignTimeOffType
     */
    public function execute($assignTimeOffType)
    {
        $policy = Policy::where('time_off_type', $assignTimeOffType->type_id)->where('policy_name', 'None')->first();
        AssignTimeOffType::updateOrCreate(
            [
                'id' => $assignTimeOffType->id
            ],
            [
                'accrual_option' => $policy->policy_name, 'attached_policy_id' => $policy->id
            ]
        );
    }
}
