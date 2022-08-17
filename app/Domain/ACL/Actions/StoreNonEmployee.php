<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;

class StoreNonEmployee
{
    public function execute($request)
    {
        $user = Employee::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'official_email' => $request->official_email,
            'password' => bcrypt('123456'),
            'type' => 'contractor'
        ]);
        $role = Role::where('id', $request->employeerole)->first();
        if (collect($user)->isNotEmpty()) {
            if ($request->employeerole == 'noaccess') {
                $user->roles()->detach();
                return $user;
            }
            $user->syncRoles([$role->name]);
            return $user;
        }
    }
}
