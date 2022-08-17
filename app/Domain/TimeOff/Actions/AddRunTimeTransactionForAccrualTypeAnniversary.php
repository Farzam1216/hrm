<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddRunTimeTransactionForAccrualTypeAnniversary
{
    /**
     * @param $data
     * @param $balance
     * @param $levelData
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     * @param $joining_date
     * @param $type
     */
    public function execute($data, &$balance, $levelData, $policy, $currentDate, &$recentAutoTransaction, $joining_date, $type)
    {
        $accrualStartDate = Carbon::parse($levelData['levelStart']);
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $accrualDate = $joining_date->copy()->day;
        $accrualMonth = $joining_date->copy()->month;
        $currentAccrualDate = Carbon::createFromFormat('n-j-Y', $accrualMonth . '-' . $accrualDate . '-' . $accrualStartDate->year)->startOfDay();
        $nextAccuralDate = $currentAccrualDate->copy()->addYears(1)->startOfDay();
        $prevAccuralDate = $currentAccrualDate->copy()->addYears(-1)->startOfDay();
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($policy->accrual_happen == "At the end of period") {
            $nextAccuralEndDate = $recentAutoTransaction->copy()->addYearNoOverflow(1)->addDays(1);
            if ($currentDate == $nextAccuralEndDate->startOfDay()) {
                if ($currentDate->month == $joining_date->month && $currentDate->day == $joining_date->day) {
                    $action = "Accrued Amount " . $recentAutoTransaction->copy()->addDays(1)->toDateString() . " to " . $recentAutoTransaction->copy()->addYearNoOverflow(1)->toDateString();
                    $balance = $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                }
            }
        } else {
            if ($currentDate == $recentAutoTransaction->copy()->addDays(1)->startOfDay() && $currentDate->month == $joining_date->month && $currentDate->day == $joining_date->day) {
                $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $recentAutoTransaction->copy()->addYearNoOverflow(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
            }
        }
    }
}
