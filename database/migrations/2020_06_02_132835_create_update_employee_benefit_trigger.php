<?php

use Illuminate\Database\Migrations\Migration;

class CreateUpdateEmployeeBenefitTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_employee_benefit_trigger BEFORE UPDATE ON employee_benefits FOR EACH ROW
        BEGIN
        
           INSERT INTO employee_benefit_histories (employee_benefit_id, benefit_group_plan_id,employee_id, employee_benefit_plan_coverage,
           deduction_frequency, employee_payment, company_payment, created_at ) VALUES ( old.id, old.benefit_group_plan_id, old.employee_id, old.employee_benefit_plan_coverage,
           old.deduction_frequency, old.employee_payment, old.company_payment, LOCALTIME());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_employee_benefit_trigger`');
    }
}
