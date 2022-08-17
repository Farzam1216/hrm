<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeletePerformanceQuestionOptionsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER delete_performance_question_options_trigger BEFORE DELETE ON performance_question_options FOR EACH ROW
       BEGIN
          INSERT INTO performance_question_option_histories (option_id, q_option, question_id, created_at) VALUES (OLD.id, Old.option, Old.question_id, LOCALTIME());
       END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_performance_question_options_trigger`');
    }
}
