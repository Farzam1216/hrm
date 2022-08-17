<?php


namespace App\Domain\TimeOff\Actions;

class GetBalanceByMaxAccrual
{
    /**
     * @param $balance
     * @param $accrualAmount
     * @param $maxAccrual
     * @return string
     */
    public function execute($balance, $accrualAmount, $maxAccrual)
    {
        $nextBalance = $balance + $accrualAmount;
        if ($nextBalance >= $maxAccrual && $maxAccrual != null) {
            $accrualHours = $maxAccrual - $balance;
            return number_format($accrualHours, 2, '.', '');
        } else {
            $accrualHours = $accrualAmount;
            return number_format($accrualHours, 2, '.', '');
        }
    }
}
