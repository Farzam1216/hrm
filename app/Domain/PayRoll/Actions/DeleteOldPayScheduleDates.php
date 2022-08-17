<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayScheduleDate;

class DeleteOldPayScheduleDates
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($id)
    {
        $payScheduleDates = PayScheduleDate::where('pay_schedule_id', $id)->get();

        foreach ($payScheduleDates as $date) {
            $date->delete();
        }

        return $payScheduleDates;
    }
}
