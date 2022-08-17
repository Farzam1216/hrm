<?php

namespace Tests\Unit;

use App\Domain\ACL\Actions\StoreNonEmployee;
use App\Domain\ACL\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AddNonEmployeeUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_add_non_employee_user()
    {
        $role = factory(Role::class)->create(['type' => 'manager']);
        $request = new Request(
            [
                'firstname' => 'Sky',
                'lastname' => 'Walker',
                'official_email' => 'skywalker@star.com',
                'employeerole' => $role->id,
            ]
        );
        $response = (new StoreNonEmployee())->execute($request);
        $this->assertIsObject($response);
        $this->assertTrue($role->forceDelete());
        $this->assertTrue($response->forceDelete());
    }
}
