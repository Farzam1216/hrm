<?php

namespace Tests\Unit;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployeeDependentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Full Admin
     * @return mixed
     */
    public function createUserWithAdminRole()
    {
        $admin = factory(Employee::class)->create();
        $admin->assignRole('admin');
        return $admin;
    }

    /**
     *
     * A user with edit first and last name of dependents can create new dependents
     * @return mixed
     */
    public function createEmployeeRoleWithCreateDependentPermissions()
    {
        $employeeRole = factory(Role::class)->create(['type' => 'employee']);
        DB::table('role_permission_has_access_levels')->insert([
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'edit employeedependent first_name')->pluck('id')->first()), 'access_level_id' => 0],
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'edit employeedependent last_name')->pluck('id')->first()), 'access_level_id' => 0],
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view employeedependent middle_name')->pluck('id')->first()), 'access_level_id' => 0],
        ]);

        return $employeeRole;
    }

    /**
     * A user with any of the edit dependent permissions can edit dependent
     * @return mixed
     */
    public function createEmployeeRoleWithEditDependentPermissions()
    {
        $employeeRole = factory(Role::class)->create(['type' => 'employee']);
        DB::table('role_permission_has_access_levels')->insert([
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view employeedependent first_name')->pluck('id')->first()), 'access_level_id' => 0],
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'edit employeedependent last_name')->pluck('id')->first()), 'access_level_id' => 0],
            ['role_id' => $employeeRole->id, 'permission_id' => (Permission::where('name', 'view employeedependent middle_name')->pluck('id')->first()), 'access_level_id' => 0],
        ]);

        return $employeeRole;
    }

    /**
     * @return mixed
     */
    public function createEmployeeWithCreatePermissions()
    {
        $employee['employee'] = factory(Employee::class)->create();
        $employee['role'] = $this->createEmployeeRoleWithCreateDependentPermissions();
        $employee['employee']->assignRole($employee['role']->name);
        $employee['dependent'] = $this->createEmployeeDependents($employee['employee']->id);
        return $employee;
    }
    /**
     * @return mixed
     */
    public function createEmployeeWithEditPermissions()
    {
        $employee['employee'] = factory(Employee::class)->create();
        $employee['role'] = $this->createEmployeeRoleWithEditDependentPermissions();
        $employee['employee']->assignRole($employee['role']->name);
        $employee['dependent'] = $this->createEmployeeDependents($employee['employee']->id);
        return $employee;
    }

    /**
     *  Admin can view all employees' dependents
     */
    public function test_admin_can_view_dependents_of_all_employees()
    {
        $admin = $this->createUserWithAdminRole();
        $employee1 = $this->createEmployeeWithCreatePermissions();
        $response = $this->actingAs($admin)
            ->get('/en/employees/' . $employee1['employee']->id . '/dependents');
        $response->assertStatus(200);
    }

    /**
     * Admin can access create/edit dependents page for all Employees
     */
    public function test_admin_can_create_and_edit_dependents_for_all_employees()
    {
        $this->withoutExceptionHandling();
        $admin = $this->createUserWithAdminRole();
        $employee1 = $this->createEmployeeWithCreatePermissions();
        $response = $this->actingAs($admin)
            ->get('/en/employees/' . $employee1['employee']->id . '/dependents/create');
        $response->assertStatus(200);
        $response = $this->actingAs($admin)
            ->get('/en/employees/' . $employee1['employee']->id . '/dependents/' . $employee1['dependent']->id . '/edit');
        $response->assertStatus(200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function createEmployeeDependents($id)
    {
        return factory(EmployeeDependent::class)->create(['employee_id' => $id]);
    }

    /**
     *  Employee can view his dependents
     */
    public function test_employee_can_view_his_dependents()
    {
        $employee = $this->createEmployeeWithCreatePermissions();
        $response = $this->actingAs($employee['employee'])
            ->get('/en/employees/' . $employee['employee']->id . '/dependents');
        $response->assertStatus(200);
    }
    /**
     *  Employee who can create his dependents can also edit
     */
    public function test_employee_can_create_and_edit_his_dependents()
    {
        $employee = $this->createEmployeeWithCreatePermissions();
        $response = $this->actingAs($employee['employee'])
            ->get('/en/employees/' . $employee['employee']->id . '/dependents/create');
        $response->assertStatus(200);
        $response = $this->actingAs($employee['employee'])
            ->get('/en/employees/' . $employee['employee']->id . '/dependents/' . $employee['dependent']->id . '/edit');
        $response->assertStatus(200);
    }

    /**
     *  Employee who can no permission to edit first or last name cannot create his dependents but only edit them
     */
    public function test_employee_can_edit_his_dependents_but_not_create()
    {
        $employee = $this->createEmployeeWithEditPermissions();
        $response = $this->actingAs($employee['employee'])
            ->get('/en/employees/' . $employee['employee']->id . '/dependents/create')->assertStatus(403);
        $response = $this->actingAs($employee['employee'])
            ->get('/en/employees/' . $employee['employee']->id . '/dependents/' . $employee['dependent']->id . '/edit');
        $response->assertStatus(200);
    }
}
