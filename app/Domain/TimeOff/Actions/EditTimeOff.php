<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\PolicyLevel;
use App\Domain\TimeOff\Models\TimeOffType;

class EditTimeOff
{
    /**
     * @param $id
     * @return mixed
     */
    public function execute($id)
    {
        $data['policy'] = Policy::with('timeoff')->find($id);
        $data['policyLevel'] = PolicyLevel::where('policy_id', $id)->get();
        $data['TimeOffTypes'] = TimeOffType::all();
        return $data;
    }
}
