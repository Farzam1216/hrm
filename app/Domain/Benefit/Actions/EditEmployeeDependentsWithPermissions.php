<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;
use App\Models\Country;

class EditEmployeeDependentsWithPermissions
{
    /**
     * @param $id
     * @return array
     */
    public function execute($id, $employeeID, $controller)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($employeeID);
        $getEmployeeByID = new GetEmployeeByID();
        $data = [];
        $data['permissions'] = (new GetAuthorizedUserPermissions)->execute([$getEmployeeByID->execute($employeeID)]);
        (new AuthorizeUser)->execute("edit", $controller, "dependents", [$getEmployeeByID->execute($employeeID)]);

        $employeeDependents = EmployeeDependent::where(['employee_id' => $employeeID, 'id' => $id])->first();
        if (!isset($employeeDependents)) {
            return null;
        }

        $disbaledFields = [];
        $approvalRequestedData = ApprovalRequestedDataField::where('approval_id', 1)->where('requested_for_id', $employeeID)->whereNull('is_approved')->get();
        foreach ($approvalRequestedData as $request) {
            $requestedData = json_decode($request->requested_data, true);
            $keys = array_keys($requestedData);
            if ($keys[0] == 'employeedependent') {
                $fieldsNames = array_keys($requestedData['employeedependent']);
                foreach ($fieldsNames as $fieldsName) {
                    $disbaledFields[] = $fieldsName;
                }
            }
        }
        $data['employeeDependent'] = $employeeDependents;
        $data['countries'] = Country::all();
        $data['disabledFields'] = $disbaledFields;
        return $data;
    }
}
