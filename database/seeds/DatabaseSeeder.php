<?php

use Database\Seeders\WorkScheduleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountrySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(PermissionsSeeder::class);
        // $this->call(EmploymentStatusSeeder::class);
        $this->call(RoleSeeder::class); //make it before employees
        // $this->call(BranchSeeder::class); //TODO: erorr
        // $this->call(DesignationSeeder::class);
        // $this->call(DepartmentSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(BenefitPlanTypeSeeder::class);
        $this->call(BenefitEnrollmentStatusSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(AccessLevelSeeder::class);
        $this->call(ApprovalTypeSeeder::class); //make it before approvals
        $this->call(ApprovalSeeder::class);
        $this->call(ApprovalWorkflowSeeder::class); //make it after Approvals seeder
        $this->call(ApprovalRequesterSeeder::class);
        $this->call(ApprovalRequestedFieldSeeder::class);
        $this->call(EmployeeRoleSeeder::class);
        $this->call(ApprovalFormFieldsSeeder::class);
        $this->call(QuestionTypesSeeder::class);
        // $this->call(WorkScheduleSeeder::class);
        $this->call(HrManagerRolePermissionSeeder::class);
        $this->call(ManagerRolePermissionSeeder::class);
    }
}
