<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\TimeOffTransaction;
use Illuminate\Support\Facades\DB;

class AdjustBalances
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($data, $id)
    {
        $type_id = (int) $data['type'];
        $policy_id = (int) $data['policy'];
        $data1 =  AssignTimeOffType::with('timeofftransaction')->where('employee_id', $id)->where('type_id', $type_id)->where('attached_policy_id', $policy_id)->get();
        $assign_id = $data1->pluck('id');

        $adjust = new TimeOffTransaction();
        $adjust->assign_time_off_id = $assign_id[0]; //getting index of array i.e. id
        $adjust->accrual_date = $data['effective-date'];
        //Getting sum of previous dates for updating balance
        $result = DB::table('time_off_transactions')
            ->select(DB::raw('SUM(hours_accrued) as hours'))
            ->where('assign_time_off_id', $assign_id[0])
            ->where('accrual_date', '<=', $data['effective-date'])
            ->get();
        if ($data['amount-options'] == "Subtract") {
            $adjust->balance = $result[0]->hours - $data['adjust-hours'];
        } else {
            $adjust->balance = $result[0]->hours + $data['adjust-hours'];
        }
        // $adjust->balance = $data['new-balance'];
        $adjust->action = "Manual Adjustment";
        if ($data['amount-options'] == "Subtract") {
            $adjustingHours = $data['adjust-hours'];
            $adjust->hours_accrued = -$adjustingHours;
        } else {
            $adjust->hours_accrued = $data['adjust-hours'];
        }

        $adjust->note = $data['note'];
        $adjust->employee_id = $id;
        $adjust->save();

        $updateBalance = new UpdateBalanceForEffectiveDate();
        $updateBalance->execute($assign_id[0], $data);
    }
}
