<?php

use App\Domain\ACL\Models\Role;
use App\Domain\ACL\Models\Permission;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Seeder;

class ManagerRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $managerRole = Role::create([
            'name'        => 'Manager Us',
            'guard_name'  => 'web',
            'type'        => 'manager',
            'sub_role'    => 4
        ]);

        $data = [
            'name' => 'Manager US',
            'description' => 'This access level grants users access to view some of their direct reports\' employee information.',
            'hasEmployeeRole' => 'yes',
            'employeeRole' => 4
        ];

        $subRole = (isset($data['hasEmployeeRole']) && $data['hasEmployeeRole'] == 'yes' && $data['employeeRole'] != 'full') ?  $data['employeeRole'] : null;

        $managerPermissionsArray = [
            'view employee id',
            'view employee firstname',
            'view employee lastname',
            'view employee date_of_birth',
            'view employee gender',
            'view employee nin',
            'view employee marital_status',
            'view employee current_address',
            'view employee permanent_address',
            'view employee city',
            'view employee state',
            'view employee zip_code',
            'view employee country',
            'view employee contact_no',
            'view employee personal_email',
            'view employee official_email',
            'view education institute_name',
            'view education major',
            'view educationtype education_type',
            'view education cgpa',
            'view education date_start',
            'view education date_end',
            'view secondarylanguage name',
            'view visatype visa_type',
            'view employeevisa issue_date',
            'view employeevisa country_id',
            'view employeevisa expire_date',
            'view employeevisa note',
            'view employee joining_date',
            'view employeeemploymentstatus employment_status_id',
            'view employeeemploymentstatus effective_date',
            'view employeeemploymentstatus comments',
            'view employeejob effective_date',
            'view employeejob designation_id',
            'view employeejob report_to',
            'view employeejob department_id',
            'view employeejob division_id',
            'view employeejob location_id',
            'view division name',
            'view department name',
            'view location name',
            'view employeedocument doc_name',
            'view timeofftype time_off_type_name',
            'view policy policy_name',
            'view employee emergency_contact',
            'view employee emergency_contact_relationship',
            'view asset asset_category',
            'view asset asset_description',
            'view asset serial',
            'view asset assign_date',
            'view documenttype document_type_name',
            'view resume and application',
            'view note note',
            'edit note note',
            'view onboarding tab',
            'view offboarding tab',
            'view employee_attendance',
            'edit employee_attendance',
            'manage performance review',
            'manage request time off decision',
        ];

        $managerPermissions = array_filter($managerPermissionsArray);
        
        if ($data['employeeRole'] == 4) {
            foreach ($managerPermissions as $permissions) {
                $permission = Permission::where('name', $permissions)->first();
                DB::table('role_permission_has_access_levels')
                    ->insert([
                        'role_id' => $managerRole->id,
                        'permission_id' => $permission->id,
                        'access_level_id' => 1
                    ]);
            }
        }

        // $manager = Employee::where('official_email', 'manager@hr.com')->first();
        // $manager->assignRole('Manager US');
    }
}
