<?php

use Illuminate\Database\Migrations\Migration;

class EmployeeTasksDeleteTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Update it accordingly when work on assign template to employees feature
//        DB::unprepared('CREATE TRIGGER employee_task_delete_trigger BEFORE DELETE ON employee_tasks FOR EACH ROW
//       BEGIN
//          INSERT INTO employee_tasks_histories (id,task_id,assigned_by,assigned_to,assigned_for,status,status_value,task_completion_status,action,created_at) VALUES ( OLD.id, OLD.task_id,OLD.assigned_by,OLD.assigned_to,OLD.assigned_for,OLD.status,OLD.status_value,OLD.task_completion_status,"delete",NOW());
//       END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `employee_task_delete_trigger`');
    }
}
