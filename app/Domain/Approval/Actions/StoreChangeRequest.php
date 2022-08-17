<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalWorkflow;
use App\Domain\Employee\Models\Employee;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;

class StoreChangeRequest
{
    use SendNotification;
    /**
     * storeChangeRequest.
     *
     * @param mixed $employeeID
     * @param mixed $approvalID
     * @param mixed $request
     *
     * @return void
     */
    public function execute($employeeID, $approvalID, $request)
    {
        foreach ($request->data as $model => $fields) {
            foreach ($fields as $fieldName => $data) {
                foreach ($data as $key => $value) {
                    //If key is 0 then it means new resource will be created in database
                    if ($key == 0) {
                        $key = null;
                    }
                    $requestedData[$model][$fieldName][] = ['id' => $key, 'value' => $value];
                }
            }
        }
        $previousData = (new GetPreviousData())->execute($requestedData, $approvalID);
        $newData = (new FlatRequestedData())->execute($requestedData);
        $requestedData = json_encode($requestedData);
        //It's store method so we might need to use store method?
        //Get workflow ID for Request against particular approval
        $workFlowID = ApprovalWorkflow::where('approval_id', $approvalID)->pluck('id')->first();
        if (isset($request->data)) {
            $approvalRequest = ApprovalRequestedDataField::create([
                'requested_field_id' => $request->fieldID,
                'approval_id' => $request->fieldID,
                'requested_by_id' => Auth::id(),
                'approval_id' => $approvalID,
                'requested_for_id' => $employeeID,
                'requested_data' => $requestedData,
                'approval_workflow_id' => $workFlowID,
                'comments' => $request->comment,
            ]);

            $changeRequestDetail = [
                'greetings' => 'Hello Dear ,',
                'subject' =>  Approval::find($approvalID)->name . ' Update Request',
                'InboxURL' => url('en/inbox'),
                'ApproveURL' => '/approval-requests/' . $approvalRequest->id . '/approve/true',
                'DisapproveURL' => '/approval-requests/' . $approvalRequest->id . '/approve/false',
                'requester' => Auth::user()->getFullNameAttribute(),
                'approval_type' => Approval::find($approvalID)->name . ' Update Request',
                'body_information' => 'The ' . Approval::find($approvalID)->name . ' Update Request has been requested for ' . Employee::find($employeeID)->getFullNameAttribute(),
                'previous_information' => $previousData,
                'requested_information' => $newData,
                'request_data_with_model' => $request->data
            ];

            $this->sendEmailAndNotification($employeeID, $workFlowID, $changeRequestDetail);

            return $approvalRequest;
        }
        return false;
    }
}
