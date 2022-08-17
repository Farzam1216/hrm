<?php

namespace App\Policies;

use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Models\Employee;
use App\Traits\AccessibleFields;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeDocumentPolicy
{
    use AccessibleFields;
    use HandlesAuthorization;

    private $getAuthorizedUserPermissions;
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
        $this->getAuthorizedUserPermissions = new GetAuthorizedUserPermissions();
    }

    /**
     *
     * Authorize Employee Documents Index page
     * @param Employee $user
     * @param $employees
     * @return bool
     */
    public function view(Employee $user, $employees)
    {
        $permissions= $this->getAuthorizedUserPermissions->execute($employees);
        if (!empty($permissions['employeeDocument'])) {
            return true;
        } else {
            return false;
        }
    }
}
