<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('schedule_start_time');
            $table->string('flex_time_in')->nullable();
            $table->string('schedule_break_time');
            $table->string('schedule_back_time');
            $table->string('schedule_end_time');
            $table->string('schedule_hours');
            $table->string('non_working_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_schedules');
    }
}
