<?php

namespace App\Console\Commands;

use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class PTOTransaction
 *
 * @package App\Console\Commands
 */
class PTOTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pto:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job to Add PTO Accruals Periodically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    //REMEMBER: Carbon custom date and while loop is for testing purpose only, by default cronjob
    // will run for actual current date (Carbon::now()) only.
    public function handle()
    {
        \Log::info("Cron is working fine!");
        //get all employees
        $employees = Employee::all();
        // $currentDate=Carbon::parse('2018-05-02')->startOfDay();
        $currentDate = Carbon::now()->startOfDay();
        // while ($currentDate->startOfDay() != Carbon::now()->addYears(1)->startOfDay()) {
        foreach ($employees as $employee) {
            $types = $this->getTimeOffTypesOfEmployee($employee->id);
            if (isset($types)) {
                $this->loopThroughEmployeeTimeOffTypes($types, $employee, $currentDate);
            }
        }
        //  $currentDate=$currentDate->addDays(1)->startOfDay();
        //}
        $this->info('pto:cron Cummand Run successfully!');
    }

    /**
     * @param $employeeID
     * @return mixed
     */
    public function getTimeOffTypesOfEmployee($employeeID)
    {
        return AssignTimeOffType::where('employee_id', $employeeID)->where('accrual_option', 'policy')->get();
    }

    /**
     * @param $timeOffTypes
     * @param $employee
     */
    public function loopThroughEmployeeTimeOffTypes($timeOffTypes, $employee, $currentDate)
    {
        foreach ($timeOffTypes as $type) {
            $policy = $this->getPolicyAttachedWithType($type->attached_policy_id);
            if ($this->policyHasLevel($policy) == true) {
                //Count number of levels
                $numberOfLevels = $policy->level->count();
                $employeeHireDate = Carbon::parse($employee->joining_date)->startOfDay();
                $levelData = $this->getLevel($type, $numberOfLevels, $employeeHireDate, $policy, $currentDate);
                // $currentDate=Carbon::now()->startOfDay();
                $accrualDetails = json_decode($levelData['level']->amount_accrued, true);
                $accrualType = $this->getAccrualType($accrualDetails["accrual-type"]);
                // if ($accrualType == '')
                //variable method call
                if ($accrualType != 'Perhourworked') {
                    $calculateAccrualToCreateTransaction = 'accrualType' . $accrualType;
                    $this->$calculateAccrualToCreateTransaction($type, $policy, $levelData, $currentDate, $employee);
                }
            }
        }
    }

    /**
     * @param $attached_policy
     * @return mixed
     */
    public function getPolicyAttachedWithType($attached_policy)
    {
        return Policy::where('id', $attached_policy)->with('level')->first();
    }

    /**
     * @param $policy
     * @return bool
     */
    public function policyHasLevel($policy)
    {
        if (isset($policy->level[0])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $accrual_type
     * @return string|string[]|null
     */
    public function getAccrualType($accrual_type)
    {
        //remove white-spaces within the accrual type
        return preg_replace("/\s+/", "", $accrual_type);
    }

    public function getPreviousTransactions($typeID, $currentDate)
    {
        // $currentDate=Carbon::now()->startOfDay();
        return TimeOffTransaction::where('assign_time_off_id', $typeID)->where('accrual_date', '<=', $currentDate)
            ->orderBy('accrual_date', 'desc')
            ->get();
    }

    /**
     * @param $carry_over_date
     * @param $employeeHireDate
     * @return string
     */
    public function getCarryOverDate($carry_over_date, $employeeHireDate)
    {
        if ($carry_over_date != 'none') { //none means carry_over_amount is unlimited. Get carryover date in this if statement
            if ($carry_over_date == 'employee_hire_date') {
                return Carbon::parse($employeeHireDate)->format('d-m');
            } else {
                return Carbon::parse($carry_over_date)->format('d-m');
            }
        }
    }

    /**
     * @param $type
     * @param $TotalLevels
     * @param $employeeHireDate
     * @param $policy
     * @return mixed
     */
    public function getLevel($type, $TotalLevels, $employeeHireDate, $policy, $currentDate)
    {
        //$currentDate=Carbon::now()->startOfDay();
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $completeLevel['carryOverDate'] = $this->getCarryOverDate($policy->carry_over_date, $employeeHireDate);
        $lastTransaction = $previousTransactions->first();
        //if DB has atleast one record of PTO transaction.
        if (isset($lastTransaction)) {
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $currentLevelCount = 0;
            $nextLevelCount = 1;
            while ($nextLevelCount <= $TotalLevels) {
                $currentLevel = $policy->level[$currentLevelCount];
                $currentLevelDate = Carbon::parse($employeeHireDate . $currentLevel['level_start_status']);
                if ($nextLevelCount == $TotalLevels) {
                    $completeLevel = $this->getCompleteLevelDetails($policy->level[$currentLevelCount], $currentLevelDate, null, $employeeHireDate);
                    return $completeLevel;
                } else {
                    $nextLevel = $policy->level[$nextLevelCount];
                    /*Using format in Carbon Parse is returning string not object
                     because of which we cannot use carbon lt & gt methods */
                    $nextLevelDate = Carbon::parse($employeeHireDate . $nextLevel['level_start_status']);
                    if ($currentLevelDate->lte($lastAccrualDate) && $nextLevelDate->gte($lastAccrualDate)) {
                        $completeLevel = $this->getCompleteLevelDetails($policy->level[$currentLevelCount], $currentLevelDate, $nextLevelDate, $employeeHireDate);
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
                $levelTwoDate = Carbon::parse($employeeHireDate)->addDays($levelTwo['level_start_status']);
            }
            $levelOneDate = Carbon::parse($employeeHireDate)->addDays($LevelOne['level_start_status']);
            $completeLevel = $this->getCompleteLevelDetails($LevelOne, $levelOneDate, $levelTwoDate, $employeeHireDate);
            return $completeLevel;
        }
    }

    /**
     * @param $currentLevel
     * @param $levelStartDate
     * @param $levelEndDate
     * @param $employeeHireDate
     * @return mixed
     */
    public function getCompleteLevelDetails($currentLevel, $levelStartDate, $levelEndDate, $employeeHireDate)
    {
        $completeLevel['level'] = $currentLevel;
        $completeLevel['levelStart'] = $levelStartDate;
        $completeLevel['levelEnd'] = $levelEndDate;
        $completeLevel['hireDate'] = $employeeHireDate;
        return $completeLevel;
    }

    /**
     * @param $TransactionDesc
     * @return string
     */
    public function getAccrualEndDate($TransactionDesc)
    {
        $pattern = "/\d{4}\-\d{2}\-\d{2}/";

        preg_match_all($pattern, $TransactionDesc, $matches);
        if (isset($matches[0][1])) {
            return Carbon::parse($matches[0][1]);
        } elseif (isset($matches[0][0])) {
            return Carbon::parse($matches[0][0]);
        } else {
            return null;
        }
    }

    /**
     * @param $previousTransactions
     */

    public function CalculateRemainingBalance($previousTransactions)
    {
        $balance = $previousTransactions->first()->balance;
        $count = 0;
        $latestTransaction[$count] = $previousTransactions->first();
        //-1 balance means pending calculations
        while ($balance == -1) {
            $count++;
            $latestTransaction[$count] = $previousTransactions->slice($count)->first();
            $balance = $latestTransaction[$count]->balance;
        }
        //Calculate Balance for all pending transactions.
        while ($count != 0) {
            // current count balance + -one count accrued hours and save in -one balance
            $prev = $count;
            $prev--;
            $latestTransaction[$prev]->balance = $latestTransaction[$count]->balance + $latestTransaction[$prev]->hours_accrued;
            $latestTransaction[$prev]->save();
            $count--;
        }
        return;
    }

    /**
     * @param $levelData
     * @param $currentDate
     * @param $previousTransaction
     * @param $type
     * @return bool|void
     */
    public function carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransaction, $type, $employee)
    {
        $carryOverDate = Carbon::createFromFormat('d-m', $levelData['carryOverDate'])->startOfDay();
        if ($carryOverDate != $currentDate) { //Guard Clause
            return false;
        } elseif ($carryOverDate == $currentDate) {
            if ($previousTransaction->first()->balance >= $levelData['level']->carry_over_amount) {
                $hours_Accrued = $levelData['level']->carry_over_amount - $previousTransaction->first()->balance;
                $balance = $levelData['level']->carry_over_amount;
                $action = "loss of " . $hours_Accrued . " hours due to year to year carryover limit.";
                $this->createTransaction($type, $action, $balance, $hours_Accrued, $currentDate, $employee);
                return true;
            }
        }
    }

    /**
     * @param $balance
     * @param $accrualAmount
     * @param $maxAccrual
     * @return mixed
     */
    public function checkMaxAccrual($balance, $accrualAmount, $maxAccrual)
    {
        $nextBalance = $balance + $accrualAmount;
        if ($nextBalance >= $maxAccrual && $maxAccrual != null) {
            $accrualHours = $maxAccrual - $balance;
            return $accrualHours;
        } else {
            $accrualHours = $accrualAmount;
            return $accrualHours;
        }
    }

    /**
     * @param $previousTransactions
     * @return string
     */

    public function getLastAutoAccrualEndDate($previousTransactions)
    {
        //Only auto accruals' action contain a date, manual adjustment or Request Time Off has no dates in action field
        if(isset($previousTransactions->first()->action)) {
            $lastAccrualDate = $this->getAccrualEndDate($previousTransactions->first()->action);
            $count = 1;
            if (isset($previousTransactions->slice($count)->first()->action)) {
                while ($lastAccrualDate == null && isset($previousTransactions->slice($count)->first()->action)) {
                    $lastAccrualDate = $this->getAccrualEndDate($previousTransactions->slice($count)->first()->action);
                    $count++;
                }
            }
            return $lastAccrualDate;
        }
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */
    public function accrualTypeDaily($type, $policy, $levelData, $currentDate, $employee)
    {
        // $currentDate=Carbon::parse('05-12-2019')->startOfDay(); //For testing purpose only
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $levelStartDate = $levelData['levelStart']->copy();
        if ($previousTransactions->first() == null && $currentDate->gte($levelStartDate)) {
            if ($policy->accrual_happen == "At the end of period") {
                $this->accrualTypeDailyAtEnd($levelStartDate, $currentDate, $type->id, $amountAccrued["accrual-hours"], $employee);
            } else {
                if ($currentDate == $levelStartDate->startOfDay()) {
                    $action = "Accrual for " . $levelStartDate->toDateString();
                    $balance = $amountAccrued["accrual-hours"];
                    $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate, $employee);
                }
            }
        } else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualDate)) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $lastAccrualDate->copy()->addDays(2)->startOfDay()) {
                        $action = "Accrual for " . $lastAccrualDate->copy()->addDays(1)->toDateString();
                        $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                    }
                } else {
                    if ($currentDate == $lastAccrualDate->copy()->addDays(1)->startOfDay()) {
                        $action = "Accrual for " . $currentDate->toDateString();
                        $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                    }
                }
            }
        }
    }

    /**
     * @param $levelStartDate
     * @param $currentDate
     * @param $type
     * @param $amountAccrued
     */
    public function accrualTypeDailyAtEnd($levelStartDate, $currentDate, $type, $amountAccrued, $employee)
    {
        if ($currentDate == $levelStartDate->copy()->addDays(1)->startOfDay()) {
            $action = "Accrual for " . $currentDate->toDateString() . " to " . $levelStartDate->toDateString();
            $balance = $amountAccrued["accrual-hours"];
            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate, $employee);
        }
    }

    /**
     * @param $nextAccuralDate
     * @param $accrualStartDate
     * @param $accrualDay
     * @param $accrualWeekNumber
     * @param $type
     * @param $accrualhours
     */
    public function calculateFirstAccrualForEveryOtherWeekType($firstAccrual, $nextAccuralDate, $accrualStartDate, $accrualDay, $accrualWeekNumber, $type, $accrualhours, $currentDate, $employee)
    {
        //$currentDate=Carbon::now()->startOfDay();
        if ($firstAccrual != "Prorate") {
            goto FullAmount;
        }
        if ($accrualStartDate->englishDayOfWeek == $accrualDay && $accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek']) {
            FullAmount:
            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
            $balance = $accrualhours;
            $this->createTransaction($type, $action, $balance, $accrualhours, $currentDate->toDateString(), $employee);
        } else {
            $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
            $remainingDays = 1 + ($accrualStartDate->diff($accrualEndDate)->days);
            $accrualForOneDay = $accrualhours / 14;
            $amount = $accrualForOneDay * $remainingDays;
            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
            $this->createTransaction($type, $action, $amount, $amount, $currentDate->toDateString(), $employee);
        }
    }

    /**
     * @param $accrualStartDate
     * @param $accrualDay
     * @param $nextAccuralDate
     * @param $type
     * @param $accrualHours
     */
    public function firstProrateAccrualForWeeklyType($accrualStartDate, $accrualDay, $nextAccuralDate, $type, $accrualHours, $employee)
    {
        $currentDate = Carbon::now()->startOfDay();
        if ($accrualStartDate->englishDayOfWeek == $accrualDay) {
            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
            $balance = $accrualHours;
            $this->createTransaction($type, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
        } else {
            $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
            $remainingDays = 1 + ($accrualStartDate->diff($accrualEndDate)->days);
            $accrualForOneDay = $accrualHours / 7;
            $amount = $accrualForOneDay * $remainingDays;
            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
            $this->createTransaction($type, $action, $amount, $amount, $currentDate->toDateString(), $employee);
        }
    }

    /**
     * @param $accrualWeekNumber
     * @param $accrualStartDate
     * @param $accrualDay
     * @return Carbon
     */
    public function calculateNextAccrualDateforEveryOtherWeekType($accrualWeekNumber, $accrualStartDate, $accrualDay)
    {
        if ($accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek'] && $accrualStartDate->dayOfWeek >= Carbon::parse($accrualDay)->dayOfWeek) {
            $currentAccrualDate = Carbon::parse("this $accrualDay" . $accrualStartDate);
            $nextAccuralDate = Carbon::parse("second $accrualDay next week" . $accrualStartDate);
        } elseif ($accrualWeekNumber['accrualStartDateWeekNumber'] == $accrualWeekNumber['accrualWeek'] && $accrualStartDate->dayOfWeek < Carbon::parse($accrualDay)->dayOfWeek) {
            $nextAccuralDate = Carbon::parse("next $accrualDay" . $accrualStartDate);
            $currentAccrualDate = Carbon::parse("last week $accrualDay last week" . $accrualStartDate);
        } else {
            $currentAccrualDate = Carbon::parse("last week $accrualDay" . $accrualStartDate);
            $nextAccuralDate = Carbon::parse("next week $accrualDay" . $accrualStartDate);
        }
        return $nextAccuralDate;
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */
    public function accrualTypeEveryOtherWeek($type, $policy, $levelData, $currentDate, $employee)
    {
        //TODO:: Loop for testing purpsose.
        /* $currentDate=Carbon::parse('2019-02-02')->startOfDay();
         while ($currentDate->startOfDay() != Carbon::now()->addYears(3)->startOfDay()) { */
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualDay = $amountAccrued['accrual-day'];
        $accrualWeekNumber['accrualWeek'] = $amountAccrued['accrual-week']; //either 0 or 1
        if ($previousTransactions->first() == null) {
            $accrualStartDate = $levelData['levelStart']->copy();
            $accrualWeekNumber['accrualStartDateWeekNumber'] = ($accrualStartDate->weekOfYear) % 2; //0= even number of week else Odd
            $nextAccuralDate = $this->calculateNextAccrualDateforEveryOtherWeekType($accrualWeekNumber, $accrualStartDate, $accrualDay);

            if ($currentDate->gte($accrualStartDate)) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralDate) {
                        $this->calculateFirstAccrualForEveryOtherWeekType($policy['first_accrual'], $nextAccuralDate, $accrualStartDate, $accrualDay, $accrualWeekNumber, $type->id, $amountAccrued["accrual-hours"], $currentDate, $employee);
                    }
                } else {
                    if ($currentDate == $accrualStartDate) {
                        $this->calculateFirstAccrualForEveryOtherWeekType($policy['first_accrual'], $nextAccuralDate, $accrualStartDate, $accrualDay, $accrualWeekNumber, $type->id, $amountAccrued["accrual-hours"], $currentDate, $employee);
                    }
                }
            }
        } //Data exists in table
        else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            if ($lastAccrualDate) {
                $nextAccuralEndDate = $lastAccrualDate->copy()->addWeek(2)->addDays(1);
                $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
                if ($currentDate->gte($lastAccrualDate)) {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralEndDate && $currentDate->englishDayOfWeek == $accrualDay) {
                            $action = "Accrued Amount " . $lastAccrualDate->copy()->addDays(1)->toDateString() . " to " . $nextAccuralEndDate->copy()->addDays(-1)->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                        }
                    } else {
                        if ($currentDate == $lastAccrualDate->copy()->addDays(1) && $currentDate->englishDayOfWeek == $accrualDay) {
                            $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $nextAccuralEndDate->copy()->addDays(-1)->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                        }
                    }
                }
            }
        }
        /* $currentDate=$currentDate->addDays(1)->startOfDay(); //end of while loop
        }*/
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */
    public function accrualTypeWeekly($type, $policy, $levelData, $currentDate, $employee)
    {
        //$currentDate=Carbon::now()->startOfDay();
        /* $currentDate=Carbon::parse('2019-10-27')->startOfDay();
         while ($currentDate->startOfDay() != Carbon::now()->addYears(3)->startOfDay()) {*/
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualDay = $amountAccrued['first-accrual-day'];
        if ($previousTransactions->first() == null) {
            $accrualStartDate = $levelData['levelStart']->copy();
            $nextAccuralDate = Carbon::parse("Next $accrualDay" . $accrualStartDate);
            $prevAccuralDate = Carbon::parse("Last $accrualDay" . $accrualStartDate);
            if ($currentDate->gte($accrualStartDate)) {
                //First Accrual is Prorate
                if (($policy['first_accrual'] == "Prorate")) {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralDate) {
                            $this->firstProrateAccrualForWeeklyType($accrualStartDate, $accrualDay, $nextAccuralDate, $type->id, $amountAccrued["accrual-hours"], $employee);
                        }
                    } else {
                        if ($currentDate == $accrualStartDate) {
                            $this->firstProrateAccrualForWeeklyType($accrualStartDate, $accrualDay, $nextAccuralDate, $type->id, $amountAccrued["accrual-hours"], $employee);
                        }
                    }
                    //First Accrual is Full Amount
                } else {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralDate) {
                            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                            $balance = $amountAccrued["accrual-hours"];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                        }
                    } else {
                        if ($currentDate == $accrualStartDate) {
                            $action = "Accrued Amount " . $accrualStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                            $balance = $amountAccrued["accrual-hours"];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                        }
                    }
                }
            }
        } else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $nextAccuralEndDate = $lastAccrualDate->copy()->addWeek(1)->addDays(1);
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualDate)) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralEndDate && $currentDate->englishDayOfWeek == $accrualDay) {
                        $action = "Accrued Amount " . $lastAccrualDate->copy()->addDays(1)->toDateString() . " to " . $lastAccrualDate->copy()->addWeek(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                } else {
                    if ($currentDate == $lastAccrualDate->copy()->addDays(1) && $currentDate->englishDayOfWeek == $accrualDay) {
                        $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $lastAccrualDate->copy()->addWeek(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                }
            }
        }
        /*$currentDate=$currentDate->addDays(1)->startOfDay();
    }*/
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */
    public function accrualTypeMonthly($type, $policy, $levelData, $currentDate, $employee)
    {
        /* $currentDate=Carbon::parse('2022-31-01')->startOfDay();
         while ($currentDate->startOfDay() != Carbon::now()->addYears(3)->startOfDay()) {*/
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualDate = $amountAccrued['accrual-firstdatemonthly'];
        if ($previousTransactions->first() == null) {
            $levelStartDate = $levelData['levelStart']->copy();
            $currentAccrualDate = Carbon::createFromFormat('n-j-Y', $levelStartDate->month . '-' . $amountAccrued['accrual-firstdatemonthly'] . '-' . $levelStartDate->year);
            $nextAccuralDate = $currentAccrualDate->copy()->addMonthsNoOverflow(1)->startOfDay();
            $prevAccuralDate = $currentAccrualDate->copy()->addMonthsNoOverflow(-1)->startOfDay();
            if ($currentDate->gte($levelStartDate)) {
                //First Accrual is Prorate
                if (($policy['first_accrual'] == "Prorate")) {
                    $this->accrualMonthlyProrate($policy, $currentDate, $nextAccuralDate, $levelStartDate, $currentAccrualDate, $type->id, $amountAccrued["accrual-hours"], $employee);
                //First Accrual is Full Amount
                } else {
                    $this->accrualMonthlyFullAmount($policy, $currentDate, $nextAccuralDate, $levelStartDate, $type->id, $amountAccrued["accrual-hours"], $employee);
                }
            }
        } else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $nextAccuralEndDate = $lastAccrualDate->copy()->addMonthsNoOverflow(1)->addDays(1);
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualDate)) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralEndDate) {
                        $action = "Accrued Amount " . $lastAccrualDate->copy()->addDays(1)->toDateString() . " to " . $lastAccrualDate->copy()->addMonthsNoOverflow(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                } else {
                    if ($currentDate == $lastAccrualDate->copy()->addDays(1)) {
                        $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $lastAccrualDate->copy()->addMonthsNoOverflow(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                }
            }
        }
        /*  $currentDate=$currentDate->addDays(1)->startOfDay();
        }*/
    }

    /**
     * @param $policy
     * @param $currentDate
     * @param $nextAccuralDate
     * @param $levelStartDate
     * @param $currentAccrualDate
     * @param $type
     * @param $amountAccrued
     */
    public function accrualMonthlyProrate($policy, $currentDate, $nextAccuralDate, $levelStartDate, $currentAccrualDate, $type, $amountAccrued, $employee)
    {
        if ($policy->accrual_happen == "At the end of period") {
            if ($currentDate == $nextAccuralDate) {
                $this->accrualMonthlyAtEnd($levelStartDate, $currentAccrualDate, $type, $amountAccrued);
                if ($levelStartDate == $currentAccrualDate) {
                    $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                    $balance = $amountAccrued;
                    $this->createTransaction($type, $action, $balance, $amountAccrued, $currentDate->toDateString(), $employee);
                } else {
                    $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                    $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                    $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                    $accrualForOneDay = $amountAccrued / $TotalDays;
                    $amount = $accrualForOneDay * $remainingDays;
                    $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                    $this->createTransaction($type, $action, $amount, $amount, $currentDate->toDateString(), $employee);
                }
            }
        } else {
            if ($currentDate == $levelStartDate) {
                if ($levelStartDate == $currentAccrualDate) {
                    $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                    $balance = $amountAccrued;
                    $this->createTransaction($type, $action, $balance, $amountAccrued, $currentDate->toDateString(), $employee);
                } else {
                    $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                    $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                    $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                    $accrualForOneDay = $amountAccrued / $TotalDays;
                    $amount = $accrualForOneDay * $remainingDays;
                    $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                    $this->createTransaction($type, $action, $amount, $amount, $currentDate->toDateString(), $employee);
                }
            }
        }
    }

    /**
     * @param $policy
     * @param $currentDate
     * @param $nextAccuralDate
     * @param $levelStartDate
     * @param $type
     * @param $amountAccrued
     */
    public function accrualMonthlyFullAmount($policy, $currentDate, $nextAccuralDate, $levelStartDate, $type, $amountAccrued, $employee)
    {
        if ($policy->accrual_happen == "At the end of period") {
            if ($currentDate == $nextAccuralDate) {
                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                $balance = $amountAccrued["accrual-hours"];
                $this->createTransaction($type, $action, $balance, $amountAccrued, $currentDate->toDateString(), $employee);
            }
        } else {
            if ($currentDate == $levelStartDate) {
                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                $balance = $amountAccrued["accrual-hours"];
                $this->createTransaction($type, $action, $balance, $amountAccrued, $currentDate->toDateString(), $employee);
            }
        }
    }
    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */
    public function accrualTypeTwiceAMonth($type, $policy, $levelData, $currentDate, $employee)
    {
        //$currentDate=Carbon::now()->startOfDay();
        //un-comment the loop for testing
        /*  $currentDate=Carbon::parse('2019-08-19')->startOfDay();
          while ($currentDate->startOfDay() != Carbon::now()->addYears(2)->startOfDay()) {*/
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $currentDate->month . '-' . $amountAccrued['accrual-firstdate'] . '-' . $currentDate->year);
        $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $currentDate->month . '-' . $amountAccrued['accrual-lastdate'] . '-' . $currentDate->year);
        $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
        if ($interval['first']['end']->lt($interval['first']['start'])) {
            $interval['first']['end'] = $interval['first']['end']->addMonth(1);
        }
        $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
        if ($interval['second']['end']->lt($interval['second']['start'])) {
            $interval['second']['end'] = $interval['second']['end']->addMonth(1);
        }
        if ($previousTransactions->first() == null) {
            $accrualStartDate = $levelData['levelStart']->copy();
            if ($currentDate->gte($accrualStartDate) && $currentDate->month == $accrualStartDate->month) {
                $accrualInterval = $this->getInterval($interval, $accrualStartDate);
                if ($policy['first_accrual'] == "Prorate") {
                    if ($accrualStartDate != $levelData['levelStart']) {
                        $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addMonth(-1)->startOfDay();
                        $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addMonth(-1)->startOfDay();
                        $diff = 1 + ($interval[$accrualInterval['opposite']]['start']->diffInDays($interval[$accrualInterval['opposite']]['end']));
                        $accrualForOneDay = $amountAccrued["accrual-hours"] / $diff;
                        if (isset($accrualInterval['last'])) {
                            $accrualDiff = 1 + ($levelData['levelStart']->diffInDays($interval[$accrualInterval['last']]['end']));
                            $amount = $accrualForOneDay * $accrualDiff;dd($accrualDiff);
                        }
                        $currentInterval = 'opposite';
                    } else {
                        $diff = 1 + ($interval[$accrualInterval['current']]['start']->diffInDays($interval[$accrualInterval['current']]['end']));
                        $accrualForOneDay = $amountAccrued["accrual-hours"] / $diff;
                        $accrualDiff = 1 + ($accrualStartDate->diffInDays($interval[$accrualInterval['current']]['end']));
                        $amount = $accrualForOneDay * $accrualDiff;
                        $currentInterval = 'current';
                    }
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amount;
                            $this->createTransaction($type->id, $action, $balance, $amount, $currentDate, $employee);
                        }
                    } else {
                        if ($currentDate->startOfDay() == $levelData['levelStart']) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amount;
                            $this->createTransaction($type->id, $action, $balance, $amount, $levelData['levelStart'], $employee);
                        }
                    }
                } //Full Amount
                else {
                    if ($accrualStartDate != $levelData['levelStart']) {
                        $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addMonth(-1)->startOfDay();
                        $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addMonth(-1)->startOfDay();
                        $currentInterval = 'opposite';
                    } else {
                        $currentInterval = 'current';
                    }
                    if ($policy->accrual_happen == "At the end of period") {
                        $currentDate = $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay();
                        if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amountAccrued['accrual-hours'];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued['accrual-hours'], $currentDate, $employee);
                        }
                    } else {
                        $currentDate = $levelData['levelStart'];
                        if ($currentDate->startOfDay() == $levelData['levelStart']) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amountAccrued['accrual-hours'];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued['accrual-hours'], $levelData['levelStart'], $employee);
                        }
                    }
                }
            } else {
            }
        } //Data exists in Table
        else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualEndDate = $this->getAccrualEndDate($previousTransactions->first()->action);
            $count = 1;
            while ($lastAccrualEndDate == null) {
                $lastAccrualEndDate = $this->getAccrualEndDate($previousTransactions->slice($count)->first()->action);
                $count++;
            }
            $tempDate = $lastAccrualEndDate->copy();
            /* Calculate Intervals */
            $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $lastAccrualEndDate->month . '-' . $amountAccrued['accrual-firstdate'] . '-' . $lastAccrualEndDate->year);
            $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $lastAccrualEndDate->month . '-' . $amountAccrued['accrual-lastdate'] . '-' . $lastAccrualEndDate->year);
            $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
            if ($interval['first']['end']->lt($interval['first']['start'])) {
                $interval['first']['end'] = $interval['first']['end']->addMonth(1);
            }
            $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
            if ($interval['second']['end']->lt($interval['second']['start'])) {
                $interval['second']['end'] = $interval['second']['end']->addMonth(1);
            }
            //Max Accrual Check
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualEndDate)) {
                $accrualInterval = $this->getInterval($interval, $tempDate);
                if ($policy->accrual_happen == "At the end of period") {
                    if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                        if ($currentDate->startOfDay()->gt($interval[$accrualInterval['opposite']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['opposite']]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['opposite']]['start']->toDateString() . " to " . $interval[$accrualInterval['opposite']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                        if ($currentDate->startOfDay()->gt($interval[$accrualInterval['current']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['current']]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    }
                } else {
                    if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval['opposite']]['start']->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['opposite']]['start']->toDateString() . " to " . $interval[$accrualInterval['opposite']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        } else {
                        }
                    } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        } else {
                        }
                    }
                }
            }
        }
        /* $currentDate=$currentDate->addDays(1)->startOfDay();
        }*/
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */
    public function accrualTypeTwiceAYearly($type, $policy, $levelData, $currentDate, $employee)
    {
        //$currentDate=Carbon::now()->startOfDay();
        //un-comment loop for testing
        /*  $currentDate=Carbon::parse('2019-02-03')->startOfDay();
           while ($currentDate->startOfDay() != Carbon::now()->addYears(2)->startOfDay()) {*/
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualFirstDate = $amountAccrued["accrual-firstdate"];
        $accrualFirstMonth = $amountAccrued["accrual-firstMonth"];
        $accrualSecondDate = $amountAccrued["accrual-seconddate"];
        $accrualSecondMonth = $amountAccrued["accrual-secondMonth"];
        $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $accrualFirstMonth . '-' . $accrualFirstDate . '-' . $currentDate->year);
        $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $accrualSecondMonth . '-' . $accrualSecondDate . '-' . $currentDate->year);
        $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
        if ($interval['first']['end']->lt($interval['first']['start'])) {
            $interval['first']['end'] = $interval['first']['end']->addYear(1);
        }
        $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
        if ($interval['second']['end']->lt($interval['second']['start'])) {
            $interval['second']['end'] = $interval['second']['end']->addYear(1);
        }
        if ($previousTransactions->first() == null) {
            $accrualStartDate = $levelData['levelStart']->copy();
            if ($currentDate->gte($accrualStartDate)) {
                $accrualInterval = $this->getInterval($interval, $accrualStartDate);
                if ($policy['first_accrual'] == "Prorate") {
                    if ($accrualStartDate != $levelData['levelStart']) {
                        $interval[$accrualInterval['opposite']]['start'] = $interval[$accrualInterval['opposite']]['start']->addYear(-1)->startOfDay();
                        $interval[$accrualInterval['opposite']]['end'] = $interval[$accrualInterval['opposite']]['end']->addYear(-1)->startOfDay();
                        $diff = 1 + ($interval[$accrualInterval['opposite']]['start']->diffInDays($interval[$accrualInterval['opposite']]['end']));
                        $accrualForOneDay = $amountAccrued["accrual-hours"] / $diff;
                        $accrualDiff = 1 + ($levelData['levelStart']->diffInDays($interval[$accrualInterval['opposite']]['end']));
                        $amount = $accrualForOneDay * $accrualDiff;
                        $currentInterval = 'opposite';
                    } else {
                        $diff = 1 + ($interval[$accrualInterval['current']]['start']->diffInDays($interval[$accrualInterval['current']]['end']));
                        $accrualForOneDay = $amountAccrued["accrual-hours"] / $diff;
                        $accrualDiff = 1 + ($accrualStartDate->diffInDays($interval[$accrualInterval['current']]['end']));
                        $amount = $accrualForOneDay * $accrualDiff;
                        $currentInterval = 'current';
                    }
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amount;
                            $this->createTransaction($type->id, $action, $balance, $amount, $currentDate, $employee);
                        }
                    } else {
                        if ($currentDate->startOfDay() == $levelData['levelStart']) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amount;
                            $this->createTransaction($type->id, $action, $balance, $amount, $levelData['levelStart'], $employee);
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
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amountAccrued['accrual-hours'];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued['accrual-hours'], $currentDate, $employee);
                        }
                    } else {
                        $currentDate = $levelData['levelStart'];
                        if ($currentDate->startOfDay() == $levelData['levelStart']) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amountAccrued['accrual-hours'];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued['accrual-hours'], $levelData['levelStart'], $employee);
                        }
                    }
                }
            }
        } //Data Exists in Table
        else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualEndDate = $this->getAccrualEndDate($previousTransactions->first()->action);
            $count = 1;
            while ($lastAccrualEndDate == null) {
                $lastAccrualEndDate = $this->getAccrualEndDate($previousTransactions->slice($count)->first()->action);
                $count++;
            }
            $tempDate = $lastAccrualEndDate->copy();
            /* Calculate Intervals */
            $interval['first']['start'] = Carbon::createFromFormat('n-j-Y', $accrualFirstMonth . '-' . $accrualFirstDate . '-' . $lastAccrualEndDate->year);
            $interval['second']['start'] = Carbon::createFromFormat('n-j-Y', $accrualSecondMonth . '-' . $accrualSecondDate . '-' . $lastAccrualEndDate->year);
            $interval['first']['end'] = $interval['second']['start']->copy()->addDays(-1);
            if ($interval['first']['end']->lt($interval['first']['start'])) {
                $interval['first']['end'] = $interval['first']['end']->addYears(1);
            }
            $interval['second']['end'] = $interval['first']['start']->copy()->addDays(-1);
            if ($interval['second']['end']->lt($interval['second']['start'])) {
                $interval['second']['end'] = $interval['second']['end']->addYears(1);
            }
            //Max Accrual Check
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualEndDate)) {
                $accrualInterval = $this->getInterval($interval, $tempDate);
                if ($policy->accrual_happen == "At the end of period") {
                    if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                        if ($currentDate->startOfDay()->gt($interval[$accrualInterval['opposite']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['opposite']]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['opposite']]['start']->toDateString() . " to " . $interval[$accrualInterval['opposite']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                        if ($currentDate->startOfDay()->gt($interval[$accrualInterval['current']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['current']]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    }
                } else {
                    if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval['opposite']]['start']->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['opposite']]['start']->toDateString() . " to " . $interval[$accrualInterval['opposite']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        } else {
                        }
                    } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    }
                }
            }
        }
        /*  $currentDate=$currentDate->addDays(1)->startOfDay();
         }*/
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */

    public function accrualTypeYearly($type, $policy, $levelData, $currentDate, $employee)
    {
        // $currentDate=Carbon::parse('2019-02-02')->startOfDay();
        //while ($currentDate->startOfDay() != Carbon::now()->addYears(3)->startOfDay()) {
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualDate = $amountAccrued['accrual-firstdate'];
        $accrualMonth = $amountAccrued['accrual-firstMonth'];
        if ($previousTransactions->first() == null) {
            $levelStartDate = $levelData['levelStart']->copy();
            $currentAccrualDate = Carbon::createFromFormat('n-j-Y', $amountAccrued['accrual-firstMonth'] . '-' . $amountAccrued['accrual-firstdate'] . '-' . $levelStartDate->year);
            $nextAccuralDate = $currentAccrualDate->copy()->addYears(1)->startOfDay();
            $prevAccuralDate = $currentAccrualDate->copy()->addYears(-1)->startOfDay();
            if ($currentDate->gte($levelStartDate)) {
                //First Accrual is Prorate
                if (($policy['first_accrual'] == "Prorate")) {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralDate) {
                            if ($levelStartDate == $currentAccrualDate) {
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                                $balance = $amountAccrued["accrual-hours"];
                                $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                            } else {
                                $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                                $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                                $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                                $accrualForOneDay = $amountAccrued["accrual-hours"] / $TotalDays;
                                $amount = $accrualForOneDay * $remainingDays;
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                                $this->createTransaction($type->id, $action, $amount, $amount, $currentDate->toDateString(), $employee);
                            }
                        }
                    } else {
                        if ($currentDate == $levelStartDate) {
                            if ($levelStartDate == $currentAccrualDate) {
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                                $balance = $amountAccrued["accrual-hours"];
                                $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                            } else {
                                $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                                $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                                $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                                $accrualForOneDay = $amountAccrued["accrual-hours"] / $TotalDays;
                                $amount = $accrualForOneDay * $remainingDays;
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                                $this->createTransaction($type->id, $action, $amount, $amount, $currentDate->toDateString(), $employee);
                            }
                        }
                    }
                    //First Accrual is Full Amount
                } else {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralDate) {
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                            $balance = $amountAccrued["accrual-hours"];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                        }
                    } else {
                        if ($currentDate == $levelStartDate) {
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                            $balance = $amountAccrued["accrual-hours"];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                        }
                    }
                }
            }
        } else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransaction = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $nextAccuralEndDate = $lastAccrualDate->copy()->addYears(1)->addDays(1);
            //Max Accrual Check
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualDate)) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralEndDate) {
                        $action = "Accrued Amount " . $lastAccrualDate->copy()->addDays(1)->toDateString() . " to " . $lastAccrualDate->copy()->addYears(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                } else {
                    if ($currentDate == $lastAccrualDate->copy()->addDays(1)) {
                        $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $lastAccrualDate->copy()->addYears(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                }
            }
        }
        //   $currentDate=$currentDate->addDays(1)->startOfDay();
        // }
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $hireDate
     * @param $currentDate
     */
    public function accrualTypeAnniversary($type, $policy, $levelData, $currentDate, $employee)
    {
        // $currentDate=Carbon::parse('2018-02-02')->startOfDay(); //For testing purpose
        // while ($currentDate->startOfDay() != Carbon::now()->addYears(3)->startOfDay()) {
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualDate = Carbon::parse($employee->joining_date)->day;
        $accrualMonth = Carbon::parse($employee->joining_date)->month;
        if ($previousTransactions->first() == null) {
            $levelStartDate = $levelData['levelStart']->copy();
            $currentAccrualDate = Carbon::createFromFormat('n-j-Y', $accrualMonth . '-' . $accrualDate . '-' . $levelStartDate->year)->startOfDay();
            $nextAccuralDate = $currentAccrualDate->copy()->addYears(1)->startOfDay();
            $prevAccuralDate = $currentAccrualDate->copy()->addYears(-1)->startOfDay();
            if ($currentDate->gte($levelStartDate)) {
                //First Accrual is Prorate
                if (($policy['first_accrual'] == "Prorate")) {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralDate) {
                            if ($levelStartDate == $currentAccrualDate) {
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                                $balance = $amountAccrued["accrual-hours"];
                                $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                            } else {
                                $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                                $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                                $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                                $accrualForOneDay = $amountAccrued["accrual-hours"] / $TotalDays;
                                $amount = $accrualForOneDay * $remainingDays;
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                                $this->createTransaction($type->id, $action, $amount, $amount, $currentDate->toDateString(), $employee);
                            }
                        }
                    } else {
                        if ($currentDate == $levelStartDate) {
                            if ($levelStartDate == $currentAccrualDate) {
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                                $balance = $amountAccrued["accrual-hours"];
                                $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                            } else {
                                $accrualEndDate = $nextAccuralDate->copy()->addDays(-1);
                                $TotalDays = 1 + ($currentAccrualDate->diffInDays($accrualEndDate));
                                $remainingDays = 1 + ($levelStartDate->diff($accrualEndDate)->days);
                                $accrualForOneDay = $amountAccrued["accrual-hours"] / $TotalDays;
                                $amount = $accrualForOneDay * $remainingDays;
                                $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $accrualEndDate->toDateString();
                                $this->createTransaction($type->id, $action, $amount, $amount, $currentDate->toDateString(), $employee);
                            }
                        }
                    }
                    //First Accrual is Full Amount
                } else {
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate == $nextAccuralDate) {
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $currentDate->copy()->addDays(-1)->toDateString();
                            $balance = $amountAccrued["accrual-hours"];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                        }
                    } else {
                        if ($currentDate == $levelStartDate) {
                            $action = "Accrued Amount " . $levelStartDate->toDateString() . " to " . $nextAccuralDate->copy()->addDays(-1)->toDateString();
                            $balance = $amountAccrued["accrual-hours"];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued["accrual-hours"], $currentDate->toDateString(), $employee);
                        }
                    }
                }
            }
        } else {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $nextAccuralEndDate = $lastAccrualDate->copy()->addYears(1)->addDays(1);
            //Max Accrual Check
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualDate)) {
                if ($policy->accrual_happen == "At the end of period") {
                    if ($currentDate == $nextAccuralEndDate && $nextAccuralEndDate->month == $hireDate->month && $nextAccuralEndDate->day == $hireDate->day) {
                        $action = "Accrued Amount " . $lastAccrualDate->copy()->addDays(1)->toDateString() . " to " . $lastAccrualDate->copy()->addYears(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                } else {
                    if ($currentDate == $lastAccrualDate->copy()->addDays(1) && $currentDate->month == $hireDate->month && $currentDate->day == $hireDate->day) {
                        $action = "Accrued Amount " . $currentDate->toDateString() . " to " . $lastAccrualDate->copy()->addYears(1)->toDateString();
                        $balance = $balance = $previousTransactions->first()->balance + $accrualHours;
                        $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate->toDateString(), $employee);
                    }
                }
            }
        }
        // $currentDate=$currentDate->addDays(1)->startOfDay();
        // }
    }

    /**
     * @param $type
     * @param $policy
     * @param $levelData
     * @param $currentDate
     */

    public function accrualTypeQuarterly($type, $policy, $levelData, $currentDate, $employee)
    {
        // $currentDate=Carbon::now()->startOfDay();
        $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
        /*------All Intervals of Policy Level------*/
        $amountAccrued = json_decode($levelData['level']->amount_accrued, true);
        $accrualFirstDate = $amountAccrued["accrual-firstdate"];
        $accrualFirstMonth = $amountAccrued["accrual-firstMonth"];
        $accrualSecondDate = $amountAccrued["accrual-seconddate"];
        $accrualSecondMonth = $amountAccrued["accrual-secondMonth"];
        $accrualThirdDate = $amountAccrued["accrual-thirddate"];
        $accrualThirdMonth = $amountAccrued["accrual-thirdMonth"];
        $accrualFourthDate = $amountAccrued["accrual-fourthdate"];
        $accrualFourthMonth = $amountAccrued["accrual-fourthMonth"];
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
        // if ($currentDate->gte($levelData['levelStart']) && $currentDate->lte($levelData['levelEnd'])) {
        //if data already exist in transactions table.
        $lastTransaction = $previousTransactions->first();
        if (isset($lastTransaction)) {
            $this->CalculateRemainingBalance($previousTransactions);
            if (isset($levelData['carryOverDate'])) {
                if ($this->carryOverDateIsReachedAndNewBalanceIsCalculated($levelData, $currentDate, $previousTransactions, $type->id, $employee)) {
                    $previousTransactions = $this->getPreviousTransactions($type->id, $currentDate);
                }
            }
            $lastAccrualEndDate = $this->getLastAutoAccrualEndDate($previousTransactions);
            $tempDate = $lastAccrualEndDate->copy();
            //Max Accrual Check
            $accrualHours = $this->checkMaxAccrual($previousTransactions->first()->balance, $amountAccrued["accrual-hours"], $levelData['level']->max_accrual);
            if ($currentDate->gte($lastAccrualEndDate)) {
                $accrualInterval = $this->getQuarterlyInterval($interval, $tempDate);
                if ($policy->accrual_happen == "At the end of period") {
                    if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                        if ($currentDate->startOfDay()->gt($interval[$accrualInterval['next']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['next']]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['next']]['start']->toDateString() . " to " . $interval[$accrualInterval['next']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                        if ($currentDate->startOfDay()->gt($interval[$accrualInterval['current']]['start']->startOfDay()) && $currentDate->startOfDay() == $interval[$accrualInterval['current']]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        }
                    }
                } else {
                    if ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['end']->startOfDay()) {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval['next']]['start']->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['next']]['start']->toDateString() . " to " . $interval[$accrualInterval['next']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        } else {
                        }
                    } elseif ($tempDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval['current']]['start']->startOfDay()) {
                            $action = "Accrued Amount " . $interval[$accrualInterval['current']]['start']->toDateString() . " to " . $interval[$accrualInterval['current']]['end']->toDateString();
                            $balance = $previousTransactions->first()->balance + $accrualHours;
                            $this->createTransaction($type->id, $action, $balance, $accrualHours, $currentDate, $employee);
                        } else {
                        }
                    }
                }
            }
        } //if no data exists
        else {
            $accrualStartDate = $levelData['levelStart']->copy();
            if ($currentDate->gte($accrualStartDate)) {
                $accrualInterval = $this->getQuarterlyInterval($interval, $accrualStartDate);
                if ($policy['first_accrual'] == "Prorate") {
                    if ($accrualStartDate != $levelData['levelStart']) {
                        $interval[$accrualInterval['last']]['start'] = $interval[$accrualInterval['last']]['start']->addYears(-1)->startOfDay();
                        $interval[$accrualInterval['last']]['end'] = $interval[$accrualInterval['last']]['end']->addYears(-1)->startOfDay();
                        $diff = 1 + ($interval[$accrualInterval['last']]['start']->diffInDays($interval[$accrualInterval['last']]['end']));
                        $accrualForOneDay = $amountAccrued["accrual-hours"] / $diff;
                        $accrualDiff = 1 + ($levelData['levelStart']->diffInDays($interval[$accrualInterval['last']]['end']));
                        $amount = $accrualForOneDay * $accrualDiff;
                        $currentInterval = 'last';
                    } else {
                        $diff = 1 + ($interval[$accrualInterval['current']]['start']->diffInDays($interval[$accrualInterval['current']]['end']));
                        $accrualForOneDay = $amountAccrued["accrual-hours"] / $diff;
                        $accrualDiff = 1 + ($accrualStartDate->diffInDays($interval[$accrualInterval['current']]['end']));
                        $amount = $accrualForOneDay * $accrualDiff;
                        $currentInterval = 'current';
                    }
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amount;
                            $this->createTransaction($type->id, $action, $balance, $amount, $currentDate, $employee);
                        }
                    } else {
                        if ($currentDate->startOfDay() == $levelData['levelStart']) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amount;
                            $this->createTransaction($type->id, $action, $balance, $amount, $levelData['levelStart'], $employee);
                        }
                    }
                } else {
                    if ($accrualStartDate != $levelData['levelStart']) {
                        $interval[$accrualInterval['last']]['start'] = $interval[$accrualInterval['last']]['start']->addYears(-1)->startOfDay();
                        $interval[$accrualInterval['last']]['end'] = $interval[$accrualInterval['last']]['end']->addYears(-1)->startOfDay();
                        $currentInterval = 'last';
                    } else {
                        $currentInterval = 'current';
                    }
                    if ($policy->accrual_happen == "At the end of period") {
                        if ($currentDate->startOfDay() == $interval[$accrualInterval[$currentInterval]]['end']->copy()->addDays(1)->startOfDay()) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amountAccrued['accrual-hours'];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued['accrual-hours'], $currentDate, $employee);
                        }
                    } else {
                        if ($currentDate->startOfDay() == $levelData['levelStart']) {
                            $action = "Accrued Amount " . $levelData['levelStart'] . " to " . $interval[$accrualInterval[$currentInterval]]['end'];
                            $balance = $amountAccrued['accrual-hours'];
                            $this->createTransaction($type->id, $action, $balance, $amountAccrued['accrual-hours'], $levelData['levelStart'], $employee);
                        }
                    }
                }
            }
        }
        //  }
    }

    /**
     * @param $interval
     * @param $tempDate
     * @return mixed
     */
    public function getQuarterlyInterval($interval, $tempDate)
    {
        if ($tempDate->startOfDay()->between($interval['first']['start'], $interval['first']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "second";
            $newInterval['current'] = "first";
            $newInterval['last'] = "fourth";
            return $newInterval;
        } elseif ($tempDate->between($interval['second']['start'], $interval['second']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "third";
            $newInterval['current'] = "second";
            $newInterval['last'] = "first";
            return $newInterval;
        } elseif ($tempDate->between($interval['third']['start'], $interval['third']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "fourth";
            $newInterval['current'] = "third";
            $newInterval['last'] = "second";
            return $newInterval;
        } elseif ($tempDate->between($interval['fourth']['start'], $interval['fourth']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "first";
            $newInterval['current'] = "fourth";
            $newInterval['last'] = "second";
            return $newInterval;
        } else {
            $tempDate = $tempDate->addDays(1);
            return $this->getQuarterlyInterval($interval, $tempDate);
        }
    }

    /**
     * @param $interval
     * @param $tempDate
     * @return mixed
     */
    public function getInterval($interval, $tempDate)
    {
        if ($tempDate->startOfDay()->between($interval['first']['start']->startOfDay(), $interval['first']['end']->startOfDay())) {
            $newInterval = $interval;
            $newInterval['opposite'] = "second";
            $newInterval['current'] = "first";
            return $newInterval;
        } elseif ($tempDate->between($interval['second']['start']->startOfDay(), $interval['second']['end']->startOfDay())) {
            $newInterval = $interval;
            $newInterval['current'] = "second";
            $newInterval['opposite'] = "first";
            return $newInterval;
        } else {
            $tempDate = $tempDate->addDays(1);

            return $this->getInterval($interval, $tempDate);
        }
    }

    /**
     * @param $timeOffTypeId
     * @param $action
     * @param $balance
     * @param $hoursAccrued
     */
    public function createTransaction($timeOffTypeId, $action, $balance, $hoursAccrued, $accrualStart, $employee)
    {
        $currentDate = Carbon::now()->toDateString();
        $transaction = new TimeOffTransaction();
        $transaction->assign_time_off_id = $timeOffTypeId;
        $transaction->action = $action;
        $transaction->accrual_date = $accrualStart;
        $transaction->balance = round($balance, 2);
        $transaction->hours_accrued = round($hoursAccrued, 2);
        $transaction->employee_id = $employee->id;
        $transaction->save();
        return;
    }
}
