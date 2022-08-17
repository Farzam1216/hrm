<?php


namespace App\Domain\TimeOff\Actions;

class ManageTransactionsForAccrualTypeAnniversary
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
            $firstTransactionAccrualTypeAnniversary = new AddFirstTransactionForAccrualTypeAnniversary();
            $isFirstAccrualHappen = $firstTransactionAccrualTypeAnniversary->execute($data, $policy, $levelData, $currentDate, $balance, $recentAutoTransaction, $joining_date, $type);
        }
        //Manual Transactions
        if ($recentAutoTransaction != null) {
            $runTimeTransactionForAccrualTypeAnniversary = new AddRunTimeTransactionForAccrualTypeAnniversary();
            $runTimeTransactionForAccrualTypeAnniversary->execute($data, $balance, $levelData, $policy, $currentDate, $recentAutoTransaction, $joining_date, $type);
        }
        return $data;
    }
}
