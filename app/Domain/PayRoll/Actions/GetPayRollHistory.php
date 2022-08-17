<?php

namespace App\Domain\PayRoll\Actions;
use App\Domain\PayRoll\Models\PayrollHistory;

class GetPayRollHistory
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute()
    {
        $payrollHistory =  PayrollHistory::with('employee')->get();
        return $payrollHistory;
    }
}
