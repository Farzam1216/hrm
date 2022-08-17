<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class StoreRole
{
    /**
     * Store a newly created Role in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string Type of role like,Employee, Manager and custom
     * @return Collection instance of created role
     */
    public function execute($request, $roleType)
    {
        $subRole = (isset($request->hasEmployeeRole) && $request->hasEmployeeRole == 'yes') ?  $request->employeeRole : null;
        try {
            return $role = Role::create([
                'name' => $request->name,
                'type' => $roleType,
                'description' => $request->description,
                'sub_role' => $subRole
            ]);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
