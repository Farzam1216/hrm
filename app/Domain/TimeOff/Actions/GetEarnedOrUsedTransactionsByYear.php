<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class GetEarnedOrUsedTransactionsByYear
{
    /**
     * @param $getyear
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($getyear, $id)
    {
        $history = TimeOffTransaction::with('assignTimeOff', 'assignTimeOff.timeOffType')
            ->whereHas('assignTimeOff.employee', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->where('accrual_date', 'like', $getyear . '%')
            ->orderBy('accrual_date', 'asc')->get();
        return response()->json($history);
    }
}
