<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAWeekend;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAHoliday;

class GetPayDatesByFrequencyTypeEveryOtherWeek
{
    public function execute($request, $holidays, $today, $data, $index)
    {
        $dates = explode(',', $request->dates);
        $selected_week = date('W', strtotime($dates[0].'-'.$today->year))%2; // 0 = even number of week else Odd

        for ($month = 1; $month <= 12; $month++) {
            $month_end_date = Carbon::parse($today->year.'-'.$month.'-'.'01')->endOfMonth();

            for ($day = 1; $day <= $month_end_date->day; $day++) {
                $period_end = Carbon::parse($today->year.'-'.$month.'-'.$day);
                $weekEvenOdd = date('W', strtotime($day.'-'.$month.'-'.$today->year))%2; // 0 = even number of week else Odd

                if ($weekEvenOdd == $selected_week) {
                    if ($period_end->format('l') == $request->week_day) {
                        $previous_week = strtotime("-2week +2day ".$request->week_day, strtotime($period_end));
                        $period_start = Carbon::parse($previous_week);
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
        }
        
        return $data;
    }
}
