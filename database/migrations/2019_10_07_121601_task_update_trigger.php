<?php

use Illuminate\Database\Migrations\Migration;

class TaskUpdateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //FIXME:: I think we don't need to track the history..Remove after completing the employee task feature
//        DB::unprepared('CREATE TRIGGER task_update_trigger BEFORE UPDATE ON tasks FOR EACH ROW
//       BEGIN
//          INSERT INTO tasks_histories (id,name,description,assigned_to,category,type,due_date,assigned_for_all_employees,action,created_at) VALUES ( OLD.id, OLD.name,OLD.description,OLD.assigned_to,OLD.category,OLD.type,OLD.due_date,OLD.assigned_for_all_employees,"update",NOW());
//       END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `task_update_trigger`');
    }
}
