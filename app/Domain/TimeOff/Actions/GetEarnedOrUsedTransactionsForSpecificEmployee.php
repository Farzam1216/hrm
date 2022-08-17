<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class GetEarnedOrUsedTransactionsForSpecificEmployee
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($id)
    {
        $history = TimeOffTransaction::where('employee_id', $id)->with(['assignTimeOff', 'assignTimeOff.timeOffType', 'assignTimeOff.employee'])
            ->orderBy('accrual_date', 'asc')->get();
        return response()->json($history);
    }
}
