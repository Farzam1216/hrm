<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Permission;

class GetFullAccessPermissionsForCustomRole
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
            } elseif (stripos($permission->name, 'setting employee_accesslevel') !== false) {
                $settings['employee']['manage_employee_access_levels'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees work_schedule') !== false) {
                $settings['employee']['manage_work_schedule'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees attendance') !== false) {
                $settings['employee']['manage_attendance'][] = $permission->name;
            } elseif (stripos($permission->name, 'manage payroll management') !== false) {
                $settings['employee']['manage payroll management'][] = $permission->name;
            } elseif (stripos($permission->name, 'manage pay schedule') !== false) {
                $settings['employee']['manage pay schedule'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees change_photos') !== false) {
                $settings['employee']['change_employee Photos'][] = $permission->name;
            } elseif (stripos($permission->name, 'employees PTO') !== false) {
                $settings['employee']['manage_time_off_policy_assignments'][] = $permission->name;
            } elseif (stripos($permission->name, 'company handbook') !== false) {
                $settings['employee']['company handbook'][] = $permission->name;
            } elseif (stripos($permission->name, 'company holidays') !== false) {
                $settings['employee']['company holidays'][] = $permission->name;
            } elseif (stripos($permission->name, 'manage poll') !== false) {
                $settings['employee']['poll'][] = $permission->name;
            } elseif ($permission->name == 'manage performance review') {
                $settings['employee']['performance review'][] = $permission->name;
            } elseif ($permission->name == 'manage performance review assign') {
                $settings['employee']['performance review assign'][] = $permission->name;
            } elseif ($permission->name == 'manage performance review decision') {
                $settings['employee']['performance review decision'][] = $permission->name;
            } elseif (stripos($permission->name, 'hiring jobopening_candidates') !== false) {
                $settings['hiring']['manage_job_openings_and_candidates'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting education type') !== false) {
                $settings['settings']['education type'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting asset type') !== false) {
                $settings['settings']['asset type'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting visa type') !== false) {
                $settings['settings']['visa type'][] = $permission->name;
            } elseif ($permission->name == 'manage setting document') {
                $settings['settings']['document'][] = $permission->name;
            } elseif ($permission->name == 'manage setting document type') {
                $settings['settings']['document type'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting onboarding') !== false) {
                $settings['settings']['onboarding'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting offboarding') !== false) {
                $settings['settings']['offboarding'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting location') !== false) {
                $settings['settings']['location'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting department') !== false) {
                $settings['settings']['department'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting employment status') !== false) {
                $settings['settings']['employment status'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting designation') !== false) {
                $settings['settings']['designation'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting division') !== false) {
                $settings['settings']['division'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting benefit') !== false) {
                $settings['settings']['benefits'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting time_off') !== false) {
                $settings['settings']['time_off'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting hiring') !== false) {
                $settings['settings']['hiring'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting language') !== false) {
                $settings['settings']['language'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting secondary language') !== false) {
                $settings['settings']['secondary language'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting approval') !== false) {
                $settings['settings']['approvals'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting company_fields') !== false) {
                $settings['settings']['company_field_settings'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting email_alert') !== false) {
                $settings['settings']['email_alerts'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting logo_and_colors') !== false) {
                $settings['settings']['logo_and_color'][] = $permission->name;
            } elseif (stripos($permission->name, 'setting training') !== false) {
                $settings['settings']['training'][] = $permission->name;
            } elseif ($permission->name == 'manage setting smtp details') {
                $settings['settings']['manage smtp details'][] = $permission->name;
            } elseif ($permission->name == 'manage setting compensation') {
                $settings['settings']['compensation'][] = $permission->name;
            }
        }
        return $settings;
    }
}
