<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\TimeOffTransaction;
use Carbon\Carbon;

class GetUsedTransactionsByEmployee
{
    /**
     * @param $userId
     * @return array
     */
    public function execute($userId)
    {
        $getUsedTransaction = AssignTimeOffType::where('employee_id', $userId)->with([
            'timeofftransaction' => function ($query) {
                $query->where('accrual_date', '<=', Carbon::now()->toDateString());
            }
        ])->get();
        $usedbalance = [];
        foreach ($getUsedTransaction as $usedTransaction) {
            $usedbalance[$usedTransaction->id] = TimeOffTransaction::where('employee_id', $userId)->where('action', 'Used')
            ->with([
                'assignTimeOff' => function ($query) use ($userId) {
                    $query->where('employee_id', $userId)->where('accrual_option', 'None');
                },
                'assignTimeOff.employee'
            ])->get();
        }
        return $usedbalance;
    }
}
