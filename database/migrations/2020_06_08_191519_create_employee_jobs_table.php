<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('effective_date');

            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade');

            $table->unsignedInteger('designation_id');
            $table->foreign('designation_id')
                ->references('id')->on('designations')
                ->onDelete('cascade');

            $table->unsignedInteger('report_to')->nullable();
            $table->foreign('report_to')
                ->references('id')->on('employees')
                ->onDelete('set null');

            $table->unsignedInteger('department_id');
            $table->foreign('department_id')
                ->references('id')->on('departments')
                ->onDelete('cascade');

            $table->unsignedInteger('division_id');
            $table->foreign('division_id')
                ->references('id')->on('divisions')
                ->onDelete('cascade');

            $table->unsignedInteger('location_id');
            $table->foreign('location_id')
                ->references('id')->on('locations')
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
        Schema::dropIfExists('employee_jobs');
    }
}
