<?php

namespace Tests\Unit;

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Session;
use Tests\TestCase;

class NoAccessUserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test if a user has any role assigned, he can login the system.
     */
    public function test_user_with_a_role_can_login()
    {
        Session::start();

        $user = factory(Employee::class)->create([
            'password' => bcrypt($password = '12345'),
        ]);
        $role = factory(Role::class)->create();
        $user->assignRole($role->name);
        $response = $this->call('POST', route('login'), [
            '_token' => csrf_token(), 'official_email' => $user->official_email,
            'password' => $password,
        ]);

        $response->assertRedirect('/en/dashboard');
        $this->assertAuthenticated();
        $this->assertTrue($user->forceDelete());
        $this->assertTrue($role->forceDelete());
    }

    /**
     * Test if a user has no role assigned, he cannot login the system.
     */
    public function test_no_access_user_cannot_login()
    {
        Session::start();

        $user = factory(Employee::class)->make([
            'password' => bcrypt($password = '12345'),
        ]);
        $response = $this->call('POST', route('login'), [
            '_token' => csrf_token(), 'official_email' => $user->official_email,
            'password' => $password,
        ]);

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
