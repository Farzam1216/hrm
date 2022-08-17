<?php

namespace Tests\Unit;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TimeOffTypePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function createUserWithAdminRole()
    {
        $admin = factory(Employee::class)->create();
        $admin->assignRole('admin');
        return $admin;
    }

    public function createUserWithCustomRole()
    {
        $user['employeeRole'] = $this->createEmployeeRole();
        $user['custom'] = factory(Employee::class)->create();
        //Create custom role with sub-role for his personal information and assign full permission to manage PTO (request time off, change policy etc).
        $user['customRole'] = $this->createCustomRole($user['employeeRole']->id);
        $user['custom']->assignRole($user['customRole']->name);
        return $user;
    }

    /**
     *
     * add new employee in system with employee type role
     * @return mixed
     *
     */

    public function createUserWithEmployeeRole()
    {
        $employee['employee'] = factory(Employee::class)->create();
        $employee['role'] = $this->createEmployeeRole();
        $employee['employee']->assignRole($employee['role']->name);
        return $employee;
    }

    /**
     *
     * Create employee role without any time off related permissions
     * @return mixed
     *
     */

    public function createEmployeeRole()
    {
        $employeeRole = factory(Role::class)->create(['type' => 'employee']);
        DB::table('role_permission_has_access_levels')->insert(
            [
                ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view employee firstname')->pluck('id')->first()), 'access_level_id' => 0],
                ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view employee lastname')->pluck('id')->first()), 'access_level_id' => 0]
            ]
        );

        return $employeeRole;
    }

    /**
     *
     * Create custom role with "manage time off" permission for all employees
     * @param $sub_role_id
     * @return mixed
     */

    public function createCustomRole($sub_role_id)
    {
        $customRole = factory(Role::class)->create(['type' => 'custom', 'sub_role' => $sub_role_id]);
        DB::table('role_permission_has_access_levels')->insert(
            [
                ['role_id' => $customRole->id, 'permission_id' => (Permission::where('name', 'view employee firstname')->pluck('id')->first()), 'access_level_id' => 4],
                ['role_id' => $customRole->id, 'permission_id' => (Permission::where('name', 'view employee lastname')->pluck('id')->first()), 'access_level_id' => 4],
                ['role_id' => $customRole->id, 'permission_id' => (Permission::where('name', 'manage employees PTO')->pluck('id')->first()), 'access_level_id' => 4]
            ]
        );

        return $customRole;
    }

    /**
     *
     * @return void
     */
    public function test_admin_can_view_employee_timeoff_information_page_for_all_employees()
    {
        $this->withoutExceptionHandling();
        $admin = $this->createUserWithAdminRole();
        $custom = $this->createUserWithCustomRole();
        $employee = $this->createUserWithEmployeeRole();
        //Access Employee Time Off
        $response = $this->actingAs($admin)
            ->get('en/employees/' . $employee['employee']->id . '/time-off');
        $response->assertStatus(200);
        //Access Custom User Time Off
        $response = $this->actingAs($admin)
            ->get('en/employees/' . $custom['custom']->id . '/time-off');
        $response->assertStatus(200);
    }

    /**
     *
     * Employee with Custom Type role having "manage employees PTO" permission can view Time Off page of all employees except his own
     * @return void
     */
    public function test_custom_user_can_view_employee_timeoff_information_page_for_all_employees_except_his()
    {
        $employee = $this->createUserWithEmployeeRole();
        $custom = $this->createUserWithCustomRole();
        $response = $this->actingAs($custom['custom'])
            ->get('en/employees/' . $custom['custom']->id . '/time-off')->assertStatus(403);
        $response = $this->actingAs($custom['custom'])
            ->get('en/employees/' . $employee['employee']->id . '/time-off');
        $response->assertStatus(200);
    }
}
