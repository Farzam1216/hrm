<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;

class StoreSinglePayRollDecisionById
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request)
    {
        
        $payroll =  PayRoll::find($request->id);
        $payroll->status = $request->decision;
        $payroll->reason = $request->reason;
        $payroll->save();
        
        return $payroll;
    }
}
