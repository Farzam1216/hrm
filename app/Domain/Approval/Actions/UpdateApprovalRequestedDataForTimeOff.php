<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use Illuminate\Support\Facades\Auth;

class UpdateApprovalRequestedDataForTimeOff
{
    /**
     * @param $requestTimeoff
     * @param $requestTimeoffHoursSum
     * @param $timeOffType
     * @return array
     */
    public function execute($requestTimeoff, $requestTimeoffHoursSum, $timeOffType)
    {
        $userID = Auth::id();
        $advanceApprovalOptions = AdvanceApprovalOption::where('approval_id', 2)->first();
        if (isset($advanceApprovalOptions)) {
            $getWorkFlow = new GetApprovalWorkFlowForTimeOff();
            $workflow = $getWorkFlow->execute($userID);
        } else {
            $workflow = ApprovalWorkflow::where('approval_id', 2)->whereNull('advance_approval_option_id')->first();
        }
        $approvalRequestedDataField = ApprovalRequestedDataField::whereJsonContains('requested_data->requesttimeoff_id', $requestTimeoff->id)->update([
            'requested_by_id' => $userID, 'requested_for_id' => $userID,
            'requested_data' => json_encode(["requesttimeoff_id"=>$requestTimeoff->id,
                "total_hours"=>$requestTimeoffHoursSum,"timeofftype_name" => $timeOffType->time_off_type_name]),
            'approval_workflow_id' => $workflow->id, 'is_approved' => null, 'approved_by' => null , 'comments' => null
        ]);
        return ['workflow'=>$workflow,'approvalRequestedDataField'=>$approvalRequestedDataField];
    }
}
