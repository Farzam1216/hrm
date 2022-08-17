<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\AdvanceApprovalOption;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Notifications\DefaultApprovalNotification;
use App\Traits\SendNotification;

class CancelTimeOffRequestandSendNotification
{
    use SendNotification;

    /**
     * @param $data
     * @param $userID
     */
    public function execute($data, $userID)
    {
        $requestTimeOff = RequestTimeOff::find($data['requestTimeOffId']);
        $requestTimeOff->status = "Canceled";
        $requestTimeOff->save();
        $requestedDataField = ApprovalRequestedDataField::whereJsonContains('requested_data->requesttimeoff_id', (int) $data['requestTimeOffId'])->first();
        $advanceApprovalOptions = AdvanceApprovalOption::where('approval_id', 2)->first();
        if (isset($advanceApprovalOptions)) {
            $workflow = $this->getApprovalWorkFlow($userID);
            $timeOffWorkflows = ApprovalWorkflow::where('approval_id', 2)->where('advance_approval_option_id', $workflow->advance_approval_option_id)->get();
        } else {
            $timeOffWorkflows = ApprovalWorkflow::where('approval_id', 2)->whereNull('advance_approval_option_id')->get();
        }
        $requesterEmployee = Employee::where('id', $requestedDataField->requested_by_id)->first();
        $notificationDetail = [
            'greetings' => 'Hello,',
            'employeeInfo' => $requesterEmployee,
            'subject' => 'Canceled Time Off Request',
            'title' => 'Canceled Time Off Request',
            'InboxURL' => url('en/inbox'),
            'url' => '/employee/' . $requesterEmployee->id . '/timeoff',
            'requester' => $requesterEmployee->getFullNameAttribute(),
            'approval_type' => 'Time Off Request',
            'message' => 'It looks like '.$requesterEmployee->getFullNameAttribute().' canceled the time off request that was previously submitted from '.$requestTimeOff->to. ' - '.$requestTimeOff->from,
            'body' => 'It looks like '.$requesterEmployee->getFullNameAttribute().' canceled the time off request that was previously submitted from '.$requestTimeOff->to. ' - '.$requestTimeOff->from,
            'body_information' => 'Status: Canceled'
        ];

        foreach ($timeOffWorkflows as $workflow) {
            $this->sendEmailAndNotification($requestedDataField->requested_by_id, $workflow->id, $notificationDetail);
        }
        $user = (new Employee)->forceFill([
            'id' => $requesterEmployee->id, 'name' => $requesterEmployee->firstname, 'email' => $requesterEmployee->official_email,
        ]);
        $user->notify(new DefaultApprovalNotification($requesterEmployee, $notificationDetail));
    }
}
