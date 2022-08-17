<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\TimeOffTransaction;

class GetBalanceByTimeOffType
{
    public function execute($policyStartDate, $assignTimeOffType, $policyId, $userId)
    {
//        $transactionsAfterNewPolicyDate = TimeOffTransaction::where('assign_time_off_id', $assignTimeOffType->id)
//            ->where('accrual_date', '>=', $policyStartDate)->get();
//        $transactionsToDelete = $transactionsAfterNewPolicyDate->pluck('id');
//        TimeOffTransaction::whereIn('id', $transactionsToDelete)->delete();
        //update Policy
        AssignTimeOffType::where('id', $assignTimeOffType->id)->update([
            'employee_id' => $userId,
            'accrual_option' => 'policy',
            'attached_policy_id' => $policyId
        ]);
        return $balance = TimeOffTransaction::where('accrual_date', '<', $policyStartDate->toDateString())
            ->where('assign_time_off_id', $assignTimeOffType->id)
            ->sum('hours_accrued');
    }
}
