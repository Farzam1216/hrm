<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class CreateDeleteJobQuestionTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now=Carbon::now();
        DB::unprepared('CREATE TRIGGER delete_job_question BEFORE DELETE ON job_questions FOR EACH ROW
        BEGIN
        
           INSERT INTO job_question_histories (jobque_id, job_id, que_id,status,created_at) VALUES ( OLD.id,OLD.job_id, OLD.que_id, OLD.status, LOCALTIME());
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `delete_job_question` ');
    }
}
