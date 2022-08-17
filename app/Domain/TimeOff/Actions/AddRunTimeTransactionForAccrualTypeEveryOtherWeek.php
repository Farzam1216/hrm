<?php

namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddRunTimeTransactionForAccrualTypeEveryOtherWeek
{
    /**
     * @param $data
     * @param $balance
     * @param $levelData
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     */
    public function execute($data, &$balance, $levelData, $policy, $currentDate, &$recentAutoTransaction)
    {
        $accrualStartDate = $levelData['levelStart'];
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualDay = $LevelDataDecoded['accrual-day'];
        $accrualHours = $LevelDataDecoded['accrual-hours'];
        $accrualWeekNumber['accrualWeek'] = $LevelDataDecoded['accrual-week']; //either 0 or 1
        $accrualWeekNumber['accrualStartDateWeekNumber'] = Carbon::parse($accrualStartDate)->format('W')%2;// 0=Even number of week else Odd
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        $getEndDate = new GetAccrualEndDate();
        $recentAutoTransaction = $getEndDate->execute($recentAutoTransaction);
        $nextAccuralEndDate = $recentAutoTransaction->copy()->addWeek(2)->addDays(1);
        if ($currentDate->gte($recentAutoTransaction)) {
            if ($policy->accrual_happen == "At the end of period") {
                if ($currentDate == $nextAccuralEndDate && $currentDate->englishDayOfWeek == $accrualDay) {
                    $action = "Accrued Amount " . $recentAutoTransaction->copy()->addDays(1)->toDateString() . " to " . $nextAccuralEndDate->copy()->addDays(-1)->toDateString();
                    $balance = $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                }
            } else {
                if ($currentDate == $recentAutoTransaction->copy()->addDays(1) && $currentDate->englishDayOfWeek == $accrualDay) {
                    $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $nextAccuralEndDate->copy()->addDays(-1)->toDateString();
                    $balance = $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                }
            }
        }
    }
}
