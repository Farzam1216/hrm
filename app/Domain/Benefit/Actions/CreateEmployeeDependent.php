<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;

class CreateEmployeeDependent
{
    /**
     * create benefit plan permission on new benefit plan create
     * @param $permission
     * @return
     */
    public function execute($employeeID, $controller)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($employeeID);
        $getEmployeeByID = new GetEmployeeByID();
        $data['permissions'] = (new GetAuthorizedUserPermissions)->execute([$getEmployeeByID->execute($employeeID)]);
        (new AuthorizeUser)->execute("create", $controller, "dependents", [$getEmployeeByID->execute($employeeID)]);
        $data['country'] = (new GetCountriesList)->execute();
        return $data;
    }
}
