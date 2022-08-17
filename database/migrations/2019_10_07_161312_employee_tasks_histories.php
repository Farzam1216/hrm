<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeTasksHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('employee_tasks_histories', function (Blueprint $table) {
//            $table->increments('row_id');
//            $table->unsignedInteger('id');
//            $table->unsignedInteger('task_id');
//            $table->unsignedInteger('assigned_by');
//            $table->unsignedInteger('assigned_to')->nullable();
//            $table->unsignedInteger('assigned_for');
//            $table->string('status');
//            $table->string('status_value')->nullable();
//            $table->string('task_completion_status')->nullable();
//            $table->string('action');
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_tasks_histories');
    }
}
