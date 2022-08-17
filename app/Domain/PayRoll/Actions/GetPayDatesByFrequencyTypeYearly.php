<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAWeekend;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAHoliday;

class GetPayDatesByFrequencyTypeYearly
{
    public function execute($request, $holidays, $today, $data, $index)
    {
        for ($month = 1; $month <= 12; $month++) {
            $month_end_date = Carbon::parse($today->year.'-'.$month.'-'.'01')->endOfMonth();

            if ($month == $request->month) {
                $previous_year = $today->year-1;

                if ($request->date == 'last day') {
                    $month_end = Carbon::parse($previous_year .'-'. $month . '-01')->endOfMonth();
                    $period_start = Carbon::parse($previous_year .'-'. $month .'-'. $month_end->day . " 1days");
                    $period_end = $month_end_date;
                } else {
                    $period_start = Carbon::parse($previous_year .'-'. $month .'-'. $request->date . " 1days");
                    $period_end = Carbon::parse($today->year .'-'. $month .'-'. $request->date);
                }

                $pay_date = Carbon::parse($period_end .' '. $request->pay_days . "days");

                $checkWeekend = (new RecalculatePayDateIfItsOnAWeekend())->execute($request, $pay_date);

                $pay_date = $checkWeekend['pay_date'];
                $adjustment = $checkWeekend['adjustment'];

                $checkHoliday = (new RecalculatePayDateIfItsOnAHoliday())->execute($request, $holidays, $pay_date, $adjustment);

                $pay_date = $checkHoliday['pay_date'];
                $adjustment = $checkHoliday['adjustment'];

                $data[$index] = [
                    'period_start' => $period_start->format('d-m-Y'),
                    'period_end'   => $period_end->format('d-m-Y'),
                    'pay_date'     => $pay_date->format('d-m-Y'),
                    'adjustment'   => $adjustment
                ];
                $index++;
            }
        }
        
        return $data;
    }
}
