<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Permission;

class GetFullAccessPermissionsForHrProRole
{
    /**
     * Get full access permissions.
     *
     * @return array $fullPermissions
     */
    public function execute()
    {
        $permissions = Permission::where('name', 'LIKE', 'manage%')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employees store') !== false) {
                $settings['employee']['add_new_employees'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees terminate') !== false) {
                $settings['employee']['terminate_employees'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees work_schedule') !== false) {
                $settings['employee']['manage_work_schedule'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees attendance') !== false) {
                $settings['employee']['manage_attendance'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees change_photos') !== false) {
                $settings['employee']['change_employee Photos'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees PTO') !== false) {
                $settings['employee']['manage_time_off_policy_assignments'][] = $permission->name;
            } elseif (stripos($permission->name, 'hiring jobopening_candidates') !== false) {
                $settings['hiring']['manage_job_openings_and_candidates'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting employee_accesslevel') !== false) {
                $settings['settings']['manage_employee_access_levels'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting approval') !== false) {
                $settings['settings']['approvals'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting benefit') !== false) {
                $settings['settings']['benefits'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting company_fields') !== false) {
                $settings['settings']['company_field_settings'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting company_holiday') !== false) {
                $settings['settings']['company_hollidays'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting email_alert') !== false) {
                $settings['settings']['email_alerts'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting hiring') !== false) {
                $settings['settings']['hiring'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting logo_and_colors') !== false) {
                $settings['settings']['logo_and_color'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting offboarding') !== false) {
                $settings['settings']['offboarding'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting onboarding') !== false) {
                $settings['settings']['onboarding'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting time_off') !== false) {
                $settings['settings']['time_off'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting training') !== false) {
                $settings['settings']['training'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting handbook') !== false) {
                $settings['settings']['handbook'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting poll') !== false) {
                $settings['settings']['poll'][] = $permission->name;
            } elseif ($permission->name == 'manage setting performance review') {
                $settings['settings']['performance review'][] = $permission->name;
            } elseif ($permission->name == 'manage setting performance review assign') {
                $settings['settings']['performance review assign'][] = $permission->name;
            } elseif ($permission->name == 'manage setting performance review decision') {
                $settings['settings']['performance review decision'][] = $permission->name;
            }
        }
        return $settings;
    }
}
