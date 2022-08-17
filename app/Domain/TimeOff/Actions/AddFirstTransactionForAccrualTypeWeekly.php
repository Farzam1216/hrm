<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransactionForAccrualTypeWeekly
{
    /**
     * @param $data
     * @param $request
     * @param $policy
     * @param $levelData
     * @param $currentDate
     * @param $balance
     * @param $recentAutoTransaction
     * @param $type
     * @return bool
     */
    public function execute($data, $request, $policy, $levelData, $currentDate, &$balance, &$recentAutoTransaction, $type)
    {
        $accrualStartDate = Carbon::parse($levelData['levelStart']);
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualDay = $LevelDataDecoded['first-accrual-day'];
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $nextAccuralDate = Carbon::parse("Next $accrualDay" . $accrualStartDate);
        $prevAccuralDate = Carbon::parse("Last $accrualDay" . $accrualStartDate);
        $getbalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getbalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($currentDate->gte($accrualStartDate)) {
            //First Accrual is Prorate
            if (($policy['first_accrual'] == "Prorate")) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralDate) {
                        if ($accrualStartDate->englishDayOfWeek == $accrualDay) {
                            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                            $balance = $balance + $accrualHours;
                            $createTrasanction = new CreateTransactionForTimeOff();
                            $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                            return true;
                        } else {
                            $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                            $remainingDays = 1 + ($accrualStartDate->diff($accrualEndDate)->days);
                            $accrualForOneDay = $LevelDataDecoded["accrual-hours"] / 7;
                            $amount = $accrualForOneDay * $remainingDays;
                            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                            $balance = $balance + $amount;
                            $createTrasanction = new CreateTransactionForTimeOff();
                            $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                            return true;
                        }
                    }
                } else {
                    if ($currentDate == $accrualStartDate) {
                        if ($accrualStartDate->englishDayOfWeek == $accrualDay) {
                            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                            $balance = $balance + $accrualHours;
                            $recentAutoTransaction = $this->createTransaction($data, $action, $balance, $accrualHours, $currentDate);
                            return true;
                        } else {
                            $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                            $remainingDays = 1 + ($accrualStartDate->diff($accrualEndDate)->days);
                            $accrualForOneDay = $LevelDataDecoded["accrual-hours"] / 7;
                            $amount = $accrualForOneDay * $remainingDays;
                            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                            $balance = $balance + $amount;
                            $createTrasanction = new CreateTransactionForTimeOff();
                            $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                            return true;
                        }
                    }
                }
                //First Accrual is Full Amount
            } else {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralDate) {
                        $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    }
                } else {
                    if ($currentDate == $accrualStartDate) {
                        $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    }
                }
            }
        }
    }
}
