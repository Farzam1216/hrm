<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\EditCustomRoleAndPermissions;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class EditCustomTest extends TestCase
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
    public function test_user_can_edit_custom_role()
    {
        $role = factory(Role::class)->create(['type' => 'custom']);
        $request = new Request([
            'name' => 'Employee edit',
            'description' => 'it is description',
            'customPermissions' => ['view employee id'],
            'fullPermissions' => ['manage employees PTO'],
            'type' => 'custom',
            'sub_role' => null,
        ]);
        $response = (new EditCustomRoleAndPermissions())->execute($request, $role->id);
        $this->assertEquals(true, $response);
        $this->assertTrue($role->forceDelete());
    }
}
