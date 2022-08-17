<?php

namespace Tests\Unit;

use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthorizedEmployeeMenuTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function authenticatedUser()
    {
        $user = factory(Employee::class)->create();
        $user->assignRole('admin');

        return $user;
    }

    /**
     * A simple test to check if unauthorized menu options are hidden.
     *
     * @test
     *
     * @return void
     */
    public function unauthorized_user_id_is_not_set_in_session()
    {
        $user = $this->authenticatedUser();

        $response = $this->actingAs($user)
                         ->get('en/employee/edit/'.$user->id);
        $response->assertStatus(200);
        $this->assertFalse(session()->has('unauthorized_user'));
        $this->assertTrue($user->forceDelete());
    }

    /**
     * A simple test to check if unauthorized menu options are set to be shown.
     *
     * @test
     *
     * @return void
     */
    public function unauthorized_user_id_is_set_in_session()
    {
        $authenticatedUser = $this->authenticatedUser();
        $unAuthenticatedUser = factory(Employee::class)->create();

        $response = $this->actingAs($authenticatedUser)
            ->get('en/employee/edit/'.$unAuthenticatedUser->id);
        $response->assertStatus(200);
        $this->assertTrue(session()->has('unauthorized_user'));
        $this->assertTrue($unAuthenticatedUser->forceDelete());
        $this->assertTrue($authenticatedUser->forceDelete());
    }
}
