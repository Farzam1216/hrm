<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\PolicyLevel;

class ViewAllPoliciesOtherThanNullOrManual
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute()
    {
        $data['policyLevel'] = PolicyLevel::with('policy', 'policy.timeoff')->get();
        $data['policy'] = Policy::with('timeoff')->Where('policy_name', '!=', 'None')->Where('policy_name', '!=', 'Manual Updated Balance')->get();
        return $data;
    }
}
