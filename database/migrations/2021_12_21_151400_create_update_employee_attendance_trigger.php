<?php

use Illuminate\Database\Migrations\Migration;

class CreateUpdateEmployeeAttendanceTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_employee_attendance_trigger BEFORE UPDATE ON employee_attendances FOR EACH ROW
            BEGIN
            INSERT INTO employee_attendance_histories (attendance_id, time_in, time_out, time_in_status, attendance_status, reason_for_leaving, employee_id, created_at) VALUES (Old.id, Old.time_in, Old.time_out, Old.time_in_status, Old.attendance_status, OLD.reason_for_leaving, OLD.employee_id, LOCALTIME());
            END'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_employee_attendance_trigger`');
    }
}
