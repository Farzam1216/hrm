<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayScheduleDate;

class StorePayScheduleDates
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request, $payScheduleId)
    {
        $total_dates = count($request->period_start_dates);

        for ($index = 0; $index < $total_dates; $index++) {
            $payScheduleDate = new PayScheduleDate();
            $payScheduleDate->period_start = $request->period_start_dates[$index];
            $payScheduleDate->period_end = $request->period_end_dates[$index];
            $payScheduleDate->pay_date = $request->pay_dates[$index];
            $payScheduleDate->pay_schedule_id = $payScheduleId;
            $payScheduleDate->adjustment = $request->adjustments[$index];
            $payScheduleDate->save();
        }
    }
}
