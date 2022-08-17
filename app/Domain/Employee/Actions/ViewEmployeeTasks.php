<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Task\Actions\AuthorizeUserForTask;
use App\Domain\Task\Actions\MyTask;

class ViewEmployeeTasks
{
    public function execute($employee_id, $request, $controller)
    {
        $employee = (new GetEmployeeByID())->execute($employee_id);
        (new AuthorizeUserForTask())->execute('view', $controller, 'tasks', [$employee]);
        $data = (new MyTask())->execute($employee_id, $request->all());

        (new ToggleEmployeeBasedMenuItems())->execute($employee_id);
        return $data;
    }
}
