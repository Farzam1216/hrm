<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAWeekend;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAHoliday;

class GetPayDatesByFrequencyTypeWeekly
{
    public function execute($request, $holidays, $today, $data, $index)
    {
        for ($month = 1; $month <= 12; $month++) {
            $month_end_date = Carbon::parse($today->year.'-'.$month.'-'.'01')->endOfMonth();

            for ($day = 1; $day <= $month_end_date->day; $day++) {
                $period_end = Carbon::parse($today->year.'-'.$month.'-'.$day);
                
                if ($period_end->format('l') == $request->week_day) {
                    $previous_day = strtotime("last ".$request->week_day, strtotime($period_end));
                    $previous_day = date("Y-m-d",$previous_day);
                    $period_start = Carbon::parse($previous_day." 1days");
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
        }
        
        return $data;
    }
}
