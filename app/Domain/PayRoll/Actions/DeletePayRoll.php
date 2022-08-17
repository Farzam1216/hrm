<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\PayRoll\Models\PayRoll;
use App\Domain\PayRoll\Models\PayrollHistory;

class DeletePayRoll
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute()
    {
        $payroll =  PayRoll::truncate();
        return $payroll;
    }
}
