<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PaySchedule;

class DestoryPayScheduleById
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($id)
    {
        $paySchedule = PaySchedule::find($id);

        $paySchedule->delete();

        return $paySchedule;
    }
}
