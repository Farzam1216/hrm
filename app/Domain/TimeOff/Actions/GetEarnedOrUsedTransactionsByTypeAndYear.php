<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class GetEarnedOrUsedTransactionsByTypeAndYear
{
    /**
     * @param $typeId
     * @param $getyear
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($typeId, $getyear, $id)
    {
        $history = TimeOffTransaction::where('employee_id', $id)->with([
            'assignTimeOff',
            'assignTimeOff.timeOffType' => function ($query) use ($typeId) {$query->where('id', $typeId);},
            'assignTimeOff.employee' => function ($query) use ($id) {$query->where('id', $id);}
        ])
        ->where('accrual_date', 'like', $getyear . '%')
        ->orderBy('accrual_date', 'asc')->get();
        return response()->json($history);
    }
}
