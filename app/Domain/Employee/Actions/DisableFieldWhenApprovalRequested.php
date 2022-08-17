<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Models\ApprovalRequestedDataField;

class DisableFieldWhenApprovalRequested
{

    /**
     * @param $id
     * @return array
     */
    public function execute($id)
    {
        if (!empty(ApprovalRequestedDataField::where('approval_id', 1)->where(
            'requested_for_id',
            $id
        )->whereNull('is_approved')->get())) {
            $employeeApprovals = ApprovalRequestedDataField::where('approval_id', 1)->where(
                'requested_for_id',
                $id
            )->whereNull('is_approved')->get();
            $disabledFields = [];
            foreach ($employeeApprovals as $employeeApproval) {
                $requestedData = json_decode($employeeApproval->requested_data, true);
                $modelName = array_keys($requestedData);
                if (in_array('employee', $modelName)) {
                    $fieldsList = array_keys($requestedData['employee']);
                    foreach ($fieldsList as $fieldList) {
                        $disabledFields[] = $fieldList;
                    }
                } elseif (isset($requestedData['education'])) {
                    $fieldsList = array_keys($requestedData['education']);
                    foreach ($fieldsList as $fieldList) {
                        $disabledFields[] = $fieldList;
                    }
                }
            }
        } else {
            $disabledFields = [];
        }
        return $disabledFields;
    }
}
