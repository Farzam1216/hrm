<?php


namespace App\Domain\Approval\Actions;

use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Domain\TimeOff\Models\TimeOffTransaction;

class AddApprovedTimeOffRequestInTransaction
{
    /**
     * @param $data
     */
    public function execute($data, $requesterEmployee)
    {
        $requestTimeoff = RequestTimeOff::where('id', $data['requesttimeoffid'])->with('requestTimeOffDetail')->first();
        TimeOffTransaction::create([
            'assign_time_off_id' => $requestTimeoff->assign_timeoff_type_id,
            'action' => 'Used',
            'accrual_date' => $requestTimeoff->to,
            'balance' => -1, //for cron
            'hours_accrued' => -$requestTimeoff->requestTimeOffDetail->sum('hours'),
            'employee_id' => $requesterEmployee->id,
        ]);
    }
}
