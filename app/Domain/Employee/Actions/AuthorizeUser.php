<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeDocument;

class AuthorizeUser
{
    /**
     * @param $policyMethod
     * @param $controllerName
     * @param $modelName
     * @param $employees
     *
     * @return mixed
     */
    public function execute($policyMethod, $controllerName, $modelName, $employees)
    {
        $models = [
            'Employee' => Employee::class,
            'employeeDocument' => EmployeeDocument::class,
            'assets' => Asset::class,
        ];

        return $controllerName->authorize($policyMethod, [$models[$modelName], $employees]);
    }
}
