<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;

class StoreMultiplePayRollDecisionById
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request)
    {
        $ids = explode(',', $request->ids);
        $payrolls =  PayRoll::find($ids);

        foreach ($payrolls as $payroll) {
            $payroll->status = $request->decision;
            $payroll->reason = $request->reason;
            $payroll->save();
        }
        
        return $payrolls;
    }
}
