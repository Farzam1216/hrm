<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\EditEmployeeRoleAndPermissions;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class EditEmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_edit_employee_role()
    {
        $role = factory(Role::class)->create(['type' => 'employee']);
        $request = new Request([
            'name' => 'Employee edit',
            'description' => 'it is description',
            'employeePermission' => ['view employee id'],
        ]);
        $editEmployeeRoleAndPermissions = new EditEmployeeRoleAndPermissions();
        $response = $editEmployeeRoleAndPermissions->execute($request, $role->id);
        $this->assertEquals(true, $response);
        $this->assertTrue($role->forceDelete());
    }
}
