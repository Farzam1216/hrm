<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\RequestTimeOff;
use App\Models\Notification;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;

class ApproveTimeOffRequest
{
    use SendNotification;
    /**
     * @param $data
     * @return bool
     */
    public function execute($data)
    {
        if (!isset($data['requesttimeoffid'])) {
            $data['requesttimeoffid'] = $data->segment(6, '');
        }
        $requestedDataField = ApprovalRequestedDataField::whereJsonContains('requested_data->requesttimeoff_id', (int) $data['requesttimeoffid'])->first();
        $requestedApprovalWorkflow = ApprovalWorkflow::where('id', $requestedDataField->approval_workflow_id)->first();

        if (isset($requestedApprovalWorkflow->advance_approval_option_id)) {
            $remainingWorkflows = ApprovalWorkflow::where('approval_id', 2)->whereNotNull('advance_approval_option_id')
                ->where('level_number', '>', $requestedApprovalWorkflow->level_number)->get()->sortBy('level_number');
        } else {
            $remainingWorkflows = ApprovalWorkflow::where('approval_id', 2)->whereNull('advance_approval_option_id')
                ->where('level_number', '>', $requestedApprovalWorkflow->level_number)->get()->sortBy('level_number');
        }
        $requesterEmployee = Employee::where('id', $requestedDataField->requested_by_id)->first();
        $workflow = $remainingWorkflows->first();
        if (isset($workflow->id)) {
            ApprovalRequestedDataField::where('id', $requestedDataField->id)->update([
                'approval_workflow_id' => $workflow->id, 'comments' => $data['comment']
            ]);
            if (isset($data['notificationId'])) {
                $notificationIsCompleted =new SetNotificationStatusCompleted();
                $notificationIsCompleted->execute($data['notificationId']);
            }
            // sending notification to other workflow
            $notificationDetail = Notification::where('id', $data['notificationId'])->pluck('data')->first();
            $this->sendEmailAndNotification($requestedDataField->requested_by_id, $workflow->id, json_decode($notificationDetail, true));
            return true;
        } else {
            $approveRequest = RequestTimeOff::where('id', $data['requesttimeoffid'])->update([
                'status' => 'approved'
            ]);
            ApprovalRequestedDataField::where('id', $requestedDataField->id)->update([
                'is_approved' => true, 'approved_by' => Auth::id(), 'comments' => $data['comment']
            ]);
            // sending approve notification to employee
            $approvedTimeOffNotification = new SendApprovedTimeOffNotificationToEmployee();
            $approvedTimeOffNotification->execute($requesterEmployee, $requestedDataField, $data['requesttimeoffid']);
            if (isset($data['notificationId'])) {
                $notificationIsCompleted =new SetNotificationStatusCompleted();
                $notificationIsCompleted->execute($data['notificationId']);
            }
            $commentForTimeOff = new AddCommentForApprovedTimeOffRequest();
            $commentForTimeOff->execute($data);
            $approvedTimeOff = new AddApprovedTimeOffRequestInTransaction();
            $approvedTimeOff->execute($data, $requesterEmployee);
        }
    }
}
