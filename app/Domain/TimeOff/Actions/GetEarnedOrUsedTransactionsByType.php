<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class GetEarnedOrUsedTransactionsByType
{
    /**
     * @param $typeId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($typeId, $id)
    {
        $history = TimeOffTransaction::where('employee_id', $id)->with([
            'assignTimeOff',
            'assignTimeOff.timeOffType' => function ($query) use ($typeId) {$query->where('id', $typeId);},
            'assignTimeOff.employee' => function ($query) use ($id) {$query->where('id', $id);}
        ])->orderBy('accrual_date', 'asc')->get();
        return response()->json($history);
    }
}
