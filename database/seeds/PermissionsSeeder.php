<?php

use App\Domain\ACL\Models\Permission;
use App\Domain\Benefit\Actions\ManageBenefitPlanPermissions;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Services\TimeOffService;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // employee personal details
        // employee basic information
        Permission::firstOrCreate(['name' => 'view employee id']);
        Permission::firstOrCreate(['name' => 'edit employee id']);
        Permission::firstOrCreate(['name' => 'view employee firstname']);
        Permission::firstOrCreate(['name' => 'edit employee firstname']);
        Permission::firstOrCreate(['name' => 'view employee lastname']);
        Permission::firstOrCreate(['name' => 'edit employee lastname']);
        Permission::firstOrCreate(['name' => 'view employee date_of_birth']);
        Permission::firstOrCreate(['name' => 'edit employee date_of_birth']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee date_of_birth']);
        Permission::firstOrCreate(['name' => 'view employee gender']);
        Permission::firstOrCreate(['name' => 'edit employee gender']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee gender']);
        Permission::firstOrCreate(['name' => 'view employee nin']);
        Permission::firstOrCreate(['name' => 'edit employee nin']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee nin']);
        Permission::firstOrCreate(['name' => 'view employee marital_status']);
        Permission::firstOrCreate(['name' => 'edit employee marital_status']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee marital_status']);
        Permission::firstOrCreate(['name' => 'view benefitgroup benefit_group']);

        /* TODO:: fields not included in our DB but should be included
        Permission::firstOrCreate(['name' => 'edit_with_approval employee NIN']);
          Permission::firstOrCreate(['name' => 'view employee SIN']);
        Permission::firstOrCreate(['name' => 'edit employee SIN']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee SIN']);
         Permission::firstOrCreate(['name' => 'view employee SSN']);
        Permission::firstOrCreate(['name' => 'edit employee SSN']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee SSN']);*/

        // employee address details
        Permission::firstOrCreate(['name' => 'view employee current_address']);
        Permission::firstOrCreate(['name' => 'edit employee current_address']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee current_address']);
        Permission::firstOrCreate(['name' => 'view employee permanent_address']);
        Permission::firstOrCreate(['name' => 'edit employee permanent_address']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee permanent_address']);
        Permission::firstOrCreate(['name' => 'view employee city']);
        Permission::firstOrCreate(['name' => 'edit employee city']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee city']);
        Permission::firstOrCreate(['name' => 'view employee state']);
        Permission::firstOrCreate(['name' => 'edit employee state']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee state']);
        Permission::firstOrCreate(['name' => 'view employee zip_code']);
        Permission::firstOrCreate(['name' => 'edit employee zip_code']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee zip_code']);
        Permission::firstOrCreate(['name' => 'view employee country']);
        Permission::firstOrCreate(['name' => 'edit employee country']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee country']);

        // employee contact details
        Permission::firstOrCreate(['name' => 'view employee contact_no']);
        Permission::firstOrCreate(['name' => 'edit employee contact_no']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee contact_no']);
        Permission::firstOrCreate(['name' => 'view employee personal_email']);
        Permission::firstOrCreate(['name' => 'edit employee personal_email']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee personal_email']);
        Permission::firstOrCreate(['name' => 'view employee official_email']);
        Permission::firstOrCreate(['name' => 'edit employee official_email']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee official_email']);

        //employee education detials
        Permission::firstOrCreate(['name' => 'view education']);
        Permission::firstOrCreate(['name' => 'edit education']);
        Permission::firstOrCreate(['name' => 'edit_with_approval education']);
        Permission::firstOrCreate(['name' => 'view education institute_name']);
        Permission::firstOrCreate(['name' => 'edit education institute_name']);
        Permission::firstOrCreate(['name' => 'edit_with_approval education institute_name']);
        Permission::firstOrCreate(['name' => 'view education major']);
        Permission::firstOrCreate(['name' => 'edit education major']);
        Permission::firstOrCreate(['name' => 'edit_with_approval education major']);
        Permission::firstOrCreate(['name' => 'view educationtype education_type']);
        Permission::firstOrCreate(['name' => 'edit educationtype education_type']);
        Permission::firstOrCreate(['name' => 'edit_with_approval educationtype education_type']);
        Permission::firstOrCreate(['name' => 'view education cgpa']);
        Permission::firstOrCreate(['name' => 'edit education cgpa']);
        Permission::firstOrCreate(['name' => 'edit_with_approval education cgpa']);
        Permission::firstOrCreate(['name' => 'view education date_start']);
        Permission::firstOrCreate(['name' => 'edit education date_start']);
        Permission::firstOrCreate(['name' => 'edit_with_approval education date_start']);
        Permission::firstOrCreate(['name' => 'view education date_end']);
        Permission::firstOrCreate(['name' => 'edit education date_end']);
        Permission::firstOrCreate(['name' => 'edit_with_approval education date_end']);
        //secondary language
        Permission::firstOrCreate(['name' => 'view secondarylanguage name']);
        Permission::firstOrCreate(['name' => 'edit secondarylanguage name']);
        Permission::firstOrCreate(['name' => 'edit_with_approval secondarylanguage name']);
        //employee visa information
        Permission::firstOrCreate(['name' => 'view visatype visa_type']);
        Permission::firstOrCreate(['name' => 'edit visatype visa_type']);
        Permission::firstOrCreate(['name' => 'view visa']);
        Permission::firstOrCreate(['name' => 'edit visa']);
        Permission::firstOrCreate(['name' => 'view employeevisa issue_date']);
        Permission::firstOrCreate(['name' => 'edit employeevisa issue_date']);
        Permission::firstOrCreate(['name' => 'view employeevisa country_id']);
        Permission::firstOrCreate(['name' => 'edit employeevisa country_id']);
        Permission::firstOrCreate(['name' => 'view employeevisa expire_date']);
        Permission::firstOrCreate(['name' => 'edit employeevisa expire_date']);
        Permission::firstOrCreate(['name' => 'view employeevisa note']);
        Permission::firstOrCreate(['name' => 'edit employeevisa note']);
        //employment status
        Permission::firstOrCreate(['name' => 'view employeeemploymentstatus employment_status_id']);
        Permission::firstOrCreate(['name' => 'edit employeeemploymentstatus employment_status_id']);
        Permission::firstOrCreate(['name' => 'view employeeemploymentstatus effective_date']);
        Permission::firstOrCreate(['name' => 'edit employeeemploymentstatus effective_date']);
        Permission::firstOrCreate(['name' => 'view employeeemploymentstatus comments']);
        Permission::firstOrCreate(['name' => 'edit employeeemploymentstatus comments']);

        //job information
        //TODO:: Missing in our system
        Permission::firstOrCreate(['name' => 'view employee joining_date']);
        Permission::firstOrCreate(['name' => 'edit employee joining_date']);
        Permission::firstOrCreate(['name' => 'view employeejob effective_date']);
        Permission::firstOrCreate(['name' => 'edit employeejob effective_date']);
        Permission::firstOrCreate(['name' => 'view employeejob report_to']);
        Permission::firstOrCreate(['name' => 'edit employeejob report_to']);
        Permission::firstOrCreate(['name' => 'view employeejob designation_id']);
        Permission::firstOrCreate(['name' => 'edit employeejob designation_id']);
        Permission::firstOrCreate(['name' => 'view employeejob department_id']);
        Permission::firstOrCreate(['name' => 'edit employeejob department_id']);
        Permission::firstOrCreate(['name' => 'view employeejob division_id']);
        Permission::firstOrCreate(['name' => 'edit employeejob division_id']);
        Permission::firstOrCreate(['name' => 'view employeejob location_id']);
        Permission::firstOrCreate(['name' => 'edit employeejob location_id']);
        Permission::firstOrCreate(['name' => 'view division name']);
        Permission::firstOrCreate(['name' => 'edit division name']);
        Permission::firstOrCreate(['name' => 'view department name']);
        Permission::firstOrCreate(['name' => 'edit department name']);
        Permission::firstOrCreate(['name' => 'view location name']);
        Permission::firstOrCreate(['name' => 'edit location name']);
        //direct reports
        Permission::firstOrCreate(['name' => 'view employee reports_to']);
        Permission::firstOrCreate(['name' => 'edit employee reports_to']);
        Permission::firstOrCreate(['name' => 'view employee manager_id']);
        Permission::firstOrCreate(['name' => 'edit employee manager_id']);
        //time off
        Permission::firstOrCreate(['name' => 'request timeofftype all']);
        Permission::firstOrCreate(['name' => 'view timeofftype time_off_type_name']);
        Permission::firstOrCreate(['name' => 'edit timeofftype time_off_type_name']);
        Permission::firstOrCreate(['name' => 'view policy policy_name']);
        Permission::firstOrCreate(['name' => 'edit policy policy_name']);
        //emergency contact
        Permission::firstOrCreate(['name' => 'view employee emergency_contact']);
        Permission::firstOrCreate(['name' => 'edit employee emergency_contact']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee emergency_contact']);
        Permission::firstOrCreate(['name' => 'view employee emergency_contact_relationship']);
        Permission::firstOrCreate(['name' => 'edit employee emergency_contact_relationship']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employee emergency_contact_relationship']);
        //employee benefit
        Permission::firstOrCreate(['name' => 'view employeebenefit company_payment']);
        Permission::firstOrCreate(['name' => 'view what company pays for each benefit']);
        Permission::firstOrCreate(['name' => 'view benefitgroup name']);
        Permission::firstOrCreate(['name' => 'edit benefitgroup name']);
        Permission::firstOrCreate(['name' => 'view employee benefits history']);
        Permission::firstOrCreate(['name' => 'edit employee benefits history']);
        //employeedependents
        Permission::firstOrCreate(['name' => 'view employeedependent first_name']);
        Permission::firstOrCreate(['name' => 'edit employeedependent first_name']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent first_name']);
        Permission::firstOrCreate(['name' => 'view employeedependent last_name']);
        Permission::firstOrCreate(['name' => 'edit employeedependent last_name']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent last_name']);
        Permission::firstOrCreate(['name' => 'view employeedependent middle_name']);
        Permission::firstOrCreate(['name' => 'edit employeedependent middle_name']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent middle_name']);
        Permission::firstOrCreate(['name' => 'view employeedependent date_of_birth']);
        Permission::firstOrCreate(['name' => 'edit employeedependent date_of_birth']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent date_of_birth']);
        Permission::firstOrCreate(['name' => 'view employeedependent SSN']);
        Permission::firstOrCreate(['name' => 'edit employeedependent SSN']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent SSN']);
        Permission::firstOrCreate(['name' => 'view employeedependent SIN']);
        Permission::firstOrCreate(['name' => 'edit employeedependent SIN']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent SIN']);
        Permission::firstOrCreate(['name' => 'view employeedependent gender']);
        Permission::firstOrCreate(['name' => 'edit employeedependent gender']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent gender']);
        Permission::firstOrCreate(['name' => 'view employeedependent relationship']);
        Permission::firstOrCreate(['name' => 'edit employeedependent relationship']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent relationship']);
        Permission::firstOrCreate(['name' => 'view employeedependent street1']);
        Permission::firstOrCreate(['name' => 'edit employeedependent street1']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent street1']);
        Permission::firstOrCreate(['name' => 'view employeedependent street2']);
        Permission::firstOrCreate(['name' => 'edit employeedependent street2']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent street2']);
        Permission::firstOrCreate(['name' => 'view employeedependent city']);
        Permission::firstOrCreate(['name' => 'edit employeedependent city']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent city']);
        Permission::firstOrCreate(['name' => 'view employeedependent state']);
        Permission::firstOrCreate(['name' => 'edit employeedependent state']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent state']);
        Permission::firstOrCreate(['name' => 'view employeedependent zip']);
        Permission::firstOrCreate(['name' => 'edit employeedependent zip']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent zip']);
        Permission::firstOrCreate(['name' => 'view employeedependent country']);
        Permission::firstOrCreate(['name' => 'edit employeedependent country']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent country']);
        Permission::firstOrCreate(['name' => 'view employeedependent home_phone']);
        Permission::firstOrCreate(['name' => 'edit employeedependent home_phone']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent home_phone']);
        Permission::firstOrCreate(['name' => 'view employeedependent is_us_citizen']);
        Permission::firstOrCreate(['name' => 'edit employeedependent is_us_citizen']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent is_us_citizen']);
        Permission::firstOrCreate(['name' => 'view employeedependent is_student']);
        Permission::firstOrCreate(['name' => 'edit employeedependent is_student']);
        Permission::firstOrCreate(['name' => 'edit_with_approval employeedependent is_student']);
        // employee attendance
        Permission::firstOrCreate(['name' => 'view employee_attendance']);
        Permission::firstOrCreate(['name' => 'edit employee_attendance']);
        //assets
        Permission::firstOrCreate(['name' => 'view asset asset_category']);
        Permission::firstOrCreate(['name' => 'edit asset asset_category']);
        Permission::firstOrCreate(['name' => 'edit_with_approval asset asset_category']);
        Permission::firstOrCreate(['name' => 'view asset asset_description']);
        Permission::firstOrCreate(['name' => 'edit asset asset_description']);
        Permission::firstOrCreate(['name' => 'edit_with_approval asset asset_description']);
        Permission::firstOrCreate(['name' => 'view asset serial']);
        Permission::firstOrCreate(['name' => 'edit asset serial']);
        Permission::firstOrCreate(['name' => 'edit_with_approval asset serial']);
        Permission::firstOrCreate(['name' => 'view asset assign_date']);
        Permission::firstOrCreate(['name' => 'edit asset assign_date']);
        Permission::firstOrCreate(['name' => 'edit_with_approval asset assign_date']);
        //Manager
        //documents
        Permission::firstOrCreate(['name' => 'view documenttype document_type_name']);
        Permission::firstOrCreate(['name' => 'edit documenttype document_type_name']);
        Permission::firstOrCreate(['name' => 'view employeedocument doc_name']);
        Permission::firstOrCreate(['name' => 'edit employeedocument doc_name']);
        Permission::firstOrCreate(['name' => 'view resume and application']);
        Permission::firstOrCreate(['name' => 'edit resume and application']);
        //notes
        Permission::firstOrCreate(['name' => 'view note note']);
        Permission::firstOrCreate(['name' => 'edit note note']);
        //onboarding and offboarding
        Permission::firstOrCreate(['name' => 'view onboarding tab']);
        Permission::firstOrCreate(['name' => 'edit onboarding tab']);
        Permission::firstOrCreate(['name' => 'view offboarding tab']);
        Permission::firstOrCreate(['name' => 'edit offboarding tab']);
        //custom
        //employee
        Permission::firstOrCreate(['name' => 'manage employees store']);
        Permission::firstOrCreate(['name' => 'manage employees terminate']);
        Permission::firstOrCreate(['name' => 'manage employees change_photos']);
        Permission::firstOrCreate(['name' => 'manage employees PTO']);
        Permission::firstOrCreate(['name' => 'manage employees work_schedule']);
        Permission::firstOrCreate(['name' => 'manage employees attendance']);
        //manage job opening and candidates
        Permission::firstOrCreate(['name' => 'manage hiring jobopening_candidates']);
        //settings
        Permission::firstOrCreate(['name' => 'manage setting education type']);
        Permission::firstOrCreate(['name' => 'manage setting asset type']);
        Permission::firstOrCreate(['name' => 'manage setting visa type']);
        Permission::firstOrCreate(['name' => 'manage setting document']);
        Permission::firstOrCreate(['name' => 'manage setting document type']);
        Permission::firstOrCreate(['name' => 'manage setting onboarding']);
        Permission::firstOrCreate(['name' => 'manage setting offboarding']);
        Permission::firstOrCreate(['name' => 'manage setting location']);
        Permission::firstOrCreate(['name' => 'manage setting department']);
        Permission::firstOrCreate(['name' => 'manage setting employment status']);
        Permission::firstOrCreate(['name' => 'manage setting designation']);
        Permission::firstOrCreate(['name' => 'manage setting division']);
        Permission::firstOrCreate(['name' => 'manage setting benefit']);
        Permission::firstOrCreate(['name' => 'manage setting time_off']);
        Permission::firstOrCreate(['name' => 'manage setting hiring']);
        Permission::firstOrCreate(['name' => 'manage setting language']);
        Permission::firstOrCreate(['name' => 'manage setting secondary language']);
        Permission::firstOrCreate(['name' => 'manage setting approval']);
        Permission::firstOrCreate(['name' => 'manage setting employee_accesslevel']);
        Permission::firstOrCreate(['name' => 'manage setting company_fields']);
        Permission::firstOrCreate(['name' => 'manage setting email_alert']);
        Permission::firstOrCreate(['name' => 'manage setting logo_and_colors']);
        Permission::firstOrCreate(['name' => 'manage setting training']);
        //
        Permission::firstOrCreate(['name' => 'edit employeebenefit company_payment']);
        Permission::firstOrCreate(['name' => 'can_see_own_info']);
        Permission::firstOrCreate(['name' => 'cant_see_own_info']);
        // Company Handbook permission
        Permission::firstOrCreate(['name' => 'manage company handbook']);
        // Company Holidays permission
        Permission::firstOrCreate(['name' => 'manage company holidays']);
        // Poll permission
        Permission::firstOrCreate(['name' => 'manage poll']);
        // Performance Review permission
        Permission::firstOrCreate(['name' => 'manage performance review']);
        Permission::firstOrCreate(['name' => 'manage performance review assign']);
        Permission::firstOrCreate(['name' => 'manage performance review decision']);
         // Payroll management
        Permission::firstOrCreate(['name' => 'manage payroll management']);
         // Pay Schedule
        Permission::firstOrCreate(['name' => 'manage pay schedule']);
         // Smtp Details
        Permission::firstOrCreate(['name' => 'manage setting smtp details']);
        // Manage Time Off Request Decision
        Permission::firstOrCreate(['name' => 'manage request time off decision']);
         // Compensation
        Permission::firstOrCreate(['name' => 'manage setting compensation']);

        //Create permissions for all time off types
        $TimeOffTypes = TimeOffType::all();
        if ($TimeOffTypes->isNotEmpty()) {
            foreach ($TimeOffTypes as $timeOffType) {
                (new TimeOffService)->createTimeOffTypesAndPoliciesPermissions('timeofftype', $timeOffType->id);
            }
        }

        //Create permissions for all time off policies
        $timeOffPolicies = Policy::all();
        if ($timeOffPolicies->isNotEmpty()) {
            foreach ($timeOffPolicies as $timeOffPolicy) {
                (new TimeOffService)->createTimeOffTypesAndPoliciesPermissions('policy', $timeOffPolicy->id);
            }
        }

        //Create permissions for all benefit plans
        $benefitPlans = BenefitPlan::all();
        if ($benefitPlans->isNotEmpty()) {
            foreach ($benefitPlans as $benefitPlan) {
                (new ManageBenefitPlanPermissions())->execute('benefitplan', $benefitPlan->id, 'create ');
            }
        }
    }
}
