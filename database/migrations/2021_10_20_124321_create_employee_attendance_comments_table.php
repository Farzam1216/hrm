<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendanceCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendance_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_attendance_id');
            $table->string('comment');
            $table->unsignedInteger('comment_added_by');
            $table->foreign('employee_attendance_id')
                ->references('id')->on('employee_attendances')
                ->onDelete('cascade');
            $table->foreign('comment_added_by')
                ->references('id')->on('employees')
                ->onDelete('cascade');
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
        Schema::dropIfExists('employee_attendance_comments');
    }
}
