<?php

namespace Tests\Unit;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AssetPolicyTest extends TestCase
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

    public function createUserWithManagerRole()
    {
        $user['employeeRole'] = $this->createEmployeeRole();
        $user['manager'] = factory(Employee::class)->create();
        $user['managerRole'] = $this->createManagerRole($user['employeeRole']->id);
        $user['manager']->assignRole($user['managerRole']->name);
        $user['employee'] = factory(Employee::class)->create(['manager_id' => $user['manager']->id]);
        return $user;
    }

    /**
     * @return mixed
     */
    public function createUserWithEmployeeRole()
    {
        $employee['employee'] = factory(Employee::class)->create();
        $employee['role'] = $this->createEmployeeRole();
        $employee['employee']->assignRole($employee['role']->name);
        return $employee;
    }

    /**
     * @return mixed
     */
    public function createEmployeeRole()
    {
        $employeeRole = factory(Role::class)->create(['type' => 'employee']);
        DB::table('role_permission_has_access_levels')->insert(
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view asset asset_category')->pluck('id')->first()), 'access_level_id' => 0]
        );

        return $employeeRole;
    }

    /**
     * @param $sub_role_id
     * @return mixed
     */
    public function createManagerRole($sub_role_id)
    {
        $managerRole = factory(Role::class)->create(['type' => 'manager', 'sub_role' => $sub_role_id]);
        DB::table('role_permission_has_access_levels')->insert(
            ['role_id' => $managerRole->id, 'permission_id' => (Permission::where('name', 'view asset asset_category')->pluck('id')->first()), 'access_level_id' => 1]
        );

        return $managerRole;
    }

    /**
     *
     * @return void
     */
    public function test_admin_can_view_employee_assets_for_all_employees()
    {
        $admin = $this->createUserWithAdminRole();
        $manager = $this->createUserWithManagerRole();
        $employee = $this->createUserWithEmployeeRole();
        //Access Employee Information Edit Page
        $response = $this->actingAs($admin)
            ->get('/en/employee/asset/' . $employee['employee']->id);
        $response->assertStatus(200);
        //Access Manager Information Edit Page
        $response = $this->actingAs($admin)
            ->get('/en/employee/asset/' . $manager['manager']->id);
        $response->assertStatus(200);
    }

    /**
     *
     * @return void
     */
    public function test_employee_can_view_his_own_assets_only()
    {
        $employee1 = $this->createUserWithEmployeeRole();
        $employee2 = $this->createUserWithEmployeeRole();
        $response = $this->actingAs($employee1['employee'])
            ->get('/en/employee/asset/' . $employee1['employee']->id);
        $response->assertStatus(200);
        $response = $this->actingAs($employee1['employee'])
            ->get('/en/employee/asset/' . $employee2['employee']->id);
        $response->assertStatus(403);
    }

    /**
     *
     * @return void
     */
    public function test_manager_can_view_his_own_and_his_direct_or_indirect_employees_assets_only()
    {
        //Manager has permissions to view his direct + indirect employees' info edit page
        $manager = $this->createUserWithManagerRole();
        //non-direct employee
        $employee = $this->createUserWithEmployeeRole();
        $response = $this->actingAs($manager['manager'])
            ->get('/en/employee/asset/' . $manager['manager']->id);
        $response->assertStatus(200);
        //Direct Employee's info page
        $response = $this->actingAs($manager['manager'])
            ->get('/en/employee/asset/' . $manager['employee']->id);
        $response->assertStatus(200);
        $response = $this->actingAs($manager['manager'])
            ->get('/en/employee/asset/' . $employee['employee']->id);
        $response->assertStatus(403);
    }
}
