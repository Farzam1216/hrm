<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\AssignTimeOffType;

class GetAllPolicies
{
    /**
     * @param null $data
     * @return mixed
     */
    public function execute($data = null, $employee_id)
    {
        if ($data != null) {
            $type_id = $data->typeId;
            $getPolicies = Policy::where('time_off_type', $type_id)->get();
            $attachedPolicy = AssignTimeOffType::where('employee_id', $employee_id)->first();
            return $data = [
                'getPolicies' => $getPolicies,
                'attachedPolicy' => $attachedPolicy
            ];
        }
    }
}
