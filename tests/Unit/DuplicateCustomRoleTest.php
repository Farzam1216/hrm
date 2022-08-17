<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\DuplicateCustomRole;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateCustomRoleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_create_duplicate_custom_role()
    {
        $role = factory(Role::class)->create(['type' => 'custom']);
        $duplicateCustomRole = new DuplicateCustomRole();
        $response = $duplicateCustomRole->execute($role->id);
        $this->assertIsInt($response);
        $this->assertTrue($role->forceDelete());
        $this->assertTrue(Role::find($response)->delete());
    }
}
