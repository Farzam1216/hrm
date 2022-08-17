<?php

use Illuminate\Database\Migrations\Migration;

class CreateUpdateQuestionTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER update_question BEFORE UPDATE ON questions FOR EACH ROW
        BEGIN
        
           INSERT INTO question_histories (que_id, que_desc,que_type,que_field, created_at) VALUES ( OLD.id, OLD.question, OLD.type_id,OLD.fieldType, LOCALTIME());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `update_question` ');
    }
}
