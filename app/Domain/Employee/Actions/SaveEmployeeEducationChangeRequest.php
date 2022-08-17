<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Approval\Actions\SendNotificationForInformationUpdate;
use App\Domain\Approval\Actions\StoreApprovalForEmployeeRole;
use App\Traits\AccessibleFields;
use Illuminate\Support\Facades\Auth;

class SaveEmployeeEducationChangeRequest
{
    use AccessibleFields;
    public function execute($id, $data)
    {
        $roles = $this->getRoles(Auth::user());
        $employeeRoles = (new GetAllEmployeeRoles())->execute($roles);
        foreach ($employeeRoles as $role) {
            $editWithApprovalFields = (new GetPermissionsOnRequestedFields())->execute('education', $data, $role);
            $changedFields = (new IsEducationRequestedDataChanged())->execute($editWithApprovalFields, $id);
            if (count($changedFields) > 0) {
                $changedFieldsArray = (new StoreApprovalForEmployeeRole())->execute(
                    Auth::id(),
                    'education',
                    $changedFields,
                    $id
                );
                (new SendNotificationForInformationUpdate())->execute(
                    'education',
                    $changedFields,
                    $changedFieldsArray['requestedDataID'],
                    $id
                );
            }
        }
        return $changedFields;
    }
}
