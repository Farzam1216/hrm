<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\PolicyLevel;
use App\Domain\TimeOff\Models\TimeOffType;

class CreateTimeOff
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $data['policy'] = Policy::all();
        $data['TimeOffTypes'] = TimeOffType::all();
        $data['policyLevel'] = PolicyLevel::all();
        return $data;
    }
}
