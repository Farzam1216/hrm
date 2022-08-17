<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Education;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;

class SendNotificationForInformationUpdate
{
    use SendNotification;
    /**
     * @param $type
     * @param $changedFields
     * @param $requestedDataID
     * @param null $modelID
     */
    //TODO: change its name from sendNotification to sendNotificationForInformationUpdate
    public function execute($type, $changedFields, $requestedDataID, $modelID = null)
    {
        $currentUser = Auth::user();
        $previousInformation = [];
        $previousInformationTemp = [];
        $requestedData = ApprovalRequestedDataField::find($requestedDataID);
        $approvalWorkflowId = ApprovalWorkflow::find($requestedData->approval_workflow_id);
        if ($type == 'employee') {
            $currentUserData = $currentUser->toArray();
            foreach ($currentUserData as $field => $value) {
                foreach ($changedFields as $fieldName => $data) {
                    if ($field == $fieldName) {
                        $previousInformationTemp[$field] = $value;
                    }
                }
            }
        } elseif ($type == 'education') {
            $currentUserData = Education::where('employee_id', $currentUser->id)->find($modelID)->toArray();
            foreach ($currentUserData as $field => $value) {
                foreach ($changedFields as $fieldName => $data) {
                    if ($field == $fieldName) {
                        $previousInformationTemp[$field] = $value;
                    }
                }
            }
        } else {
            $currentUserData = EmployeeDependent::where('employee_id', $currentUser->id)->find($modelID)->toArray();
            foreach ($currentUserData as $field => $value) {
                foreach ($changedFields as $fieldName => $data) {
                    if ($field == $fieldName) {
                        $previousInformationTemp[$field] = $value;
                    }
                }
            }
        }
        foreach (array_keys($changedFields) as $field) {
            $previousInformation[$field] = $previousInformationTemp[$field];
        }
        $NotificationDetails = [
            'greetings' => 'Hello Dear,',
            'subject' => 'Information Update Request',
            'InboxURL' => url('inbox'),
            'ApproveURL' => '/approval-requests/' . $requestedDataID . '/approve/true',
            'DisapproveURL' => '/approval-requests/' . $requestedDataID . '/approve/false',
            'requester' => $currentUser->firstname . ' ' . $currentUser->lastname,
            'approval_type' => 'Information Update Request',
            'body' => $currentUser->firstname . ' ' . $currentUser->lastname . ' is requesting an update to the following information.',
            'body_information' => 'Here all previous and requested data will go,',
            'previous_information' => $previousInformation,
            'requested_information' => $changedFields,
            'requestedDataID' => $requestedDataID,
            'status' => 'Pending'
        ];
        $this->sendEmailAndNotification(
            $requester_id = $currentUser->id,
            $workflow_id = $approvalWorkflowId->id,
            $NotificationDetails
        );
    }
}
