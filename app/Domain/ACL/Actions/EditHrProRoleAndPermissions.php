<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Support\Facades\DB;

class EditHrProRoleAndPermissions
{
    /**
     * Store Custom level with permission.
     * @param $request
     * @return bool
     */
    public function execute($request, $id)
    {
        $subRole = (isset($request->hasEmployeeRole) && $request->hasEmployeeRole == 'yes' && $request->employeeRole != 'full') ?  $request->employeeRole : null;
        try {
            $role = Role::findById($id);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->sub_role = $subRole;
            DB::table('role_permission_has_access_levels')->where('role_id', $role->id)->delete();
            DB::table('custom_role_permissions')->where('role_id', $role->id)->delete();
            $customPermissionsfilter = (isset($request->customPermissions)) ? array_filter($request->customPermissions) : [];
            $fullPermissionsfilter = (isset($request->fullPermissions)) ? array_filter($request->fullPermissions) : [];
            $role->syncPermissions($customPermissionsfilter);
            $role->syncPermissions($fullPermissionsfilter);
            $role->update();
        } catch (\Throwable $th) {
            return false;
        }
        $customPermissionsArray = [
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
        ];
        $customPermissions = array_filter($customPermissionsArray);
        if (isset($customPermissions)) {
            (new GivePermissionsToHrProRole())->execute($request, $customPermissions, $role);
        }

        if (isset($request->fullPermissions)) {
            $accessLevel['access-level'] = 4;
            $role->givePermissionTo($accessLevel, $request->fullPermissions);
        }
        return true;
    }
}
