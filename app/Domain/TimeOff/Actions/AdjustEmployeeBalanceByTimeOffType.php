<?php


namespace App\Domain\TimeOff\Actions;

class AdjustEmployeeBalanceByTimeOffType
{
    /**
     * @param $assignTimeOffType
     * @return bool
     */
    public function execute($assignTimeOffType)
    {
        $manualPolicy = new AssignPolicyManualByTimeOffType();
        $manualPolicy->execute($assignTimeOffType);
        return true;
    }
}
