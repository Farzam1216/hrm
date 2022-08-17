<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;

use Carbon\Carbon;

class CalculateUpcommingTimeOffByEmployeeAJAX
{
    /**
     * @param $request
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function execute($request, $id)
    {
        $data = collect();
        $balance = 0;
        $employee = Employee::where('id', $id)->first();
        $policy = Policy::where('id', $request['policyId'])->with('level')->first();
        $type = TimeOffType::where('id', $policy->time_off_type)->first();
        $isPolicyStart = false;
        if (isset($type)) {
            if (isset($policy->level[0])) {
                $newDate = Carbon::parse($request['newDate'])->startOfDay();
                $recentAutoTransaction = null;
                $employeeHireDate = Carbon::parse($employee->joining_date)->startOfDay();
                $getTransacntions = new GetAutomatedAndManualTransactions();
                $allTransactions = $getTransacntions->execute($type);
                $manualTransactions = $allTransactions['manualTransactions'];
                $autoTransaction = $allTransactions['autoTransaction'];
                $isChagnedPolicy = false;
                $isChagnedPolicy = isset($request['isChangePolicy']);
                $isPolicyStart = false;
                $recalculateTransactions=new RecalculateTransactionsIfPolicyChanged();
                $balance = $recalculateTransactions->execute(
                    $data,
                    $request,
                    $isChagnedPolicy,
                    $manualTransactions,
                    $autoTransaction,
                    $employeeHireDate
                );
                $currentDate = $employeeHireDate->copy();
                while ($currentDate->lte($newDate)) {
                    $numberOfLevels = $policy->level->count();
                    $policyLevel = new GetPolicyLevel();
                    $level = $policyLevel->execute(
                        $type,
                        $numberOfLevels,
                        $employeeHireDate,
                        $policy,
                        $currentDate,
                        $recentAutoTransaction,
                        $request
                    );
                    $levelData = $level['level'];
                    //Carry Over balance to next year
                    if (isset($levelData['carry_over_amount'])) {
                        $newBalanceCalculate = new CarryOverDateIsReachedAndNewBalanceIsCalculated();
                        $balance = $newBalanceCalculate->execute(
                            $policy,
                            $data,
                            $levelData,
                            $currentDate,
                            $balance,
                            $type->id,
                            $employeeHireDate
                        );
                    }
                    $amountAccrued = json_decode($levelData->amount_accrued, true);
                    if ($amountAccrued["accrual-type"] == "Daily") {
                        $manageAccrualTyeDaily = new ManageTransactionsForAccrualTypeDaily();
                        $data = $manageAccrualTyeDaily->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Weekly") {
                        $manageAccrualTyeWeekly = new ManageTransactionsForAccrualTypeWeekly();
                        $data = $manageAccrualTyeWeekly->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Monthly") {
                        $manageAccrualTyeMonthly = new ManageTransactionsForAccrualTypeMonthly();
                        $data = $manageAccrualTyeMonthly->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Yearly") {
                        $manageAccrualTyeYearly = new ManageTransactionsForAccrualTypeYearly();
                        $data = $manageAccrualTyeYearly->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Twice a Yearly") {
                        $manageAccrualTyeYearly = new ManageTransactionsForAccrualTypeTwiceAYear();
                        $data = $manageAccrualTyeYearly->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Twice a month") {
                        $manageAccrualTyeTwiceAMonth =new ManageTransactionsForAccrualTypeTwiceAMonth();
                        $data = $manageAccrualTyeTwiceAMonth->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Anniversary") {
                        $manageAccrualTyeAnniversary = new ManageTransactionsForAccrualTypeAnniversary();
                        $data = $manageAccrualTyeAnniversary->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Quarterly") {
                        $manageAccrualTyeQuarterlyAYear = new ManageTransactionsForAccrualTypeQuarterlyAYear();
                        $data = $manageAccrualTyeQuarterlyAYear->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    } elseif ($amountAccrued["accrual-type"] == "Every other week") {
                        $manageAccrualTyeEveryOtherWeek = new ManageTransactionsForAccrualTypeEveryOtherWeek();
                        $data = $manageAccrualTyeEveryOtherWeek->execute(
                            $data,
                            $balance,
                            $type,
                            $policy,
                            $level,
                            $request,
                            $employeeHireDate,
                            $currentDate,
                            $recentAutoTransaction
                        );
                    }

                    //Manual,used or transaction which has no action date
                    $manualTransaction = $manualTransactions->where('accrual_date', $currentDate->toDateString());
                    if ($manualTransaction->isNotEmpty()) {
                        $manualTransactionOBJ = new AddManualTransaction();
                        $manualTransactionOBJ->execute($data, $manualTransaction, $balance);
                    }
                    $currentDate = $currentDate->addDays(1)->startOfDay();
                }
                return $data;
            }
        }
    }
}
