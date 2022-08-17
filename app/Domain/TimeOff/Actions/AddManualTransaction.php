<?php


namespace App\Domain\TimeOff\Actions;

class AddManualTransaction
{
    /**
     * @param $data
     * @param $manualTransaction
     * @param $balance
     * @return string
     */
    public function execute($data, $manualTransaction, &$balance)
    {
        foreach ($manualTransaction as $transaction) {
            $hoursAccrued = number_format($transaction->hours_accrued, 2, '.', '');
            $balance = number_format($balance, 2, '.', '') + $hoursAccrued;
            $transaction->hours_accrued = $hoursAccrued;
            $transaction->balance = $balance;
            $data->push($transaction);
        }
        return $balance;
    }
}
