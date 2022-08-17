<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\DuplicateManagerRole;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateManagerRoleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_create_duplicate_manager_role()
    {
        $role = factory(Role::class)->create(['type' => 'manager']);
        $duplicateManagerRole = new DuplicateManagerRole();
        $response = $duplicateManagerRole->execute($role->id);
        $this->assertIsInt($response);
        $this->assertTrue($role->forceDelete());
        $this->assertTrue(Role::find($response)->delete());
    }
}
