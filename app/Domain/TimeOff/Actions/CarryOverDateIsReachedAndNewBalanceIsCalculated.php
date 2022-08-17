<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class CarryOverDateIsReachedAndNewBalanceIsCalculated
{
    /**
     * @param $policy
     * @param $data
     * @param $levelRecord
     * @param $currentDate
     * @param $balance
     * @param $type
     * @param $joining_date
     * @return string
     */
    public function execute($policy, $data, $levelRecord, $currentDate, $balance, $type, $joining_date)
    {
        $getCarryOverDate = new GetCarryOverDate();
        $carryOverDate = $getCarryOverDate->execute($policy['carry_over_date'], $joining_date);
        $carryOverDate = Carbon::createFromFormat('d-m', $carryOverDate)->startOfDay();
        $carryOverDate->year = $currentDate->year;

        if ($carryOverDate != $currentDate) { //Guard Clause
            return $balance;
        } elseif ($carryOverDate == $currentDate) {
            if ($balance >= $levelRecord['carry_over_amount']) {
                $next['hours_accrued'] = $hours_Accrued = number_format($levelRecord['carry_over_amount'] - $balance, 2, '.', '');
                $balance = $next['balance'] = number_format($levelRecord['carry_over_amount'], 2, '.', '');
                $next['action'] = "loss of " . $hours_Accrued . " hours due to year to year carryover limit.";
                $next['accrual_date'] = $currentDate->copy()->toDateString();
                $data->push($next);
                return $balance;
            }
        }

        return $balance;
    }
}
