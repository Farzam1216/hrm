<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class CalculateNextAccrualDateforAccrualTypeEveryOtherWeek
{
    /**
     * @param $accrualWeekNumber
     * @param $accrualStartDate
     * @param $accrualDay
     * @return Carbon
     */
    public function execute($accrualWeekNumber, $accrualStartDate, $accrualDay)
    {
        if ($accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek'] && $accrualStartDate->dayOfWeek >= Carbon::parse($accrualDay)->dayOfWeek) {
            $currentAccrualDate = Carbon::parse("this $accrualDay" . $accrualStartDate);
            $nextAccuralDate = Carbon::parse("second $accrualDay next week" . $accrualStartDate);
        } elseif ($accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek'] && $accrualStartDate->dayOfWeek < Carbon::parse($accrualDay)->dayOfWeek) {
            $nextAccuralDate = Carbon::parse("next $accrualDay" . $accrualStartDate);
            $currentAccrualDate = Carbon::parse("last week $accrualDay last week" . $accrualStartDate);
        } else {
            $currentAccrualDate = Carbon::parse("last week $accrualDay" . $accrualStartDate);
            $nextAccuralDate = Carbon::parse("next week $accrualDay" . $accrualStartDate);
        }
        return $nextAccuralDate;
    }
}
