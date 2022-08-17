<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class AddRunTimeTransactionForAccrualTypeTwiceAYear
{
    /**
     * @param $data
     * @param $balance
     * @param $levelData
     * @param $policy
     * @param $currentDate
     * @param $recentAutoTransaction
     * @param $type
     * @return bool
     */
    public function execute($data, &$balance, $levelData, $policy, $currentDate, &$recentAutoTransaction, $type)
    {
        $LevelDataDecoded = json_decode($levelData['level']->amount_accrued, true);
        $accrualFirstDate = $LevelDataDecoded["accrual-firstdate"];
        $accrualFirstMonth = $LevelDataDecoded["accrual-firstMonth"];
        $accrualSecondDate = $LevelDataDecoded["accrual-seconddate"];
        $accrualSecondMonth = $LevelDataDecoded["accrual-secondMonth"];
        $accrualHours = $LevelDataDecoded["accrual-hours"];


        $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $accrualFirstMonth . '-' . $accrualFirstDate . '-' . $recentAutoTransaction->year);
        $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $accrualSecondMonth . '-' . $accrualSecondDate . '-' . $recentAutoTransaction->year);

        $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
        if ($interval['first']['end']->lt($interval['first']['start'])) {
            $interval['first']['end'] = $interval['first']['end']->addYear(1);
        }

        $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
        if ($interval['second']['end']->lt($interval['second']['start'])) {
            $interval['second']['end'] = $interval['second']['end']->addYear(1);
        }
        $tempDate = $recentAutoTransaction->copy();
        $getInterval =new GetIntervalByCurrentDate();
        $accrualInterval = $getInterval->execute($interval, $tempDate);
        $getBalance = new GetBalanceByMaxAccrual();
        $accrualHours = $getBalance->execute($balance, $accrualHours, $levelData['level']->max_accrual);
        if ($policy->accrual_happen == "At the end of period") {
            if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                if ($currentDate->startOfDay()->gt($interval[$accrualInterval['opposite']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['opposite']]['end']->copy()->addDays(1)->startOfDay()) {
                    $action = "Accrued Amount " . $interval[$accrualInterval['opposite']]['start']->toDateString() . " to " . $interval[$accrualInterval['opposite']]['end']->toDateString();
                    $balance =  $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                    return true;
                }
            } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                if ($currentDate->startOfDay()->gt($interval[$accrualInterval['current']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['current']]['end']->copy()->addDays(1)->startOfDay()) {
                    $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                    $balance =  $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                    return true;
                }
            }
        } else {
            if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                if ($currentDate->startOfDay() == $interval[$accrualInterval['opposite']]['start']->startOfDay()) {
                    $action = "Accrued Amount " . $interval[$accrualInterval['opposite']]['start']->toDateString() . " to " . $interval[$accrualInterval['opposite']]['end']->toDateString();
                    $balance =  $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                    return true;
                }
            } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                if ($currentDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                    $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                    $balance = $balance + $accrualHours;
                    $createTrasanction = new CreateTransactionForTimeOff();
                    $recentAutoTransaction = $createTrasanction->execute($data, $action, $balance, $accrualHours, $currentDate);
                    return true;
                }
            }
        }
    }
}
