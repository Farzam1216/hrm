<?php


namespace App\Domain\TimeOff\Actions;

class RecalculateTransactionsIfPolicyChanged
{
    /**
     * @param $data
     * @param $request
     * @param $isChagnedPolicy
     * @param $manualTransactions
     * @param $autoTransaction
     * @param $joining_date
     * @return int
     */
    public function execute($data, $request, $isChagnedPolicy, $manualTransactions, $autoTransaction, $joining_date)
    {
        $sum = 0;
        $accrualDate = $joining_date->toDateString();
        $copyOfJoiningDate = $joining_date->copy();
        $autoAccrualTillDate = collect();
        if ($isChagnedPolicy) {
            while ($copyOfJoiningDate->lt($request['policy_start_date'])) {
                $transactions = $autoTransaction->where('accrual_date', $copyOfJoiningDate->toDateString());
                foreach ($transactions as $key => $transaction) {
                    $hoursAccrued = $transaction->hours_accrued;
                    $sum = $sum + $hoursAccrued;
                    $autoTransaction->forget($key);
                }
                $currentTransactions = $manualTransactions->where('accrual_date', $copyOfJoiningDate->toDateString());
                foreach ($currentTransactions as $key => $transaction) {
                    $hoursAccrued = $transaction->hours_accrued;
                    $sum = $sum + $hoursAccrued;
                    $manualTransactions->forget($key);
                }
                $copyOfJoiningDate = $copyOfJoiningDate->addDays(1)->startOfDay();
            }
            $accrualDate =  $request['policy_start_date'];
        }
        $next['accrual_date'] = $accrualDate;
        $next['action'] = "Enrolled in new policy";
        $next['balance'] = $sum;
        $next['hours_accrued'] = "0";
        $data->push($next);
        return $sum;
    }
}
