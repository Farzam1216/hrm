<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;

class ViewEmployeeBenefit
{
    public function execute($userId, $controller)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($userId);
        $employee=(new GetEmployeeByID())->execute($userId);
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute([$employee]);
        (new AuthorizeUser)->execute("view", $controller, "employeeBenefits", [$employee]);
        $data['employeeBenefitStatus'] = (new GetEmployeeBenefitStatusHistory())->execute($userId);
        $data['employeeBenefitDetails'] = (new GetEmployeeBenefitDetails())->execute($userId);
        return $data;
    }
}
