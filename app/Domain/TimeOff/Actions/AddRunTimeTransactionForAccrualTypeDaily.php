<?php


namespace App\Domain\TimeOff\Actions;

class AddRunTimeTransactionForAccrualTypeDaily
{
    /**
     * @param $data
     * @param $balance
     * @param $levelData
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     */
    public function execute($data, &$balance, $levelData, $policy, $currentDate, &$recentAutoTransaction, $type)
    {
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        // $accrualHours = $this->checkMaxAccrual($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($policy->accrual_happen == "At the end of period") {
            if ($currentDate == $recentAutoTransaction->copy()->addDays(2)->startOfDay()) {
                $action = "Accrual for " . $recentAutoTransaction->copy()->addDays(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
            }
        } else {
            if ($currentDate == $recentAutoTransaction->copy()->addDays(1)->startOfDay()) {
                $action = "Accrual for " . $recentAutoTransaction->copy()->addDays(1)->toDateString();
                $balance = $balance + $accrualHours;
                $createTrasanction = new CreateTransactionForTimeOff();
                $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
            }
        }
    }
}
