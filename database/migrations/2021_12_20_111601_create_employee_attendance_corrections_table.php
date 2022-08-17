<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendanceCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendance_corrections', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('time_in_status')->nullable();
            $table->string('attendance_status')->nullable();
            $table->text('reason_for_leaving')->nullable();
            $table->string('status')->default('pending');
            $table->string('total_entries')->nullable();
            $table->string('attendance_id')->nullable();
            $table->string('remove_attendance_id')->nullable();
            $table->unsignedInteger('employee_id');
            $table->timestamps();

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
        Schema::dropIfExists('employee_attendance_corrections');
    }
}
