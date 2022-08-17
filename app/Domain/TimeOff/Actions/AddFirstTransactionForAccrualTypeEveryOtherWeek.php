<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransactionForAccrualTypeEveryOtherWeek
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
     * @return mixed
     */
    public function execute($data, $request, $policy, $levelData, $currentDate, &$balance, &$recentAutoTransaction, $type)
    {
        $accrualStartDate = Carbon::parse($levelData['levelStart']);
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualDay = $LevelDataDecoded['accrual-day'];
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $accrualWeekNumber['accrualWeek'] = $LevelDataDecoded['accrual-week']; //either 0 or 1
        $accrualWeekNumber['accrualStartDateWeekNumber'] = ($accrualStartDate->weekOfYear) % 2; //0= even number of week else Odd
        $nextAccuralDateForEveryOtherWeek = new CalculateNextAccrualDateforAccrualTypeEveryOtherWeek();
        $nextAccuralDate = $nextAccuralDateForEveryOtherWeek->execute($accrualWeekNumber, $accrualStartDate, $accrualDay);
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        $firstAccrual = $policy['first_accrual'];
        if ($currentDate->gte($accrualStartDate)) {
            if ($policy->accrual_happen == "At the end of period") {
                if ($currentDate == $nextAccuralDate) {
                    if ($firstAccrual != "Prorate") {
                        goto FullAmount;
                    }
                    if ($accrualStartDate->englishDayOfWeek == $accrualDay && $accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek']) {
                        FullAmount: $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    } else {
                        $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                        $remainingDays = 1 + ($accrualStartDate->diff($accrualEndDate)->days);
                        $accrualForOneDay = $accrualHours / 14;
                        $amount = $accrualForOneDay * $remainingDays;
                        $balance = $balance + $amount;
                        $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                        return true;
                    }
                }
            } else {
                if ($currentDate == $nextAccuralDate) {
                    if ($firstAccrual != "Prorate") {
                        goto Prorate;
                    }
                    if ($accrualStartDate->englishDayOfWeek == $accrualDay && $accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek']) {
                        Prorate: $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    } else {
                        $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                        $remainingDays = 1 + ($accrualStartDate->diff($accrualEndDate)->days);
                        $accrualForOneDay = $accrualHours / 14;
                        $amount = $accrualForOneDay * $remainingDays;
                        $balance = $balance + $amount;
                        $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                        return true;
                    }
                }
            }
        }
    }
}
