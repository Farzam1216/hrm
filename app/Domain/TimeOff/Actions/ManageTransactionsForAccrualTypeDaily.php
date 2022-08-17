<?php


namespace App\Domain\TimeOff\Actions;

class ManageTransactionsForAccrualTypeDaily
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
            $firstTransaction = new AddFirstTransactionForAccrualTypeDaily();
            $isFirstAccrualHappen = $firstTransaction->execute(
                $data,
                $request,
                $policy,
                $levelData,
                $currentDate,
                $balance,
                $recentAutoTransaction,
                $type
            );
        }
        //Runtimecalculations which start after most recent PTO transaction.
        if ($recentAutoTransaction != null) {
            $runTimeTransaction = new AddRunTimeTransactionForAccrualTypeDaily();
            $runTimeTransaction->execute(
                $data,
                $balance,
                $levelData,
                $policy,
                $currentDate,
                $recentAutoTransaction,
                $type
            );
        }
        return $data;
    }
}
