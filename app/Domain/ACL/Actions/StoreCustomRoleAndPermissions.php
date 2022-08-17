<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class StoreCustomRoleAndPermissions
{
    /**
     * Store Custom level with permission.
     * @param Request $request
     * @return bool
     */
    public function execute($request)
    {
        $subRole = (isset($request->hasEmployeeRole) && $request->hasEmployeeRole == 'yes' && $request->employeeRole != 'full') ?  $request->employeeRole : null;
        try {
            $role = Role::create([
                'name' => $request->name,
                'type' => 'custom',
                'description' => $request->description,
                'sub_role' => $subRole
            ]);
        } catch (\Throwable $th) {
            return false;
        }
        if (isset($request->customPermissions)) {
            $customPermissions = array_filter($request->customPermissions);
            (new GivePermissionsToCustomRole())->execute($request, $customPermissions, $role);
        }

        if (isset($request->fullPermissions)) {
            $accessLevel['access-level'] = 4;
            $role->givePermissionTo($accessLevel, $request->fullPermissions);
        }
        return $role;
    }
}
