<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\EmployeeTask;
use App\Traits\AccessibleFields;

class GetAuthorizedUserPermissionsForTask
{
    use AccessibleFields;
    /**
     * @param $employees
     * @return array
     */
    public function execute($employees)
    {
        $permissions = [];
        $permissions['tasks'] = $this->getAccessibleFieldList(EmployeeTask::class, $employees);
        return $permissions;
    }
}
