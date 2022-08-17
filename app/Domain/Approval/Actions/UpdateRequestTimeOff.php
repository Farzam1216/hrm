<?php


namespace App\Domain\Approval\Actions;

use Illuminate\Support\Carbon;
use App\Jobs\RequestTimeOffJob;
use App\Models\RequestTimeOffDetail;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\TimeOff\Models\AssignTimeOffType;

class UpdateRequestTimeOff
{
    /**
     * @param $data
     */
    public function execute($data, $id)
    {
        $requestedData = RequestTimeOff::find($data['requestid']);
        $requestedData->to = $data['to'];
        $requestedData->from = $data['from'];
        $requestedData->note = $data['noteEdit'];
        $requestedData->assign_timeoff_type_id = $data['assign_timeoff_type_id'];
        $requestedData->status = 'pending';
        $requestedData->save();
        RequestTimeOffDetail::where('request_time_off_id', $data['requestid'])->delete();
        $getRequestTimeOff = new UpdateRequestTimeOffDetials();
        $requestTimeoffHoursSum = $getRequestTimeOff->execute($data, $requestedData->id);
        $assignTimeOff = AssignTimeOffType::find($data['assign_timeoff_type_id']);
        $timeOffType = TimeOffType::find($assignTimeOff->type_id);
        $workflow = ApprovalWorkflow::where('approval_id', 2)->whereNull('advance_approval_option_id')->first();
        $updateRequestTimeoffData = new UpdateApprovalRequestedDataForTimeOff();
        $data = $updateRequestTimeoffData->execute($requestedData, $requestTimeoffHoursSum, $timeOffType);
        RequestTimeOffJob::dispatch($requestedData, $data, $requestTimeoffHoursSum, $timeOffType, $id)->delay(Carbon::now()->addMinutes(1));
    }
}
