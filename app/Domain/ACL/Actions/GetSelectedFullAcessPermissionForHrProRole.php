<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class GetSelectedFullAcessPermissionForHrProRole
{
    /*
    * Get Custom Permissions.
    *
    * @return settings[]
    */
    public function execute($roleType)
    {
        $settings = [];
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employees store') !== false) {
                $settings['employee']['add_new_employees']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employees terminate') !== false) {
                $settings['employee']['terminate_employees']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employees change_photos') !== false) {
                $settings['employee']['change_employee Photos']['checked'] = $permission->name;
            }  elseif (stripos($permission->name, 'employees work_schedule') !== false) {
                $settings['employee']['manage_work_schedule']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employees attendance') !== false) {
                $settings['employee']['manage_attendance']['checked'] = $permission->name;
            }elseif (stripos($permission->name, 'employees PTO') !== false) {
                $settings['employee']['manage_time_off_policy_assignments']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'hiring jobopening_candidates') !== false) {
                $settings['hiring']['manage_job_openings_and_candidates']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting employee_accesslevel') !== false) {
                $settings['settings']['manage_employee_access_levels']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting approval') !== false) {
                $settings['settings']['approvals']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting benefit') !== false) {
                $settings['settings']['benefits']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting company_fields') !== false) {
                $settings['settings']['company_field_settings']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting company_holiday') !== false) {
                $settings['settings']['company_hollidays']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting email_alert') !== false) {
                $settings['settings']['email_alerts']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting hiring') !== false) {
                $settings['settings']['hiring']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting logo_and_colors') !== false) {
                $settings['settings']['logo_and_color']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting offboarding') !== false) {
                $settings['settings']['offboarding']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting onboarding') !== false) {
                $settings['settings']['onboarding']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting time_off') !== false) {
                $settings['settings']['time_off']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'setting training') !== false) {
                $settings['settings']['training']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'manage setting handbook') !== false) {
                $settings['settings']['handbook']['checked'] = $permission->name;
            }elseif (stripos($permission->name, 'manage setting poll') !== false) {
                $settings['settings']['poll']['checked'] = $permission->name;
            } elseif ($permission->name == 'manage setting performance review') {
                $settings['settings']['performance review']['checked'] = $permission->name;
            } elseif ($permission->name == 'manage setting performance review assign') {
                $settings['settings']['performance review assign']['checked'] = $permission->name;
            } elseif ($permission->name == 'manage setting performance review decision') {
                $settings['settings']['performance review decision']['checked'] = $permission->name;
            }

        }
        return $settings;
    }
}
