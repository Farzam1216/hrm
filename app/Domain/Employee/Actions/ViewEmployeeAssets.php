<?php


namespace App\Domain\Employee\Actions;

class ViewEmployeeAssets
{
    public function execute($id, $controllerName)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($id);
        $employee=(new GetEmployeeByID())->execute($id);

        (new AuthorizeUser())->execute('view', $controllerName, 'assets', [$employee]);
        $employeeData['info'] = (new GetEmployeeAssets())->execute($id);
        $employeeData['permissions'] = (new GetAuthorizedUserPermissions())->execute([$employee]);
        return $employeeData;
    }
}
