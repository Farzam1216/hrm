<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Models\PaySchedule;

class EditPaySchedule
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($id)
    {
        $now = Carbon::now();

        $paySchedule =  PaySchedule::where('id', $id)->with(['payScheduleDates' => function ($query) use ($now) {
            return $query->where('period_end', 'LIKE', '%'.$now->year);
        }])->first();
        
        return $paySchedule;
    }
}
