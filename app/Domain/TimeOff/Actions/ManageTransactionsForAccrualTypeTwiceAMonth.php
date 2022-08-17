<?php


namespace App\Domain\TimeOff\Actions;

class ManageTransactionsForAccrualTypeTwiceAMonth
{
    public function execute($data, &$balance, $type, $policy, $levelData, $request, $joining_date, $currentDate, &$recentAutoTransaction)
    {
        //First Accrual
        if ($recentAutoTransaction == null) {
            $firstTransactionAccrualTypeTwiceAMonth= new AddFirstTransactionForAccrualTypeTwiceAMonth();
            $isFirstAccrualHappen =  $firstTransactionAccrualTypeTwiceAMonth->execute($data, $request, $policy, $levelData, $currentDate, $balance, $recentAutoTransaction, $type);
        }
        //Runtimecalculations which start after most recent PTO transaction.
        if ($recentAutoTransaction != null) {
            $runtimeTransactions = new AddRunTimeTransactionForAccrualTypeTwiceAMonth();
            $runtimeTransactions->execute($data, $balance, $levelData, $policy, $currentDate, $recentAutoTransaction, $type);
        }
        return $data;
    }
}
