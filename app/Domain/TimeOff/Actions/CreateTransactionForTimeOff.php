<?php


namespace App\Domain\TimeOff\Actions;

class CreateTransactionForTimeOff
{
    /**
     * @param $data
     * @param $action
     * @param $balance
     * @param $accrualHours
     * @param $currentDate
     * @return mixed
     */
    public function execute($data, $action, $balance, $accrualHours, $currentDate)
    {
        $balance = number_format($balance, 2, '.', '');
        $accrualHours = number_format($accrualHours, 2, '.', '');
        $next['action'] = $action;
        $next['balance'] = $balance;
        $next['hours_accrued'] = $accrualHours;
        $next['accrual_date'] = $currentDate->copy()->toDateString();
        $data->push($next);
        $endDate = new GetAccrualEndDate();
        return $endDate->execute($action);
    }
}
