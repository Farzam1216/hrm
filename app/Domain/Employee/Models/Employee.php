<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use App\Domain\Compensation\Models\Compensation;
use App\Domain\Employee\Models\EmployeeHoliday;
use App\Domain\PayRoll\Models\PayScheduleAssign;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaireAssign;
use App\Domain\PerformanceReview\Models\PerformanceFormAssign;
use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\Approval\Models\ApprovalRequestedDataField;
use App\Domain\Benefit\Models\BenefitGroupEmployee;
use App\Domain\Benefit\Models\BenefitGroupPlan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    protected $guard_name = 'web';

    protected $dates = ['deleted_at'];
    protected $appends = ['full_name'];

    protected $fillable = [
        'employee_no',
        'firstname',
        'department_id',
        'lastname',
        'contact_no',
        'emergency_contact_relationship',
        'emergency_contact',
        'password',
        'zuid',
        'account_id',
        'official_email',
        'personal_email',
        'designation_id',
        'work_schedule_id',
        'basic_salary',
        'home_allowance',
        'status',
        'type',
        'employment_status_id',
        'education_id',
        'picture',
        'exit_date',
        'bonus',
        'invite_to_zoho',
        'invite_to_slack',
        'invite_to_asana',
        'nin',
        'date_of_birth',
        'current_address',
        'permanent_address',
        'city',
        'joining_date',
        'exit_date',
        'location_id',
        'deleted_at',
        'created_at',
        'updated_at',
        'gender',
        'marital_status',
        'manager_id',
        'can_mark_attendance',
    ];

    public function manager($manager_id)
    {
        return Employee::where('id', $manager_id)->first();
    }
    
    public function employeeCompensations()
    {
        return $this->hasMany(Compensation::class, 'employee_id');
    }
    
    public function employeeHolidays()
    {
        return $this->hasMany(EmployeeHoliday::class, 'employee_id');
    }

    public function assignedPaySchedule()
    {
        return $this->belongsTo(PayScheduleAssign::class, 'id', 'employee_id');
    }

    public function assignTimeOffType()
    {
        return $this->hasMany(AssignTimeOffType::class, 'employee_id');
    }

    public function jobs()
    {
        return $this->hasMany(EmployeeJob::class, 'employee_id');
    }

    public function performance_assigned()
    {
        return $this->hasMany(PerformanceQuestionnaireAssign::class, 'employee_id');
    }

    public function assignedForm()
    {
        return $this->belongsTo(PerformanceFormAssign::class, 'id', 'employee_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function Location()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Location', 'location_id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Designation', 'designation_id');
    }

    public function attendanceSummary()
    {
        return $this->hasMany('App\Models\Attendence\AttendanceSummary')->latest();
    }

    public function attendanceSummaries()
    {
        return $this->hasMany('App\Models\Attendence\AttendanceSummary');
    }

    public function employeeAttendance()
    {
        return $this->hasMany('App\Domain\Attendance\Models\EmployeeAttendance');
    }

    public function employeeWorkSchedule()
    {
        return $this->belongsTo('App\Domain\Attendance\Models\WorkSchedule', 'work_schedule_id');
    }

    public function leaveTypes()
    {
        return $this->belongsToMany('App\Domain\TimeOff\Models\LeaveType')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function directEmployees()
    {
        return Employee::where('manager_id', $this->id)->get();
    }

    public function indirectEmployees()
    {
        $directEmployees = Employee::where('manager_id', $this->id)->get();
        $indirectEmployees = new Collection();
        if ($directEmployees->isNotEmpty()) {
            foreach ($directEmployees as $directEmployee) {
                $indirectEmployee = Employee::where('manager_id', $directEmployee->id)->get();
                $indirectEmployees = $indirectEmployees->merge($indirectEmployee);
            }
        }

        return $indirectEmployees;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }


    public function isEmployeeOnly()
    {
        $roles = $this->roles()->get();

        //If user is not an "employee" he might have a sub_role (employee) also
        if ($roles[0]->type == 'employee') {
            return true;
        }

        return false;
    }

    public function isNotAdmin()
    {
        return !($this->hasRole('admin'));
    }

    public function isAllowed()
    {
        return true;
    }

    public function department()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Department', 'department_id');
    }

    public function leaves()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\Leave', 'employee_id', 'id');
    }

    public function bankAccount()
    {
        return $this->hasOne('App\Domain\Employee\Models\EmployeeBankAccount', 'employee_id');
    }

    public function salaryTemplate()
    {
        return $this->belongsTo('App\Models\Payroll\SalaryTemplate', 'salary_template');
    }

    public function employmentStatus()
    {
        return $this->belongsTo('App\Domain\Employee\Models\EmploymentStatus', 'employment_status_id');
    }

    public function employment_status()
    {
        return $this->hasMany('App\Domain\Employee\Models\EmployeeEmploymentStatus', 'employee_id', 'id');
    }

    public function Education()
    {
        return $this->hasMany('App\Domain\Employee\Models\Education', 'employee_id', 'id');
    }

    public function Visa()
    {
        return $this->hasMany('App\Domain\Employee\Models\EmployeeVisa', 'employee_id', 'id');
    }

    public function employee()
    {
        return $this->hasMany('App\Domain\Task\Models\TaskAssign', 'employee_id');
    }

    public function selectedEmployee()
    {
        return $this->hasMany('App\Domain\Task\Models\Task', 'selected_employee');
    }

    public function assignedBy()
    {
        return $this->hasMany('App\Domain\Task\Models\EmployeeTask', 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->hasMany('App\Domain\Task\Models\EmployeeTask', 'assigned_to');
    }

    public function assignedFor()
    {
        return $this->hasMany('App\Domain\Task\Models\EmployeeTask', 'assigned_for');
    }

    public function timeofftypes()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\AssignTimeOffType', 'employee_id');
    }

    public function candidateEmail()
    {
        return $this->hasMany('App\Models\Hiring\CandidateEmail', 'email_from');
    }

    public function employeeInBenefitGroup()
    {
        return $this->hasOne(BenefitGroupEmployee::class, 'employee_id');
    }

    public function benefitGroupPlan()
    {
        return $this->belongsToMany(BenefitGroupPlan::class, 'employee_benefits', 'employee_id', 'benefit_group_plan_id')->withPivot('employee_benefit_plan_coverage', 'deduction_frequency', 'employee_payment', 'company_payment');
    }

    public function employeeManager()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'manager_id');
    }

    public function employeeEmploymentStatus()
    {
        return $this->hasMany('App\Domain\Employee\Models\EmployeeEmploymentStatus', 'employee_id');
    }

    //Custom method to get all default permissions related to Employee Model
    public static function getDefaultPermissions($roleType, $employee)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employee id') !== false) {
                $employee['personal']['basic_info']['Employee_ID'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee firstname') !== false) {
                $employee['personal']['basic_info']['first_name'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee lastname') !== false) {
                $employee['personal']['basic_info']['last_name'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee nin') !== false) {
                $employee['personal']['basic_info']['NIN'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee joining_date') !== false) {
                $employee['job']['no_group']['joining_date'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee date_of_birth') !== false) {
                $employee['personal']['basic_info']['date_of_birth'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee gender') !== false) {
                $employee['personal']['basic_info']['gender'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee marital_status') !== false) {
                $employee['personal']['basic_info']['marital_status'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee contact_no') !== false) {
                $employee['personal']['contact']['contact'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee personal_email') !== false) {
                $employee['personal']['contact']['personal_email'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee official_email') !== false) {
                $employee['personal']['contact']['official_email'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee current_address') !== false) {
                $employee['personal']['address']['current_address'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee permanent_address') !== false) {
                $employee['personal']['address']['permanent_address'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee city') !== false) {
                $employee['personal']['address']['city'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee emergency_contact_relationship') !== false) {
                $employee['emergency']['no_group']['emergency_contact_relationship'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee emergency_contact') !== false) {
                $employee['emergency']['no_group']['emergency_contact'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee reports_to') !== false) {
                $employee['job']['no_group']['reports_to'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee designation') !== false) {
                $employee['job']['no_group']['designation'][] = $permission->name;
            } elseif (stripos($permission->name, 'employee_attendance') !== false) {
                if (!isset($employee['attendance']['checkbox']['can_view_employee_attendance']['can_mark_employee_attendance'][0])) {
                    $employee['attendance']['checkbox']['can_view_employee_attendance']['can_mark_employee_attendance'][] = $permission->name;
                }
            }
        }
        return $employee;
    }

    /**
     *  Get permissions of logged-in user related to Employee Model.
     *
     * @param $user
     *
     * @return mixed|null $employee
     */
    public static function getPermissionsWithAccessLevel($user)
    {
        $employee = null;
        $roles = $user->roles()->get();
        foreach ($roles as $role) {
            $roleWithSubRole[] = $role;
            //If user is not an "employee" he might have a sub_role (employee) also
            if ($role->type != 'employee') {
                if (isset($role->sub_role)) {
                    $roleWithSubRole[] = Role::where('id', $role->sub_role)->first();
                }
            }
            foreach ($roleWithSubRole as $role) {
                $permissions = $role->permissions()->get();
                foreach ($permissions as $permission) {
                    $employee = (new Employee())->groupPermissions($role, $permission, $employee);
                    //Non-field permissions
                    if (stripos($permission->name, 'manage employees store') !== false) {
                        $employee[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    if (stripos($permission->name, 'manage employees change_photos') !== false) {
                        $employee[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    if (stripos($permission->name, 'manage employees work_schedule') !== false) {
                        $employee[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employee;
    }

    /**
     *  Group Permissions depending upon model fields.
     *
     * @param $role
     * @param $permission
     * @param $employee
     *
     * @return mixed
     */
    public function groupPermissions($role, $permission, $employee)
    {
        foreach ((new Employee())->fillable as $field) {
            if (stripos($permission->name, 'employee ' . $field) !== false) {
                $employee[$role->id][$permission->pivot->access_level_id][] = $permission->name;
            }
        }

        return $employee;
    }

    public function approvalRequestedByEmployee()
    {
        $this->belongsTo(ApprovalRequestedDataField::class, 'requested_by_id');
    }

    public function approvalRequestedForEmployee()
    {
        $this->belongsTo(ApprovalRequestedDataField::class, 'requested_for_id');
    }

    public function syncRoles(array $roles)
    {
        $this->roles()->sync($this->getStoredRoleIds($roles));
    }

    protected function getStoredRoleIds($roles)
    {
        $ids = [];
        foreach ($roles as $role) {
            $ids[] = $this->getRoleId($role);
        }

        return $ids;
    }

    private function getRoleId($role)
    {
        if (is_string($role)) {
            return app(Role::class)->findByName($role)->id;
        }

        return $role->id;
    }

    public static function getSelectedPermissions($roleType, $employee)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employee id') !== false) {
                $employee['personal']['basic_info']['Employee_ID']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee firstname') !== false) {
                $employee['personal']['basic_info']['first_name']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee lastname') !== false) {
                $employee['personal']['basic_info']['last_name']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee nin') !== false) {
                $employee['personal']['basic_info']['NIN']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee joining_date') !== false) {
                $employee['job']['no_group']['joining_date']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee date_of_birth') !== false) {
                $employee['personal']['basic_info']['date_of_birth']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee gender') !== false) {
                $employee['personal']['basic_info']['gender']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee marital_status') !== false) {
                $employee['personal']['basic_info']['marital_status']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee contact_no') !== false) {
                $employee['personal']['contact']['contact']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee personal_email') !== false) {
                $employee['personal']['contact']['personal_email']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee official_email') !== false) {
                $employee['personal']['contact']['official_email']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee current_address') !== false) {
                $employee['personal']['address']['current_address']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee permanent_address') !== false) {
                $employee['personal']['address']['permanent_address']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee city') !== false) {
                $employee['personal']['address']['city']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee emergency_contact_relationship') !== false) {
                $employee['emergency']['no_group']['emergency_contact_relationship']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee emergency_contact') !== false) {
                $employee['emergency']['no_group']['emergency_contact']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee reports_to') !== false) {
                $employee['job']['no_group']['reports_to']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee designation') !== false) {
                $employee['job']['no_group']['designation']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employee_attendance') !== false) {
                if (!isset($employee['attendance']['checkbox']['can_view_employee_attendance']['can_mark_employee_attendance'][0])) {
                    $employee['attendance']['checkbox']['can_view_employee_attendance']['can_mark_employee_attendance']['checked'] = $permission->name;
                }
            }
        }
        return $employee;
    }

    public static function sortPermissionKeys(&$defaultPermissions)
    {
        if (isset(
            $defaultPermissions['personal']['basic_info'],
            $defaultPermissions['personal']['contact'],
            $defaultPermissions['personal']['address'],
            $defaultPermissions['job']['no_group'],
            $defaultPermissions['job']['employment_status'],
            $defaultPermissions['job']['job_information'],
            $defaultPermissions['emergency']['no_group']
        )) {
            $employee = $defaultPermissions['personal'];
            $job = $defaultPermissions['job'];
            $emergency = $defaultPermissions['emergency'];
            $sortedPermissions = [];
            !array_key_exists('Employee_ID', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['Employee_ID'] = $employee['basic_info']['Employee_ID'];
            !array_key_exists('first_name', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['first_name'] = $employee['basic_info']['first_name'];
            !array_key_exists('last_name', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['last_name'] = $employee['basic_info']['last_name'];
            !array_key_exists('nin', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['NIN'] = $employee['basic_info']['NIN'];
            !array_key_exists('date_of_birth', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['date_of_birth'] = $employee['basic_info']['date_of_birth'];
            !array_key_exists('gender', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['gender'] = $employee['basic_info']['gender'];
            !array_key_exists('marital_status', $employee['basic_info']) ?: $sortedPermissions['personal']['basic_info']['marital_status'] = $employee['basic_info']['marital_status'];
            !array_key_exists('contact', $employee['contact']) ?: $sortedPermissions['personal']['contact']['contact'] = $employee['contact']['contact'];
            !array_key_exists('personal_email', $employee['contact']) ?: $sortedPermissions['personal']['contact']['personal_email'] = $employee['contact']['personal_email'];
            !array_key_exists('official_email', $employee['contact']) ?: $sortedPermissions['personal']['contact']['official_email'] = $employee['contact']['official_email'];
            !array_key_exists('current_address', $employee['address']) ?: $sortedPermissions['personal']['address']['current_address'] = $employee['address']['current_address'];
            !array_key_exists('permanent_address', $employee['address']) ?: $sortedPermissions['personal']['address']['permanent_address'] = $employee['address']['permanent_address'];
            !array_key_exists('city', $employee['address']) ?: $sortedPermissions['personal']['address']['city'] = $employee['address']['city'];
            !array_key_exists('joining_date', $job['no_group']) ?: $sortedPermissions['job']['no_group']['joining_date'] = $job['no_group']['joining_date'];
            !array_key_exists('date', $job['job_information']) ?: $sortedPermissions['job']['job_information']['date'] = $job['job_information']['date'];
            !array_key_exists('designation', $job['job_information']) ?: $sortedPermissions['job']['job_information']['designation'] = $job['job_information']['designation'];
            !array_key_exists('reporting_to', $job['job_information']) ?: $sortedPermissions['job']['job_information']['reporting_to'] = $job['job_information']['reporting_to'];
            !array_key_exists('department', $job['job_information']) ?: $sortedPermissions['job']['job_information']['department'] = $job['job_information']['department'];
            !array_key_exists('division', $job['job_information']) ?: $sortedPermissions['job']['job_information']['division'] = $job['job_information']['division'];
            !array_key_exists('location', $job['job_information']) ?: $sortedPermissions['job']['job_information']['location'] = $job['job_information']['location'];
            !array_key_exists('employment_status', $job['employment_status']) ?: $sortedPermissions['job']['employment_status']['employment_status'] = $job['employment_status']['employment_status'];
            !array_key_exists('effective_date', $job['employment_status']) ?: $sortedPermissions['job']['employment_status']['effective_date'] = $job['employment_status']['effective_date'];
            !array_key_exists('comments', $job['employment_status']) ?: $sortedPermissions['job']['employment_status']['comments'] = $job['employment_status']['comments'];
            !array_key_exists('emergency_contact_relationship', $emergency['no_group']) ?: $sortedPermissions['emergency']['no_group']['emergency_contact_relationship'] = $emergency['no_group']['emergency_contact_relationship'];
            !array_key_exists('emergency_contact', $emergency['no_group']) ?: $sortedPermissions['emergency']['no_group']['emergency_contact'] = $emergency['no_group']['emergency_contact'];
            $defaultPermissions['personal']['basic_info'] = $sortedPermissions['personal']['basic_info'];
            $defaultPermissions['personal']['contact'] = $sortedPermissions['personal']['contact'];
            $defaultPermissions['personal']['address'] = $sortedPermissions['personal']['address'];
            $defaultPermissions['job']['no_group'] = $sortedPermissions['job']['no_group'];
            $defaultPermissions['emergency']['no_group'] = $sortedPermissions['emergency']['no_group'];
        }
        return $defaultPermissions;
    }

    public function getEmployeeManagerOfManager()
    {
        return Employee::find((Employee::find($this->manager_id))->manager_id);
    }

    public function getEmployeeManager()
    {
        return Employee::find($this->manager_id);
    }
}
