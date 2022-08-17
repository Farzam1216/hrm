<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;

class GetPayRollById
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($id)
    {
        $payroll_ids = explode(',', $id);

        $payrolls =  PayRoll::whereIn('id', $payroll_ids)->with('employee')->get();

        return $payrolls;
    }
}
