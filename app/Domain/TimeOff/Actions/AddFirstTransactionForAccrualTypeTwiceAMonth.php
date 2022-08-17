<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddFirstTransactionForAccrualTypeTwiceAMonth
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
        $accrualFirstDate = $LevelDataDecoded['accrual-firstdate'];
        $accrualLastDate = $LevelDataDecoded['accrual-lastdate'];
        $accrualHours = $LevelDataDecoded['accrual-hours'];
        $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $currentDate->month . '-' . $accrualFirstDate . '-' . $currentDate->year);
        $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $currentDate->month . '-' . $accrualLastDate . '-' . $currentDate->year);
        $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
        if ($interval['first']['end']->lt($interval['first']['start'])) {
            $interval['first']['end'] = $interval['first']['end']->addMonth(1);
        }
        $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
        if ($interval['second']['end']->lt($interval['second']['start'])) {
            $interval['second']['end'] = $interval['second']['end']->addMonth(1);
        }
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        $getInterval =new GetIntervalByCurrentDate();
        $accrualInterval = $getInterval->execute($interval, $levelStartDate);
        if ($currentDate->gte($levelStartDate)) {
            if ($policy['first_accrual'] == "Prorate") {
                if ($levelStartDate != $levelStartDate) {
                    $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addMonth(-1)->startOfDay();
                    $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addMonth(-1)->startOfDay();
                    $diff = 1 + ($interval[$accrualInterval['opposite']]['start']->diffInDays($interval[$accrualInterval['opposite']]['end']));
                    $accrualForOneDay = $LevelDataDecoded["accrual-hours"] / $diff;
                    $accrualDiff = 1 + ($levelStartDate->diffInDays($interval[$accrualInterval['last']]['end']));
                    $amount = $accrualForOneDay * $accrualDiff;
                    $amount = number_format($amount, 2, '.', '');
                    $currentInterval = 'opposite';
                } else {
                    $diff = 1 + ($interval[$accrualInterval['current']]['start']->diffInDays($interval[$accrualInterval['current']]['end']));
                    $accrualForOneDay = $LevelDataDecoded["accrual-hours"] / $diff;
                    $accrualDiff = 1 + ($levelStartDate->diffInDays($interval[$accrualInterval['current']]['end']));
                    $amount = $accrualForOneDay * $accrualDiff;
                    $amount = number_format($amount, 2, '.', '');
                    $currentInterval = 'current';
                }
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $interval[$accrualInterval[$currentInterval]]['end']->toDateString();
                        $balance = $balance + $amount;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                        return true;
                    }
                } else {
                    if ($currentDate->startOfDay() == $levelStartDate) {
                        $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $interval[$accrualInterval[$currentInterval]]['end']->toDateString();
                        $balance = $balance + $amount;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $amount, $currentDate);
                        return true;
                    }
                }
            } //Full Amount
            else {
                if ($levelStartDate != $levelStartDate) {
                    $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addMonth(-1)->startOfDay();
                    $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addMonth(-1)->startOfDay();
                    $currentInterval = 'opposite';
                } else {
                    $currentInterval = 'current';
                }
                if ($policy->accrual_happen == "At the end of period") {
                    $currentDate = $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay();
                    if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $interval[$accrualInterval[$currentInterval]]['end']->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    }
                } else {
                    $currentDate = $levelStartDate;
                    if ($currentDate->startOfDay() == $levelStartDate) {
                        $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $interval[$accrualInterval[$currentInterval]]['end']->toDateString();
                        $balance = $balance + $accrualHours;
                        $createTrasanction = new CreateTransactionForTimeOff();
                        $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                        return true;
                    }
                }
            }
        }
//        $transaction = $autoTransaction->where('accrual_date', $currentDate->toDateString());
//        if ($transaction->isNotEmpty()) {
//            $this->addAutoTransaction($data, $transaction, $balance, $recentAutoTransaction);
//        }
    }
}
