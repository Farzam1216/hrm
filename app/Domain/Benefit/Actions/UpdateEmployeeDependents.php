<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Approval\Actions\SendNotificationForInformationUpdate;
use App\Domain\Approval\Actions\StoreApprovalForEmployeeRole;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Traits\AccessibleFields;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UpdateEmployeeDependents
{
    use AccessibleFields;
    /**
     * @param $id
     * @param $databenefit_status
     */
    public function execute($emp_id, $dependentID, $data)
    {
        $currentUser = Auth::user();
        $roles = $this->getRoles(Auth::user());
        $employeeRoles = (new GetAllEmployeeRoles())->execute($roles);
        $changedFields = [];
        //NOTE:this Another service is being called in Benefit Service
        if (!$currentUser->isAdmin()) {
            if ($currentUser->id == $emp_id) {
                $editWithApprovalFields = (new GetPermissionsOnRequestedFields())->execute('employeedependent', $data, $employeeRoles[0]);
                $changedFields = (new IsEmployeeDependentDataChanged())->execute($editWithApprovalFields, $emp_id, $dependentID);
                if (count($changedFields) > 0) {
                    $changedFieldsArray = (new StoreApprovalForEmployeeRole())->execute($emp_id, 'employeedependent', $changedFields, $dependentID);
                    (new SendNotificationForInformationUpdate())->execute('employeedependent', $changedFields, $changedFieldsArray['requestedDataID'], $dependentID);
                }
            }
        }
        $employeeDependent = EmployeeDependent::find($dependentID);
        foreach ($data as $field => $value) {
            if ($field == '_token' || $field == '_method') {
                continue;
            }
            if (count($changedFields) > 0) {
                if (in_array($field, $changedFields)) {
                    continue;
                }
            } else {
                $employeeDependent->{$field} = $value;
            }
        }
        $employeeDependent->save();
        if (count($changedFields) > 0) {
            Session::flash('success', trans('language.Employee Dependent requested succesfully'));
        } else {
            Session::flash('success', trans('language.Employee Dependent is updated succesfully'));
        }
    }
}
