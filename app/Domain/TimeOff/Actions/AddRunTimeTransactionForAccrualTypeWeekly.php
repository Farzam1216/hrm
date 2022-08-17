<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddRunTimeTransactionForAccrualTypeWeekly
{
    /**
     * @param $data
     * @param $balance
     * @param $levelData
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     * @param $type
     */
    public function execute($data, &$balance, $levelData, $policy, $currentDate, &$recentAutoTransaction, $type)
    {
        $accrualStartDate = $levelData['levelStart'];
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualEnglishDay = $LevelDataDecoded['first-accrual-day'];
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $nextAccuralDate = Carbon::parse("Next $accrualEnglishDay") . $accrualStartDate;
        $prevAccuralDate = Carbon::parse("Last $accrualEnglishDay") . $accrualStartDate;
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($policy->accrual_happen == "At the end of period") {
            $nextAccuralEndDate = $recentAutoTransaction->copy()->addWeek(1)->addDays(1);
            if ($currentDate == $nextAccuralEndDate->startOfDay()) {
                $action = "Accrued Amount " . $recentAutoTransaction->copy()->addDays(1)->toDateString() . " to " . $recentAutoTransaction->copy()->addWeek(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                return true;
            }
        } else {
            if ($currentDate == $recentAutoTransaction->copy()->addDays(1)->startOfDay() && $currentDate->englishDayOfWeek == $accrualEnglishDay) {
                $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $recentAutoTransaction->copy()->addWeek(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                return true;
            }
        }
    }
}
