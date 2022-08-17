<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PaySchedule;
use App\Domain\PayRoll\Actions\StorePayScheduleDates;


class StorePaySchedule
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request)
    {
        $paySchedule = new PaySchedule();
        $paySchedule->name = $request->name;
        $paySchedule->frequency = $request->frequency;

        if ($request->frequency == 'Weekly') {
            $paySchedule->period_ends = $request->week_day;
        }

        if ($request->frequency == 'Every other week') {
            $paySchedule->period_ends = $request->week_day.','.$request->dates;
        }

        if ($request->frequency == 'Twice a month') {
            $paySchedule->period_ends = $request->first_date.','.$request->second_date;
        }

        if ($request->frequency == 'Monthly') {
            $paySchedule->period_ends = $request->date;
        }

        if ($request->frequency == 'Quarterly') {
            $paySchedule->period_ends = $request->first_date.'-'.$request->first_month.','.$request->second_date.'-'.$request->second_month.','.$request->third_date.'-'.$request->third_month.','.$request->fourth_date.'-'.$request->fourth_month;
        }

        if ($request->frequency == 'Twice a yearly') {
            $paySchedule->period_ends = $request->first_date.'-'.$request->first_month.','.$request->second_date.'-'.$request->second_month;
        }

        if ($request->frequency == 'Yearly') {
            $paySchedule->period_ends = $request->date.'-'.$request->month;
        }

        $paySchedule->pay_days = $request->pay_days;
        $paySchedule->exceptional_pay_day = $request->exceptional_pay_day;
        $paySchedule->save();

        $payScheduleDates = (new StorePayScheduleDates())->execute($request, $paySchedule->id);

        return $paySchedule;
    }
}
