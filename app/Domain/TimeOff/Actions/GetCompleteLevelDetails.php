<?php


namespace App\Domain\TimeOff\Actions;

class GetCompleteLevelDetails
{
    /**
     * @param $currentLevel
     * @param $levelStartDate
     * @param $levelEndDate
     * @param $employeeHireDate
     * @return mixed
     */
    public function execute($currentLevel, $levelStartDate, $levelEndDate, $employeeHireDate)
    {
        $completeLevel['level'] = $currentLevel;
        $completeLevel['levelStart'] = $levelStartDate;
        $completeLevel['levelEnd'] = $levelEndDate;
        $completeLevel['hireDate'] = $employeeHireDate;
        return $completeLevel;
    }
}
