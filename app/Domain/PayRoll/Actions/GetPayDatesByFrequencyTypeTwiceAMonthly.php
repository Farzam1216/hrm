<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAWeekend;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAHoliday;

class GetPayDatesByFrequencyTypeTwiceAMonthly
{
    public function execute($request, $holidays, $today, $data, $index)
    {
        for ($month = 1; $month <= 12; $month++) {
            $month_end_date = Carbon::parse($today->year.'-'.$month.'-'.'01')->endOfMonth();

            for ($interval = 1; $interval <= 2; $interval++) {                
                if ($interval == 1) {
                    $period_end = Carbon::parse($today->year .'-'.$month .'-'. $request->first_day);

                    if ($month == 1) {
                        $previous_year = $today->year-1;

                        if ($request->second_day == 'last day') {
                            $month_end = Carbon::parse($previous_year .'-12-01')->endOfMonth();
                            $period_start = Carbon::parse($previous_year .'-'. '12' .'-'. $month_end->day . " 1days");
                        } else {
                            $period_start = Carbon::parse($previous_year .'-'. '12' .'-'. $request->second_day . " 1days");
                        }
                    } else {
                        $previous_month = $month-1;
                        $month_end = Carbon::parse($today->year .'-'. $previous_month .'-01')->endOfMonth();

                        if ($request->second_day == 'last day') {
                            $period_start = Carbon::parse($today->year .'-'. $previous_month .'-'. $month_end->day . " 1days");
                        } else {
                            $period_start = Carbon::parse($today->year .'-'. $previous_month .'-'. $request->second_day . " 1days");
                        }
                    }
                }
                
                if ($interval == 2) {
                    if ($request->second_day == 'last day') {
                        $period_end = Carbon::parse($month_end_date);
                    } else {
                        $period_end = Carbon::parse($today->year.'-'.$month.'-'.$request->second_day);
                    }

                    $period_start = Carbon::parse($today->year.'-'.$month.'-'.$request->first_day." 1days");
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
