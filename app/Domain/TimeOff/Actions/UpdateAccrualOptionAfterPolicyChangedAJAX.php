<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffTransaction;
use Carbon\Carbon;

class UpdateAccrualOptionAfterPolicyChangedAJAX
{
    /**
     * @param $request
     * @param $userId
     * @return mixed
     */
    public function execute($request, $userId)
    {
        $policyId = $request->policyId;
        $policyStartDate = $request->policyStartDate;
        $timeOfTypeId = $request->timeOfTypeId;
        $policy = Policy::where('id', $request->policyId)->first();
        $assignTimeOffType = AssignTimeOffType::where('employee_id', $userId)->first();
        $policyName = $policy->policy_name;
        if ($policyName == 'None') {
            $deleteBalance = new DeleteEmployeeBalanceByTimeOffType();
            $deleteBalance->execute($assignTimeOffType);
        } elseif ($policy->policy_name == 'Manual Updated Balance') {
            $adjustEmployeeBalance = new AdjustEmployeeBalanceByTimeOffType();
            $adjustEmployeeBalance->execute($assignTimeOffType);
        } elseif ($policy->policy_name != 'None' && $policy->policy_name != 'Manual Updated Balance') {
            $calculateTimeoffData = [
                "policyId" => $policyId,
                "timeOfTypeId" => $timeOfTypeId,
                "policy_start_date" => $policyStartDate,
                "newDate"  => Carbon::now()->addYears(3)->toDateString(),
                'isChangePolicy' => true
            ];
            $calulateTimeOff = new CalculateUpcommingTimeOffByEmployeeAJAX();
            $calculateTimeoff = $calulateTimeOff->execute($calculateTimeoffData, $userId);
            $data = $calculateTimeoff;
            if (isset($request['isSave'])) {
                $currentDate = Carbon::now()->startOfDay();
                $policyStartDate = Carbon::parse($policyStartDate)->startOfDay();
                $deleteTimeOff = new DeleteTransactionByTimeOffType();
                $deleteTimeOff->execute($policyStartDate, $assignTimeOffType);
                $getBalance = new GetBalanceByTimeOffType();
                $balance = $getBalance->execute($policyStartDate, $assignTimeOffType, $policyId, $userId);
                //insert transaction with change policy
                while ($policyStartDate->lte($currentDate)) {
                    $transactionOfCurrentDay = $data->where('accrual_date', $policyStartDate->toDateString());
                    if ($transactionOfCurrentDay->isNotEmpty()) {
                        foreach ($transactionOfCurrentDay as $transaction) {
                            $newTransaction = new TimeOffTransaction();
                            $hoursAccrued = $transaction['hours_accrued'];
                            $balance = number_format($balance, 2, '.', '') + $hoursAccrued;
                            $newTransaction->hours_accrued = $hoursAccrued;
                            $newTransaction->balance = $balance;
                            $newTransaction->assign_time_off_id = $assignTimeOffType->id;
                            $newTransaction->action = $transaction['action'];
                            $newTransaction->accrual_date = $transaction['accrual_date'];
                            $newTransaction->employee_id = $userId;
                            $newTransaction->save();
                        }
                    }

                    $policyStartDate = $policyStartDate->addDays(1)->startOfDay();
                }
            } else {
                return $data;
            }
        }
    }
}
