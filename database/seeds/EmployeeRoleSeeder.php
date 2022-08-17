<?php

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeRole = Role::create([
            'name'       => 'Employee US',
            'guard_name' => 'web',
            'type'       => 'employee',
            'description' => 'Basic Employee Role',
            'sub_role'   => null,
        ]);

        $employeePermissions = [
            'view employee id',
            'view employee firstname',
            'view employee lastname',
            'edit_with_approval employee date_of_birth',
            'edit_with_approval employee gender',
            'view employee nin',
            'edit_with_approval employee current_address',
            'edit_with_approval employee permanent_address',
            'edit employee city',
            'edit_with_approval employee state',
            'view employee zip_code',
            'edit employee country',
            'view employee contact_no',
            'edit_with_approval employee personal_email',
            'edit_with_approval employee official_email',
            'edit_with_approval education institute_name',
            'edit_with_approval education major',
            'edit_with_approval educationtype education_type',
            'edit_with_approval education cgpa',
            'edit_with_approval education date_start',
            'edit_with_approval education date_end',
            'edit_with_approval secondarylanguage name',
            'view visatype visa_type',
            'view employeevisa issue_date',
            'view employeevisa country_id',
            'view employeevisa expire_date',
            'view employee joining_date',
            'view employeejob effective_date',
            'view employeejob designation_id',
            'view employeejob report_to',
            'view division name',
            'view department name',
            'view location name',
            'view employeeemploymentstatus employment_status_id',
            'view employeeemploymentstatus comments',
            //            'can request time off' ,
            //            'view time_off_type time_off_type_name' ,
            'view policy policy_name',
            'edit employee emergency_contact_relationship',
            'view employeebenefit company_payment',
            'view benefitgroup name',
            'view employee benefits history',
            'edit_with_approval employeedependent first_name',
            'edit_with_approval employeedependent last_name',
            'edit_with_approval employeedependent middle_name',
            'edit_with_approval employeedependent date_of_birth',
            'edit_with_approval employeedependent SSN',
            'edit_with_approval employeedependent SIN',
            'edit_with_approval employeedependent gender',
            'edit_with_approval employeedependent relationship',
            'edit_with_approval employeedependent street1',
            'edit_with_approval employeedependent street2',
            'edit_with_approval employeedependent city',
            'edit_with_approval employeedependent state',
            'edit_with_approval employeedependent zip',
            'edit_with_approval employeedependent country',
            'edit_with_approval employeedependent home_phone',
            'edit_with_approval employeedependent is_us_citizen',
            'edit_with_approval employeedependent is_student',
            'edit_with_approval employeedependent home_phone',
            'edit_with_approval asset asset_category',
            'edit_with_approval asset asset_description',
            'edit_with_approval asset serial',
            'edit_with_approval asset assign_date',
            'view employee_attendance',
            'edit employee_attendance',
        ];
        foreach ($employeePermissions as $permission) {
            $employeeRole->givePermissionTo(null, $permission);
        }
        // $employee = Employee::find(2);
        // $employee->assignRole($employeeRole);
    }
}
