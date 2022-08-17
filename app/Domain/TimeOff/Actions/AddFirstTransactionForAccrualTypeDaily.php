<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransactionForAccrualTypeDaily
{
    /**
     * @param $data
     * @param $request
     * @param $policy
     * @param $levelData
     * @param $currentDate
     * @param $balance
     * @param $recentAutoTransaction
     * @param $type
     * @return bool
     */
    public function execute(
        $data,
        $request,
        $policy,
        $levelData,
        $currentDate,
        &$balance,
        &$recentAutoTransaction,
        $type
    ) {
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualHours = $amountAccrued["accrual-hours"];
        $levelStartDate = $levelData['levelStart'];
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($currentDate->gte($levelStartDate)) {
            if ($policy->accrual_happen == "At the end of period") {
                $action = "Accrual for " . $currentDate->toDateString() . " to " . $levelStartDate;
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute(
                    $data,
                    $action,
                    $balance,
                    $accrualHours,
                    $currentDate
                );
                return true;
            } else {
                if ($currentDate == Carbon::parse($levelStartDate)->startOfDay()) {
                    $action = "Accrual for " . $levelStartDate;
                    $balance = $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute(
                        $data,
                        $action,
                        $balance,
                        $accrualHours,
                        $currentDate
                    );
                    return true;
                }
            }
        }
    }
}
