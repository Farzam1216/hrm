<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayScheduleDate;

class UpdatePayDateById
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request)
    {
        $payScheduleDate = PayScheduleDate::find($request->date_id);
        $payScheduleDate->pay_date = $request->pay_date;
        $payScheduleDate->adjustment = "manual";
        $payScheduleDate->save();

        return $payScheduleDate;
    }
}
