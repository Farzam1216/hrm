<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;

class RecalculatePayDateIfItsOnAWeekend
{
    public function execute($request, $pay_date)
    {
        $weekendCheck = false;
        $adjustment = false;

        while ($weekendCheck == false) {
            if ($pay_date->format('l') == "Saturday" || $pay_date->format('l') == "Sunday") {
                if ($request->exceptional_pay_day == "Before") {
                    $pay_date = Carbon::parse($pay_date . " -1 days");
                    $adjustment = true;
                }
                if ($request->exceptional_pay_day == "After") {
                    $pay_date = Carbon::parse($pay_date . " 1 days");
                    $adjustment = true;
                }
            }

            if ($pay_date->format('l') != "Saturday" && $pay_date->format('l') != "Sunday") {
                $weekendCheck = true;
            }
        }

        return $data = [
            'adjustment' => $adjustment,
            'pay_date' => $pay_date
        ];
    }
}
