<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeleteEmployeeBenefitStatusTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER delete_employee_benefit_status_trigger BEFORE DELETE ON employee_benefit_statuses FOR EACH ROW
        BEGIN
           INSERT INTO employee_benefit_status_histories (employee_benefit_status_id, employee_benefit_id,effective_date, enrollment_status,
           enrollment_status_tracking_field, created_at) VALUES ( old.id, old.employee_benefit_id, old.effective_date, old.enrollment_status,
           old.enrollment_status_tracking_field, LOCALTIME());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_employee_benefit_status_trigger`');
    }
}
