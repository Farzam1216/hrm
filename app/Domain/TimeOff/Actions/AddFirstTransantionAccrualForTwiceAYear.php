<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransantionAccrualForTwiceAYear
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
    public function execute(
        $data,
        $request,
        $policy,
        $levelData,
        $currentDate,
        &$balance,
        &$recentAutoTransaction,
        $type
    ) {
        $accrualStartDate = Carbon::parse($levelData['levelStart']);
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualFirstDate = $LevelDataDecoded["accrual-firstdate"];
        $accrualFirstMonth = $LevelDataDecoded["accrual-firstMonth"];
        $accrualSecondDate = $LevelDataDecoded["accrual-seconddate"];
        $accrualSecondMonth = $LevelDataDecoded["accrual-secondMonth"];
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $interval['first']['start'] = Carbon::createFromFormat(
            'n-j-Y',
            $accrualFirstMonth . '-' . $accrualFirstDate . '-' . $currentDate->year
        );
        $interval['second']['start'] = Carbon::createFromFormat(
            'n-j-Y',
            $accrualSecondMonth . '-' . $accrualSecondDate . '-' . $currentDate->year
        );
        $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
        if ($interval['first']['end']->lt($interval['first']['start'])) {
            $interval['first']['end'] = $interval['first']['end']->addYear(1);
        }
        $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
        if ($interval['second']['end']->lt($interval['second']['start'])) {
            $interval['second']['end'] = $interval['second']['end']->addYear(1);
        }
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        $getInterval = new GetIntervalByCurrentDate();
        $accrualInterval = $getInterval->execute($interval, $accrualStartDate);
        if ($currentDate->gte($accrualStartDate)) {
            if ($policy['first_accrual'] == "Prorate") {
                if ($accrualStartDate != $levelData['levelStart']) {
                    $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addYear(-1)->startOfDay();
                    $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addYear(-1)->startOfDay();
                    $diff = 1 + ($interval[$accrualInterval['opposite']]['start']->diffInDays($interval[$accrualInterval['opposite']]['end']));
                    $accrualForOneDay = $accrualHours / $diff;
                    $accrualDiff = 1 + (Carbon::parse($levelData['levelStart'])->diffInDays($interval[$accrualInterval['opposite']]['end']));
                    $amount = $accrualForOneDay * $accrualDiff;
                    $amount = number_format($amount, 2, '.', '');
                    $currentInterval = 'opposite';
                } else {
                    $diff = 1 + ($interval[$accrualInterval['current']]['start']->diffInDays($interval[$accrualInterval['current']]['end']));
                    $accrualForOneDay = $accrualHours / $diff;
                    $accrualDiff = 1 + ($accrualStartDate->diffInDays($interval[$accrualInterval['current']]['end']));
                    $amount = $accrualForOneDay * $accrualDiff;
                    $amount = number_format($amount, 2, '.', '');
                    $currentInterval = 'current';
                }
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $amount;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute(
                            $data,
                            $action,
                            $balance,
                            $amount,
                            $currentDate
                        );
                        return true;
                    }
                } else {
                    if ($currentDate->startOfDay() == Carbon::parse($levelData['levelStart'])) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $amount;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute(
                            $data,
                            $action,
                            $balance,
                            $amount,
                            $currentDate
                        );
                        return true;
                    }
                }
            } //Full Amount
            else {
                if ($accrualStartDate != $levelData['levelStart']) {
                    $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addYear(-1)->startOfDay();
                    $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addYear(-1)->startOfDay();
                    $currentInterval = 'opposite';
                } else {
                    $currentInterval = 'current';
                }
                if ($policy->accrual_happen == "At the end of period") {
                    $currentDate = $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay();
                    if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute(
                            $data,
                            $action,
                            $balance,
                            $accrualHours,
                            $currentDate
                        );
                        return true;
                    }
                } else {
                    $currentDate = Carbon::parse($levelData['levelStart']);
                    if ($currentDate->startOfDay() == Carbon::parse($levelData['levelStart'])) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute(
                            $data,
                            $action,
                            $balance,
                            $accrualHours,
                            $currentDate
                        );
                        return true;
                    }
                }
            }
        }
    }
}
