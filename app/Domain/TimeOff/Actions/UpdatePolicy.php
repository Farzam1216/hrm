<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\PolicyLevel;
use Carbon\Carbon;

class UpdatePolicy
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($data, $id)
    {
        $policy = Policy::find($id);
        $policy->policy_name = $data['policy_name'];
        $policy->policy_type = $data['policy_type'];
        $policy->first_accrual = $data['first-accrual'];
        //when Carryover Amount is set to unimlimted then no carryover date will be provided
        if (isset($data['carryover-date'])) {
            if ($data['carryover-date'] == 'Other') {
                $date = $data['custom-carry-over-date'] . $data['custom-carry-over-month'];
                $format = Carbon::createFromFormat('dm', $date);
                $dt = $format->format('d-m');
                $policy->carry_over_date = $dt;
            } else {
                $policy->carry_over_date = $data['carryover-date'];
            }
        } else {
            $policy->carry_over_date = 'none'; //test purpose
        }
        $policy->accrual_happen = $data['carryover-happen'];
        $policy->time_off_type = $data['time_off_type'];
        $policy->save();
        PolicyLevel::where('policy_id', $id)->delete();
        foreach ($data['level'] as $key => $level) {
            $updatePolicyLevel = new UpdatePolicyLevel();
            $updatePolicyLevel->execute($level, $id);
        }
    }
}
