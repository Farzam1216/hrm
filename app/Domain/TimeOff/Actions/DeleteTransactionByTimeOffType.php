<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class DeleteTransactionByTimeOffType
{
    /**
     * @param $policyStartDate
     * @param $assignTimeOffType
     * @param $policyId
     * @param $userId
     * @return mixed
     */
    public function execute($policyStartDate, $assignTimeOffType)
    {
        $transactionsAfterNewPolicyDate = TimeOffTransaction::where('assign_time_off_id', $assignTimeOffType->id)
            ->where('accrual_date', '>=', $policyStartDate)->get();
        $transactionsToDelete = $transactionsAfterNewPolicyDate->pluck('id');
        TimeOffTransaction::whereIn('id', $transactionsToDelete)->delete();
    }
}
