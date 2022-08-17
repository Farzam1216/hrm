<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            //don't delete employee task on task deletion
            $table->unsignedInteger('task_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('category_name')->nullable();
            $table->integer('type');
            $table->string('calculated_due_date')->nullable();
            $table->unsignedInteger('assigned_by');
            $table->foreign('assigned_by')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')->references('id')->on('employees')->onDelete('set null');
            $table->dateTime('completed_at')->nullable();
            $table->unsignedInteger('completed_by')->nullable();
            $table->foreign('completed_by')->references('id')->on('employees')->onDelete('set null');

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
        Schema::dropIfExists('employee_tasks');
    }
}
