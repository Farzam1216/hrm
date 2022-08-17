<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransactionForAccrualTypeAnniversary
{
    /**
     * @param $data
     * @param $policy
     * @param $levelData
     * @param $currentDate
     * @param $balance
     * @param $recentAutoTransaction
     * @param $joining_date
     * @param $type
     * @return bool
     */
    public function execute($data, $policy, $levelData, $currentDate, &$balance, &$recentAutoTransaction, $joining_date, $type)
    {
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $accrualDate = $joining_date->copy()->day;
        $accrualMonth = $joining_date->copy()->month;
        $levelStartDate = Carbon::parse($levelData['levelStart']);
        $currentAccrualDate = Carbon::createFromFormat('n-j-Y', $accrualMonth . '-' . $accrualDate . '-' . $levelStartDate->year)->startOfDay();
        $nextAccuralDate = $currentAccrualDate->copy()->addYears(1)->startOfDay();
        $prevAccuralDate = $currentAccrualDate->copy()->addYears(-1)->startOfDay();
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($currentDate->gte($levelStartDate)) {
            //First Accrual is Prorate
            if (($policy['first_accrual'] == "Prorate")) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralDate) {
                        if ($levelStartDate == $currentAccrualDate) {
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                            $balance = $balance + $accrualHours;
                            $createTrasanction = new CreateTransactionForTimeOff();
                            $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                            return true;
                        } else {
                            $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                            $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                            $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                            $accrualForOneDay = $accrualHours / $TotalDays;
                            $amount = $accrualForOneDay * $remainingDays;
                            $balance = $balance + $amount;
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                            $createTrasanction = new CreateTransactionForTimeOff();
                            $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                            return true;
                        }
                    }
                } else {
                    if ($currentDate == $levelStartDate) {
                        if ($levelStartDate == $currentAccrualDate) {
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                            $balance = $balance + $accrualHours;
                            $createTrasanction = new CreateTransactionForTimeOff();
                            $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                            return true;
                        } else {
                            $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                            $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                            $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                            $accrualForOneDay = $accrualHours / $TotalDays;
                            $amount = $accrualForOneDay * $remainingDays;
                            $balance = $balance + $amount;
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
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
                        $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    }
                } else {
                    if ($currentDate == $levelStartDate) {
                        $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
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
