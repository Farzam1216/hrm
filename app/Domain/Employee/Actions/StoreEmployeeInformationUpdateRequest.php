<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Actions\SendNotificationForInformationUpdate;
use App\Domain\Approval\Actions\StoreApprovalForEmployeeRole;

class StoreEmployeeInformationUpdateRequest
{
    public function execute($id, $data, $employeeRole)
    {
        $editWithApprovalFields = (new GetPermissionsOnRequestedFields())->execute('employee', $data, $employeeRole);
        $changedFields = (new IsEmployeeRequestedDataChanged())->execute($editWithApprovalFields, $id);
        if (count($changedFields) > 0) {
            $changedFieldsArray = (new StoreApprovalForEmployeeRole())->execute(
                $id,
                'employee',
                $changedFields
            );
            (new SendNotificationForInformationUpdate())->execute(
                'employee',
                $changedFields,
                $changedFieldsArray['requestedDataID']
            );
        }
    }
}
