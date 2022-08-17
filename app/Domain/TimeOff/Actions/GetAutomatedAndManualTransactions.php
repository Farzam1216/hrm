<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class GetAutomatedAndManualTransactions
{
    /**
     *
     */
    public function execute($type)
    {
        $manualTransactions = collect();
        $autoTransaction = collect();
        $allTransactions = TimeOffTransaction::where('assign_time_off_id', $type->id)
            ->orderBy('accrual_date', 'asc')
            ->get();
        foreach ($allTransactions as $key => $transaction) {
            $isUsedOrManualAction = false;
            $accuralEndDate = new GetAccrualEndDate();
            $isManual = $accuralEndDate->execute($transaction->action);
            if ($isManual == null) {
                $manualAction = $transaction->action;
                if ($manualAction == "used") {
                    $isUsedOrManualAction = true;
                } elseif ($manualAction == "Manual Adjustment") {
                    $isUsedOrManualAction = true;
                } else {
                    $isUsedOrManualAction = false;
                }
            }
            if ($isManual == null && $isUsedOrManualAction == true) {
                $transaction->balance = number_format($transaction->balance, 2, '.', '');
                $manualTransactions->push($transaction);
                $allTransactions->forget($key);
            } else {
                $autoTransaction->push($transaction);
                $recentAccrualDate = $transaction->accrual_date;
                $allTransactions->forget($key);
            }
        }
        return $initDate = [
            'manualTransactions' => $manualTransactions,
            'autoTransaction' => $autoTransaction
        ];
    }
}
