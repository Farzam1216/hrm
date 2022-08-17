<?php

namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use Carbon\Carbon;

class AddTimeOffPolicy
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        $policy = new Policy();
        $policy->policy_name = $data['policy_name'];
        $policy->policy_type = $data['policy_type'];
        $policy->first_accrual = $data['first-accrual'];
        //when Carryover Amount is set to unlimited then no carryover date will be provided
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

        $manageTimeOffTypes = new ManageTimeOffTypesAndPoliciesPermissions();
        $manageTimeOffTypes->execute("policy", $policy->id, "create");

        $policyLevel = new AddTimeOffPolicyLevel();
        $policyLevel->execute($data, $policy->id);
    }
}
