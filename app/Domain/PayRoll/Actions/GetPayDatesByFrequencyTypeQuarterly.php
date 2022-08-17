<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAWeekend;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAHoliday;

class GetPayDatesByFrequencyTypeQuarterly
{
    public function execute($request, $holidays, $today, $data, $index)
    {
        for ($month = 1; $month <= 12; $month++) {
            $month_end_date = Carbon::parse($today->year.'-'.$month.'-'.'01')->endOfMonth();

            if ($month == $request->first_month) {
                $previous_year = $today->year-1;
                
                if ($request->first_day == 'last day') {
                    $period_end = $month_end_date;
                } else {
                    $period_end = Carbon::parse($today->year .'-'.$month .'-'. $request->first_day);
                }

                if ($request->fourth_day == 'last day') {
                    $month_end = Carbon::parse($previous_year .'-'. $request->fourth_month . '-01')->endOfMonth();
                    $period_start = Carbon::parse($previous_year .'-'. $request->fourth_month .'-'. $month_end->day . " 1days");
                } else {
                    $period_start = Carbon::parse($previous_year .'-'. $request->fourth_month .'-'. $request->fourth_day . " 1days");
                }
            }

            if ($month == $request->second_month) {
                if ($request->second_day == 'last day') {
                    $period_end = $month_end_date;
                } else {
                    $period_end = Carbon::parse($today->year .'-'.$month .'-'. $request->second_day);
                }

                if ($request->first_day == 'last day') {
                    $month_end = Carbon::parse($today->year .'-'. $request->first_month . '-01')->endOfMonth();
                    $period_start = Carbon::parse($today->year .'-'. $request->first_month .'-'. $month_end->day . " 1days");
                } else {
                    $period_start = Carbon::parse($today->year .'-'. $request->first_month .'-'. $request->first_day . " 1days");
                }
            }

            if ($month == $request->third_month) {
                if ($request->third_day == 'last day') {
                    $period_end = $month_end_date;
                } else {
                    $period_end = Carbon::parse($today->year .'-'.$month .'-'. $request->third_day);
                }

                if ($request->second_day == 'last day') {
                    $month_end = Carbon::parse($today->year .'-'. $request->second_month . '-01')->endOfMonth();
                    $period_start = Carbon::parse($today->year .'-'. $request->second_month .'-'. $month_end->day . " 1days");
                } else {
                    $period_start = Carbon::parse($today->year .'-'. $request->second_month .'-'. $request->second_day . " 1days");
                }
            }

            if ($month == $request->fourth_month) {
                if ($request->fourth_day == 'last day') {
                    $period_end = $month_end_date;
                } else {
                    $period_end = Carbon::parse($today->year .'-'.$month .'-'. $request->fourth_day);
                }

                if ($request->third_day == 'last day') {
                    $month_end = Carbon::parse($today->year .'-'. $request->third_month . '-01')->endOfMonth();
                    $period_start = Carbon::parse($today->year .'-'. $request->third_month .'-'. $month_end->day . " 1days");
                } else {
                    $period_start = Carbon::parse($today->year .'-'. $request->third_month .'-'. $request->third_day . " 1days");
                }
            }

            if ($month == $request->first_month || $month == $request->second_month || $month == $request->third_month || $month == $request->fourth_month) {
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
