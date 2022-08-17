<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class CreateDeleteQuestionTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now=Carbon::now();
        DB::unprepared('CREATE TRIGGER delete_question BEFORE DELETE ON questions FOR EACH ROW
        BEGIN
        
           INSERT INTO question_histories (que_id, que_desc,que_type,que_field, created_at, updated_at) VALUES ( OLD.id, OLD.question, OLD.type_id,OLD.fieldType, LOCALTIME(), null);
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_question` ');
    }
}
