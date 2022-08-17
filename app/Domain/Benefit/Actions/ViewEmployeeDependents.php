<?php

namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;

class ViewEmployeeDependents
{
    public function execute($employeeID, $controller)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($employeeID);
        $getEmployeeByID = new GetEmployeeByID();
        $data['employeeDependents'] = (new GetEmployeeDependents)->execute($employeeID);
        (new AuthorizeUser)->execute("view", $controller, "dependents", [$getEmployeeByID->execute($employeeID)]);//TODO:Work after EmployeeService breakdown
          $data['permissions'] = (new GetAuthorizedUserPermissions)->execute([$getEmployeeByID->execute($employeeID)]);//TODO:Work after EmployeeService breakdown
          return $data;
    }
}
