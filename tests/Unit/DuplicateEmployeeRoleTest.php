<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\DuplicateEmployeeRole;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateEmployeeRoleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_user_can_create_duplicate_employee_role()
    {
        $role = factory(Role::class)->create(['type' => 'employee']);
        $duplicateEmployeeRole = new DuplicateEmployeeRole();
        $response = $duplicateEmployeeRole->execute($role->id);
        $this->assertIsInt($response);
        $this->assertTrue($role->forceDelete());
        $this->assertTrue(Role::find($response)->delete());
    }
}
