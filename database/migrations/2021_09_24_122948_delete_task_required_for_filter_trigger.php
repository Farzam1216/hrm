<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTaskRequiredForFilterTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER delete_task_required_for_filter_trigger BEFORE DELETE ON task_required_for_filters FOR EACH ROW
       BEGIN
          INSERT INTO task_required_for_filter_histories (id,task_id,filter_type,filter_id,action,created_at) VALUES ( OLD.id,OLD.task_id,OLD.filter_type, OLD.filter_id,"delete",NOW());
       END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_task_required_for_filter_trigger`');
    }
}
