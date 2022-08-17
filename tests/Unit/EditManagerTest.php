<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\EditManagersRoleAndPermissions;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class EditManagerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /*
     *
     * A basic unit test example.
     */

    public function test_user_can_see_edit_Manager_page()
    {
        $request = new Request([
            'name' => 'Employee dummy 2',
            'description' => 'it is description',
            'employeePermission' => ['view employee id'],
            'managerRole' => ['access-level' => '2'],
        ]);
        $role = factory(Role::class)->create(['type' => 'manager']);
        $response = (new EditManagersRoleAndPermissions())->execute($request, $role->id);
        $this->assertEquals(true, $response);
        $this->assertTrue($role->forceDelete());
    }
}
