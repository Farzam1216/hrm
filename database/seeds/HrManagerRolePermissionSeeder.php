<?php

use App\Domain\ACL\Models\Role;
use App\Domain\ACL\Models\Permission;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Seeder;

class HrManagerRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $hrManagerRole = Role::create([
            'name'        => 'Hr Manager',
            'guard_name'  => 'web',
            'type'        => 'custom',
            'sub_role'    => 4
        ]);

        $data = [
            'name' => 'Hr Manager',
            'description' => 'This access level allows employees to view, edit all of the employees information.',
            'hasEmployeeRole' => 'yes',
            'employeeRole' => 'full'
        ];

        $subRole = (isset($data['hasEmployeeRole']) && $data['hasEmployeeRole'] == 'yes' && $data['employeeRole'] != 'full') ?  $data['employeeRole'] : null;

        $hrProPermissionsArray = [
            'edit employee id',
            'edit employee firstname',
            'edit employee lastname',
            'edit employee date_of_birth',
            'edit employee gender',
            'edit employee nin',
            'edit employee marital_status',
            'edit employee current_address',
            'edit employee permanent_address',
            'edit employee city',
            'edit employee contact_no',
            'edit employee personal_email',
            'edit employee official_email',
            'edit education institute_name',
            'edit education major',
            'edit education cgpa',
            'edit education date_start',
            'edit education date_end',
            'edit educationtype education_type',
            'edit visatype visa_type',
            'edit employeevisa issue_date',
            'edit employeevisa country_id',
            'edit employeevisa expire_date',
            'edit employeevisa note',
            'edit employee joining_date',
            'edit employeeemploymentstatus employment_status_id',
            'edit employeejob effective_date',
            'edit employeejob designation_id',
            'edit employeejob department_id',
            'edit employeejob division_id',
            'edit employeejob location_id',
            'edit employee emergency_contact',
            'edit employee emergency_contact_relationship',
            'edit employee benefits history',
            'edit benefitgroup name',
            'edit employeedependent first_name',
            'edit employeedependent last_name',
            'edit employeedependent middle_name',
            'edit employeedependent date_of_birth',
            'edit employeedependent SSN',
            'edit employeedependent SIN',
            'edit employeedependent gender',
            'edit employeedependent relationship',
            'edit employeedependent street1',
            'edit employeedependent street2',
            'edit employeedependent city',
            'edit employeedependent state',
            'edit employeedependent zip',
            'edit employeedependent country',
            'edit employeedependent home_phone',
            'edit employeedependent is_us_citizen',
            'edit employeedependent is_student',
            'edit asset asset_category',
            'edit asset asset_description',
            'edit asset serial',
            'edit asset assign_date',
            'edit employeedocument doc_name',
            'edit note note',
            'edit onboarding tab',
            'edit employeeemploymentstatus comments',
            'edit offboarding tab',
            'edit employeeemploymentstatus effective_date',
            'manage employees store',
            'manage employees terminate',
            'manage employees change_photos',
            'manage employees PTO',
            'manage employees work_schedule',
            'manage employees attendance',
            'manage hiring jobopening_candidates',
            'manage setting employee_accesslevel',
            'manage setting approval',
            'manage setting benefit',
            'manage setting company_fields',
            'manage company holidays',
            'manage setting email_alert',
            'manage setting hiring',
            'manage setting logo_and_colors',
            'manage setting offboarding',
            'manage setting onboarding',
            'manage setting time_off',
            'manage setting training',
            'manage company handbook',
            'manage poll',
            'manage performance review',
            'manage performance review assign',
            'manage performance review decision',
            'manage payroll management',
            'manage pay schedule',
            'manage setting education type',
            'manage setting asset type',
            'manage setting visa type',
            'manage setting document',
            'manage setting document type',
            'manage setting location',
            'manage setting department',
            'manage setting employment status',
            'manage setting designation',
            'manage setting division',
            'manage setting language',
            'manage setting secondary language',
            'manage setting smtp details',
            'view employee_attendance',
            'edit employee_attendance',
            'manage setting compensation',
        ];

        $hrProPermissions = array_filter($hrProPermissionsArray);

        if ($data['employeeRole'] == 'full') {
            foreach ($hrProPermissions as $permissions) {
                $permission = Permission::where('name', $permissions)->first();
                DB::table('role_permission_has_access_levels')
                    ->insert([
                        'role_id' => $hrManagerRole->id,
                        'permission_id' => $permission->id,
                        'access_level_id' => 4
                    ]);
            }
        }

        // $hrManager = Employee::where('official_email', 'hrmanager@hr.com')->first();
        // $hrManager->assignRole('Hr Manager');
    }
}
