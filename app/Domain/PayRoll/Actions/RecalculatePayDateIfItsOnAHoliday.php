<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Actions\RecalculatePayDateIfItsOnAWeekend;

class RecalculatePayDateIfItsOnAHoliday
{
    public function execute($request, $holidays, $pay_date, $adjustment)
    {
        $holidayCheck = true;
        $dates = [];
        $count = 0;

        foreach ($holidays as $holiday) {
            if (strpos($holiday->date, ' to ') ==  true) {
                $dateRange = explode(' to ', $holiday->date);
                $dateFrom = Carbon::parse($dateRange[0])->format('Y-m-d');
                $dateTo = Carbon::parse($dateRange[1])->format('Y-m-d');
                $periods = \Carbon\CarbonPeriod::create($dateFrom, $dateTo);

                foreach ($periods as $period) {
                    $dates[$count] = $period->format('Y-m-d');
                    $count = $count+1;
                }
            }

            if (strpos($holiday->date, ' to ') ==  false) {
                $dates[$count] = $holiday->date;
                $count = $count+1;
            }
        }

        while ($holidayCheck == true) {
            if (in_array($pay_date->format('Y-m-d'), $dates) == true) {
                if ($request->exceptional_pay_day == "Before") {
                    $pay_date = Carbon::parse($pay_date . " -1days");
                    $adjustment = true;
                }

                if ($request->exceptional_pay_day == "After") {
                    $pay_date = Carbon::parse($pay_date . " 1days");
                    $adjustment = true;
                }

                $checkWeekend = (new RecalculatePayDateIfItsOnAWeekend())->execute($request, $pay_date);

                $pay_date = $checkWeekend['pay_date'];

                $holidayCheck = true;
            }

            if (in_array($pay_date->format('Y-m-d'), $dates) == false) {
                $holidayCheck = false;
            }
        }

        return $data = [
            'adjustment' => $adjustment,
            'pay_date' => $pay_date
        ];
    }
}
