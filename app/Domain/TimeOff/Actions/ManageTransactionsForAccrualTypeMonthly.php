<?php


namespace App\Domain\TimeOff\Actions;

class ManageTransactionsForAccrualTypeMonthly
{
    /**
     * @param $data
     * @param $balance
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $request
     * @param $joining_date
     * @param $currentDate
     * @param $recentAutoTransaction
     * @return mixed
     */
    public function execute($data, &$balance, $type, $policy, $levelData, $request, $joining_date, $currentDate, &$recentAutoTransaction)
    {
        //First Accrual
        if ($recentAutoTransaction == null) {
            $firstTransactionAccrualTypeMonthly = new AddFirstTransactionForAccrualTypeMonthly();
            $isFirstAccrualHappen =  $firstTransactionAccrualTypeMonthly->execute($data, $request, $policy, $levelData, $currentDate, $balance, $recentAutoTransaction, $type);
        }
        //Runtimecalculations which start after most recent PTO transaction.
        if ($recentAutoTransaction != null) {
            $runTimeTransactions = new AddRunTimeTransactionForAccrualTypeMonthly();
            $runTimeTransactions->execute($data, $balance, $levelData, $policy, $currentDate, $recentAutoTransaction, $type);
        }
        return $data;
    }
}