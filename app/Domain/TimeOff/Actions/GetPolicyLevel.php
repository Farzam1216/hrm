<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class GetPolicyLevel
{
    /**
     * @param $type
     * @param $TotalLevels
     * @param $employeeHireDate
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     * @param $request
     * @return mixed
     */
    public function execute(
        $type,
        $TotalLevels,
        $employeeHireDate,
        $policy,
        $currentDate,
        &$recentAutoTransaction,
        $request
    ) {
        if (isset($request['policy_start_date'])) {
            $employeeHireDate = $request['policy_start_date'];
        }

        //$currentDate=Carbon::now()->startOfDay();
        // $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $getCarryOverDate = new GetCarryOverDate();
        $completeLevel['carryOverDate'] = $getCarryOverDate->execute($policy->carry_over_date, $employeeHireDate);
        $lastTransaction = $recentAutoTransaction;
        //if DB has atleast one record of PTO transaction.
        if (isset($lastTransaction)) {
            $lastAccrualDate = $recentAutoTransaction;
            $currentLevelCount = 0;
            $nextLevelCount = 1;
            while ($nextLevelCount <= $TotalLevels) {
                $currentLevel = $policy->level[$currentLevelCount];
                $currentLevelDate = Carbon::parse($employeeHireDate)->format('Y-m-d ') . $currentLevel['level_start_status'];
                if ($nextLevelCount == $TotalLevels) {
                    $levelDetails = new GetCompleteLevelDetails();
                    $completeLevel = $levelDetails->execute(
                        $policy->level[$currentLevelCount],
                        $currentLevelDate,
                        null,
                        $employeeHireDate
                    );
                    return $completeLevel;
                } else {
                    $nextLevel = $policy->level[$nextLevelCount];
                    /*Using format in Carbon Parse is returning string not object
                     because of which we cannot use carbon lt & gt methods */

                    $nextLevelDate = Carbon::parse($currentLevelDate)->format('Y-m-d ')->addDays(1) . $nextLevel['level_start_status'];
                    if ($currentLevelDate->lte($nextLevelDate) && $nextLevelDate->gte($lastAccrualDate)) {
                        $levelDetails = new GetCompleteLevelDetails();
                        $completeLevel = $levelDetails->execute(
                            $policy->level[$currentLevelCount],
                            $currentLevelDate,
                            $nextLevelDate,
                            $employeeHireDate
                        );
                        if ($currentDate->eq($nextLevelDate)) {
                            $recentAutoTransaction = null;
                        }
                        return $completeLevel;
                    }
                }
                $currentLevelCount++;
                $nextLevelCount++;
            }
        } else {
            $LevelOne = $policy->level[0];
            $levelTwoDate = "";
            if (isset($policy->level[1])) {
                $levelTwo = $policy->level[1];
                $levelTwoDate = Carbon::parse($employeeHireDate)->format('Y-m-d ') . $levelTwo['level_start_status'];
            }
            if (isset($request['policy_start_date'])) {
                $levelOneDate = Carbon::parse($employeeHireDate)->format('Y-m-d ');
                if (isset($policy->level[1])) {
                    $levelTwo = $policy->level[1];
                    $levelTwoDate = Carbon::parse($employeeHireDate)->format('Y-m-d ') . $levelTwo['level_start_status'];
                }
            } else {
                $levelOneDate = Carbon::parse($employeeHireDate)->format('Y-m-d ') . $LevelOne['level_start_status'];
            }
            $levelDetails = new GetCompleteLevelDetails();
            $completeLevel = $levelDetails->execute($LevelOne, $levelOneDate, $levelTwoDate, $employeeHireDate);
            return $completeLevel;
        }
    }
}
