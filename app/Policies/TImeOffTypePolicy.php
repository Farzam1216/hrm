<?php

namespace App\Policies;

use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Actions\GetAuthorizedUserPermissions;
use App\Traits\AccessibleFields;
use Illuminate\Auth\Access\HandlesAuthorization;

class TImeOffTypePolicy
{
    use HandlesAuthorization;
    use AccessibleFields;
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
     * @param Employee $user //Authenticated user
     * @param $employees
     * @return bool
     */
    public function view(Employee $user, $employees)
    {
        $permissions = $this->getAuthorizedUserPermissions->execute($employees);
        if (!empty($permissions['timeofftype']) || !empty($permissions['policy'])) {
            return true;
        } else {
            return false;
        }
    }
}
