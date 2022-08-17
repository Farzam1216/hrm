<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEmploymentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_employment_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('cascade');

            $table->date('effective_date');

            $table->unsignedInteger('employment_status_id');
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses')
                ->onDelete('cascade');

            $table->mediumText('comment')->nullable();
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
        Schema::dropIfExists('employee_employment_statuses');
    }
}
