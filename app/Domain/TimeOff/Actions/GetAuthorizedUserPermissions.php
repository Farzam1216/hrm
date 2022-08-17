<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Traits\AccessibleFields;

class GetAuthorizedUserPermissions
{
    use AccessibleFields;

    /**
     * @param $employees
     * @return array
     */
    public function execute($employees)
    {
        $permissions = [];
        $permissions['timeofftype'] = $this->getAccessibleFieldList(TimeOffType::class, $employees);
        $permissions['policy'] = $this->getAccessibleFieldList(Policy::class, $employees);
        return $permissions;
    }
}
