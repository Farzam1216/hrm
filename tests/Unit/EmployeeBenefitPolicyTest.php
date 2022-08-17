<?php

namespace Tests\Unit;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployeeBenefitPolicyTest extends TestCase
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

    public function createUserWithEmployeeRole()
    {
        $employee['employee'] = factory(Employee::class)->create();
        $employee['role'] = $this->createEmployeeRole();
        $employee['employee']->assignRole($employee['role']->name);
        return $employee;
    }

    public function createEmployeeRole()
    {
        $employeeRole = factory(Role::class)->create(['type' => 'employee']);
        DB::table('role_permission_has_access_levels')->insert([
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view benefitgroup name')->pluck('id')->first()), 'access_level_id' => 0],
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view employeebenefit company_payment')->pluck('id')->first()), 'access_level_id' => 0]
        ]);

        return $employeeRole;
    }

    public function createManagerRole($sub_role_id)
    {
        $managerRole = factory(Role::class)->create(['type' => 'manager', 'sub_role' => $sub_role_id]);
        /*DB::table('role_permission_has_access_levels')->insert([
            ['role_id' => $managerRole->id, 'permission_id' => (Permission::where('name', 'view benefitgroup name')->pluck('id')->first()), 'access_level_id' => 1],
            ['role_id' => $managerRole->id, 'permission_id' => (Permission::where('name', 'view employee benefits history')->pluck('id')->first()), 'access_level_id' => 1]
                ]
        );*/

        return $managerRole;
    }

    /**
     *
     * @return void
     */
    public function test_admin_can_view_benefits_for_all_employees()
    {
        $admin = $this->createUserWithAdminRole();
        $manager = $this->createUserWithManagerRole();
        $employee = $this->createUserWithEmployeeRole();
        //Access Employee Information Edit Page
        $response = $this->actingAs($admin)
            ->get('/en/employees/' . $employee['employee']->id . '/benefit-details');
        $response->assertStatus(200);
        //Access Manager Information Edit Page
        $response = $this->actingAs($admin)
            ->get('/en/employees/' . $manager['manager']->id . '/benefit-details');
        $response->assertStatus(200);
    }

    /**
     *
     * @return void
     */
    public function test_employee_can_view_his_own_benefits_only()
    {
        $employee1 = $this->createUserWithEmployeeRole();
        $employee2 = $this->createUserWithEmployeeRole();
        $response = $this->actingAs($employee1['employee'])
            ->get('/en/employees/' . $employee1['employee']->id . '/benefit-details');
        $response->assertStatus(200);
        $response = $this->actingAs($employee1['employee'])
            ->get('/en/employees/' . $employee2['employee']->id . '/benefit-details');
        $response->assertStatus(403);
    }

    /**
     *
     * @return void
     */
    public function test_manager_cannot_view_his_direct_or_indirect_employees_benefits()
    {
        //Manager has no permissions to view his direct + indirect employees' benefits
        $manager = $this->createUserWithManagerRole();
        $response = $this->actingAs($manager['manager'])
            ->get('/en/employees/' . $manager['manager']->id . '/benefit-details');
        $response->assertStatus(200);
        //Direct Employee's benefits page
        $response = $this->actingAs($manager['manager'])
            ->get('/en/employees/' . $manager['employee']->id . '/benefit-details');
        $response->assertStatus(403);
    }
}
