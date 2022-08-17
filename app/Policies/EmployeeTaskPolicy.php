<?php

namespace App\Policies;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Actions\GetAuthorizedUserPermissionsForTask;
use App\Traits\AccessibleFields;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeTaskPolicy
{
    use HandlesAuthorization;
    use AccessibleFields;

    private $task;
    public function before($user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->task = new GetAuthorizedUserPermissionsForTask();
    }

    /**
     * @param Employee $user
     * @param $employees
     * @return bool
     */

    public function view(Employee $user, $employee)
    {
        $permissions = $this->task->execute($employee);
        if (!empty($permissions['tasks']) || $user->id == $employee[0]->id) {
            return true;
        } else {
            return false;
        }
    }
}
