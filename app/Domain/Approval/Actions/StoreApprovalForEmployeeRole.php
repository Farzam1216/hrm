<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Approval\Models\ApprovalRequestedField;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Education;
use Illuminate\Support\Facades\Auth;

class StoreApprovalForEmployeeRole
{
    /**
     * @param $id
     * @param $type
     * @param $changedFields
     *
     * @return mixed
     */
    public function execute($id, $type, $changedFields, $modelID = null)
    {
        $currentUser = Auth::user();
        $approvals = Approval::where('name', 'Information Updates')->first();
        $approvalWorkflow = (new GetApprovalWorkFlow())->execute($id);
        if (count($changedFields) > 0) {
            if ($approvals->count() > 0) {
                // $requestedFields = ApprovalRequestedField::where('approval_id', $approvals->id)->get()->last();
                $requestedData = new ApprovalRequestedDataField();
                // $requestedData->requested_field_id = $approvalData;
                $requestedData->requested_by_id = $currentUser->id;
                // $requestedData->requested_field_id = 0;
                $requestedData->approval_id = $approvals->id;
                //In cases when the Manager or any other with Permission has tried to Changed the Data
                if ($currentUser->id == $id) {
                    $requestedData->requested_for_id = $currentUser->id;
                } else {
                    //When Education type data gas been changed
                    if ('education' == $type) {
                        $education = Education::find($modelID);

                        $requestedData->requested_for_id = $education->employee_id;
                    }
                    if ('employeedependent' == $type) {
                        $dependent = EmployeeDependent::find($modelID);

                        $requestedData->requested_for_id = $dependent->emp_id;
                    }
                    //When Employee Data has been Changed.
                    if ('employee' == $type) {
                        $requestedData->requested_for_id = (int) $id;
                    }
                }

                if (isset($modelID)) {
                    $requestedData->requested_data = json_encode([
                        $type . '_id' => $modelID,
                        $type => $changedFields,
                    ]);
                } else {
                    $requestedData->requested_data = json_encode([
                        $type => $changedFields,
                    ]);
                }

                $requestedData->approval_workflow_id = $approvalWorkflow->id; //This is the default value for Information Update Approvals to get started
                $requestedData->save();
            }
        }
        // (new SendNotificationForInformationUpdate())->execute($type, $changedFields, $requestedData->id, $modelID);
        $data = [
            'changeFields' => $changedFields,
            'requestedDataID' => $requestedData->id,
        ];
        return $data;
    }
}
