<?php

namespace Tests\Feature;

use App\Domain\ACL\Actions\GetRoleAndAccessLevel;
use App\Domain\ACL\Models\Role;
use Tests\TestCase;

class EditMangerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_admin_can_edit_manager()
    {
        // $role = Role::create([
        //     'name' => 'Ben',
        //     'type' => 'manager',
        //     'description' => 'This is default',
        // ]);
        // $getRoleAndAccessLevel = new GetRoleAndAccessLevel();
        // $user = $getRoleAndAccessLevel->execute($role->id);
        // $this->assertIsArray($user);
    }
}
