<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;

class AssignPolicyToEmployees
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        $policyEmployees = AssignTimeOffType::where('attached_policy_id', $data['policy_id'])->get();

        foreach ($policyEmployees as $policyEmployee) {
            $policyEmployee->attached_policy_id = 1;
            $policyEmployee->save();
        }
        if (isset($data['employeeData'])) {
            foreach ($data['employeeData'] as $employee) {
                $policyName = Policy::where('id', $employee['attached_policy_id'])->first()->policy_name;
                $policy = ($policyName == 'None' || $policyName == 'Manual Updated Balance') ? $policyName : 'policy';
                $policy = ($policy == 'Manual Updated Balance') ? 'Manual' : $policy;
                AssignTimeOffType::updateOrCreate(
                    [
                        'employee_id' => $employee['employee_id'],
                        'type_id' => $employee['type_id']
                    ],
                    [
                        'attached_policy_id' => $employee['attached_policy_id'],
                        'accrual_option' => $policy
                    ]
                );
            }
        }
    }
}
