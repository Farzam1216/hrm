<?php


namespace App\Domain\Employee\Actions;

use App\Domain\TimeOff\Models\LeaveType;

class AssignLeavesToNewEmployee
{
    public function execute($employee)
    {
        $leave_types = LeaveType::get();
        $arr = [];
        foreach ($leave_types as $leave_type) {
            $arr[$leave_type->id] = ['count' => $leave_type->amount];
        }
        $employee->leaveTypes()->sync($arr);
    }
}
