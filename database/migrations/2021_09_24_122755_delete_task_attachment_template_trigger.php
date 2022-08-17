<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTaskAttachmentTemplateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        CREATE TRIGGER DELETE_CHILD_ROW BEFORE DELETE ON PARANT_TABLE
//FOR EACH ROW
//    BEGIN
//DELETE FROM CHILD_TABLE WHERE PARANT_ID=OLD.PARANT_ID;
//END;
        //trigger before task delete because trigger doesn't fire with cascade
        DB::unprepared('CREATE TRIGGER delete_task_attachment_template_trigger BEFORE DELETE ON task_attachment_templates FOR EACH ROW
       BEGIN
          INSERT INTO task_attachment_template_histories (id,document_id,task_id,action,created_at) VALUES ( OLD.id,OLD.document_id,OLD.task_id,"delete",NOW());
       END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_task_attachment_template_trigger`');
    }
}
