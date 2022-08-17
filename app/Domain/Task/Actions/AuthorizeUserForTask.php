<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\EmployeeTask;

class AuthorizeUserForTask
{
    /**
     * @param $policyMethod
     * @param $controllerName
     * @param $modelName
     * @param $employees
     * @return mixed
     */

    public function execute($policyMethod, $controllerName, $modelName, $employee)
    {
        $models = ['tasks' => EmployeeTask::class,];
        return $controllerName->authorize($policyMethod, [$models[$modelName], $employee]);
    }
}
