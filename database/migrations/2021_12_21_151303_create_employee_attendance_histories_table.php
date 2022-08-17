<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendance_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('time_in_status')->nullable();
            $table->string('attendance_status')->nullable();
            $table->string('reason_for_leaving')->nullable();
            $table->unsignedInteger('employee_id')->nullable();
            $table->string('changed_by_id')->nullable();
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
        Schema::dropIfExists('employee_attendance_histories');
    }
}
