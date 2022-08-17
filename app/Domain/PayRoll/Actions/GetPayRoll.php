<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;
use App\Domain\Employee\Models\Employee;
use App\Domain\PayRoll\Models\PaySchedule;

class GetPayRoll
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute()
    {
        $employees =  Employee::all();
        $paySchedules =  PaySchedule::all();
        $payrolls =  PayRoll::with('employee')->get();
        
        return $data = [
            'payrolls' => $payrolls,
            'employees' => $employees,
            'paySchedules' => $paySchedules
        ];
    }
}
