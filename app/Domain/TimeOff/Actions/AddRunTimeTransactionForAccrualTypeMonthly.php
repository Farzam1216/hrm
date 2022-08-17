<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddRunTimeTransactionForAccrualTypeMonthly
{
    /**
     * @param $data
     * @param $balance
     * @param $levelData
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     * @param $type
     * @return bool
     */
    public function execute($data, &$balance, $levelData, $policy, $currentDate, &$recentAutoTransaction, $type)
    {
        $accrualStartDate = Carbon::parse($levelData['levelStart']);
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualDate = $LevelDataDecoded['accrual-firstdatemonthly'];
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $currentAccrualDate = Carbon::createFromFormat('n-j-Y', $accrualStartDate->month . '-' . $accrualDate . '-' . $accrualStartDate->year);
        $nextAccuralDate = $currentAccrualDate->copy()->addMonthsNoOverflow(1)->startOfDay();
        $prevAccuralDate = $currentAccrualDate->copy()->addMonthsNoOverflow(-1)->startOfDay();
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($policy->accrual_happen == "At the end of period") {
            $nextAccuralEndDate = $recentAutoTransaction->copy()->addMonthsNoOverflow(1)->addDays(1);
            if ($currentDate == $nextAccuralEndDate->startOfDay()) {
                $action = "Accrued Amount " . $recentAutoTransaction->copy()->addDays(1)->toDateString() . " to " . $recentAutoTransaction->copy()->addMonthsNoOverflow(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                return true;
            }
        } else {
            if ($currentDate == $recentAutoTransaction->copy()->addDays(1)->startOfDay() && $currentDate->day == $accrualDate) {
                $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $recentAutoTransaction->copy()->addMonthsNoOverflow(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                return true;
            }
        }
    }
}
