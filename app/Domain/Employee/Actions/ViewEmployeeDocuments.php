<?php


namespace App\Domain\Employee\Actions;

class ViewEmployeeDocuments
{
    public function execute($employee_id, $controller)
    {
        $employee=(new GetEmployeeByID())->execute($employee_id);
        (new AuthorizeUser())->execute("view", $controller, "employeeDocument", [$employee]);
        (new ToggleEmployeeBasedMenuItems())->execute($employee_id);
        $data=(new GetEmployeeDocuments())->execute($employee_id);
        $data['permissions']=(new GetAuthorizedUserPermissions())->execute([$employee]);
        return $data;
    }
}
