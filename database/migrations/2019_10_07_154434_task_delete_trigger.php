<?php

use Illuminate\Database\Migrations\Migration;

class TaskDeleteTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER task_delete_trigger BEFORE DELETE ON tasks FOR EACH ROW
       BEGIN
          INSERT INTO tasks_histories (id,name,description,assigned_to,category,type,due_date,period,assigned_for_all_employees,action,created_at) VALUES ( OLD.id, OLD.name,OLD.description,OLD.assigned_to,OLD.category,OLD.type,OLD.due_date,OLD.period,OLD.assigned_for_all_employees,"delete",NOW());
       END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `task_delete_trigger`');
    }
}
