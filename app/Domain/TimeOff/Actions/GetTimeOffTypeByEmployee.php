<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use Carbon\Carbon;

class GetTimeOffTypeByEmployee
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute($userId)
    {
        return AssignTimeOffType::with('timeofftransaction')->with([
            'timeofftransaction' => function ($query) {
                $query->where('accrual_date', '<=', Carbon::now()->toDateString());
            }
        ])->where('employee_id', $userId)->get();
    }
}
