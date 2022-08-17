<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\PayRoll\Models\PaySchedule;

class GetEmployeesWithPaySchedule
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($id)
    {
        $pay_schedule = PaySchedule::find($id);
        $employees = Employee::with(['assignedPaySchedule', 'assignedPaySchedule.paySchedule'])->get();

        return $data = [
            'pay_schedule' => $pay_schedule,
            'employees' => $employees
        ];
    }
}
