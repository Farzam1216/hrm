<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeletePerformanceQuestionTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER delete_performance_question_trigger BEFORE DELETE ON performance_questions FOR EACH ROW
            BEGIN
            INSERT INTO performance_question_histories (question_id, question, field_type, placement, status, created_at) VALUES (Old.id, Old.question, Old.field_type, Old.placement, Old.status, LOCALTIME());
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
        DB::unprepared('DROP TRIGGER `delete_performance_question_trigger`');
    }
}
