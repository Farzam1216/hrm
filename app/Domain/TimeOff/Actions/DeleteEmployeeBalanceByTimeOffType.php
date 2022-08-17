<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;

class DeleteEmployeeBalanceByTimeOffType
{
    /**
     * @param $assignTimeOffType
     * @return bool
     */
    public function execute($assignTimeOffType)
    {
        TimeOffTransaction::where('assign_time_off_id', $assignTimeOffType->id)->where('action', '!=', 'used')->delete();
        $nonePolicy = new AssignPolicyNoneByTimeOffType();
        $nonePolicy->execute($assignTimeOffType);
        return true;
    }
}
