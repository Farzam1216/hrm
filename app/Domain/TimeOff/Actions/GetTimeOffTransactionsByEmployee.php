<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\TimeOffTransaction;
use Carbon\Carbon;

class GetTimeOffTransactionsByEmployee
{
    /**
     * @param $userId
     * @return array
     */
    public function execute($userId)
    {
        $assignTimeOff = AssignTimeOffType::with('timeofftransaction')->with([
            'timeofftransaction' => function ($query) {
                $query->where('accrual_date', '<=', Carbon::now()->toDateString());
            }
        ])->where('employee_id', $userId)->get();
        $timeOffTransactions = [];
        foreach ($assignTimeOff as $type) {
            $timeOffTransactions[0][$type->id] = TimeOffTransaction::with(
                'assignTimeOff',
                'assignTimeOff.employee'
            )->whereHas('assignTimeOff', function ($query) use ($userId) {
                $query->where('employee_id', $userId);
            })->where('assign_time_off_id', $type->id)->where(
                'accrual_date',
                '<=',
                Carbon::now()->toDateString()
            )->orderBy('accrual_date', 'asc')->get();
        }
        return $timeOffTransactions;
    }
}
