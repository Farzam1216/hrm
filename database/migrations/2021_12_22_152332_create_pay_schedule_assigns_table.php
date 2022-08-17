<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayScheduleAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_schedule_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pay_schedule_id');
            $table->unsignedInteger('employee_id');
            $table->timestamps();

            $table->foreign('pay_schedule_id')->references('id')->on('pay_schedules')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_schedule_assigns');
    }
}
