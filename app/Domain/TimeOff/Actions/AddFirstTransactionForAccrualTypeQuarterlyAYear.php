<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransactionForAccrualTypeQuarterlyAYear
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
        $levelStartDate = Carbon::parse($levelData['levelStart']);
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualFirstDate = $LevelDataDecoded["accrual-firstdate"];
        $accrualFirstMonth = $LevelDataDecoded["accrual-firstMonth"];
        $accrualSecondDate = $LevelDataDecoded["accrual-seconddate"];
        $accrualSecondMonth = $LevelDataDecoded["accrual-secondMonth"];
        $accrualThirdDate = $LevelDataDecoded["accrual-thirddate"];
        $accrualThirdMonth = $LevelDataDecoded["accrual-thirdMonth"];
        $accrualFourthDate = $LevelDataDecoded["accrual-fourthdate"];
        $accrualFourthMonth = $LevelDataDecoded["accrual-fourthMonth"];
        // dd($accrualSecondMonth);
        $accrualHours = $LevelDataDecoded["accrual-hours"];
        $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $accrualFirstMonth . '-' . $accrualFirstDate . '-' . $currentDate->year)->startOfDay();
        $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $accrualSecondMonth . '-' . $accrualSecondDate . '-' . $currentDate->year)->startOfDay();
        $interval['third']['start'] = Carbon::createFromFormat('n-j-Y', $accrualThirdMonth . '-' . $accrualThirdDate . '-' . $currentDate->year)->startOfDay();
        $interval['fourth']['start'] = Carbon::createFromFormat('n-j-Y', $accrualFourthMonth . '-' . $accrualFourthDate . '-' . $currentDate->year)->startOfDay();
        $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
        if ($interval['first']['end']->lt($interval['first']['start'])) {
            $interval['first']['end'] = $interval['first']['end']->addYear(1);
        }
        $interval['second']['end'] = $interval['third']['start']->copy()->addDays(-1);
        if ($interval['second']['end']->lt($interval['second']['start'])) {
            $interval['second']['end'] = $interval['second']['end']->addYear(1);
        }
        $interval['third']['end'] = $interval['fourth']['start']->copy()->addDays(-1);
        if ($interval['third']['end']->lt($interval['third']['start'])) {
            $interval['third']['end'] = $interval['third']['end']->addYear(1);
        }
        $interval['fourth']['end'] = $interval['first']['start']->copy()->addDays(-1);
        if ($interval['fourth']['end']->lt($interval['fourth']['start'])) {
            $interval['fourth']['end'] = $interval['fourth']['end']->addYear(1);
        }
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        $getQuaterlyInterval=new GetIntervalOfAccrualTypeQuaterlyAYear();
        $accrualInterval = $getQuaterlyInterval->execute($interval, $levelStartDate);
        if ($currentDate->gte($levelStartDate)) {
            if ($policy['first_accrual'] == "Prorate") {
                if ($levelStartDate != $levelData['levelStart']) {
                    $interval[$accrualInterval['last']]['start'] = $interval[$accrualInterval['last']]['start']->addYears(-1)->startOfDay();
                    $interval[$accrualInterval['last']]['end'] = $interval[$accrualInterval['last']]['end']->addYears(-1)->startOfDay();
                    $diff = 1 + ($interval[$accrualInterval['last']]['start']->diffInDays($interval[$accrualInterval['last']]['end']));
                    $accrualForOneDay = $accrualHours / $diff;
                    $accrualDiff = 1 + (Carbon::parse($levelData['levelStart'])->diffInDays($interval[$accrualInterval['last']]['end']));
                    $amount = $accrualForOneDay * $accrualDiff;
                    $amount = number_format($amount, 2, '.', '');
                    $currentInterval = 'last';
                } else {
                    $diff = 1 + ($interval[$accrualInterval['current']]['start']->diffInDays($interval[$accrualInterval['current']]['end']));
                    $accrualForOneDay = $accrualHours / $diff;
                    $accrualDiff = 1 + ($levelStartDate->diffInDays($interval[$accrualInterval['current']]['end']));
                    $amount = $accrualForOneDay * $accrualDiff;
                    $amount = number_format($amount, 2, '.', '');
                    $currentInterval = 'current';
                }
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $balance + number_format($amount, 2, '.', '');
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                        return true;
                    }
                } else {
                    if ($currentDate->startOfDay() == Carbon::parse($levelData['levelStart'])) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $balance + number_format($amount, 2, '.', '');
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                        return true;
                    }
                }
            } else {
                if ($levelStartDate != $levelData['levelStart']) {
                    $interval[$accrualInterval['last']]['start'] = $interval[$accrualInterval['last']]['start']->addYears(-1)->startOfDay();
                    $interval[$accrualInterval['last']]['end'] = $interval[$accrualInterval['last']]['end']->addYears(-1)->startOfDay();
                    $currentInterval = 'last';
                } else {
                    $currentInterval = 'current';
                }
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    }
                } else {
                    if ($currentDate->startOfDay() == Carbon::parse($levelData['levelStart'])) {
                        $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end']->format('Y-m-d');
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
