<?php


namespace App\Domain\Approval\Actions;

use App\Domain\ACL\Models\Role;

class GetAllCustomAccessLevels
{
    /**
     * @return collection
     */
    public function execute()
    {
        return Role::where('type', 'custom')->get();
    }
}
