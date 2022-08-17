<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_no')->unique()->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('contact_no')->nullable();
            $table->string('official_email')->unique();
            $table->string('personal_email')->unique()->nullable();
            $table->string('nin')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('password');
            $table->boolean('can_mark_attendance')->nullable();
            $table->string('current_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('designation_id')->nullable();
            $table->foreign('designation_id')
                ->references('id')->on('designations')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('type')->comment('work from remote/office')->default('office');
            $table->integer('status')->default(1);
            $table->unsignedInteger('employment_status_id')->nullable();
            $table->foreign('employment_status_id')
                ->references('id')->on('employment_statuses')
                ->onDelete('cascade');
            //TODO:: Remove education_id and Visa_id
            $table->unsignedInteger('education_id')->nullable();
            $table->unsignedInteger('visa_id')->nullable();
            $table->string('picture')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')
                ->references('id')->on('locations')
                ->onDelete('set null');
            $table->integer('zuid')->default(0);
            $table->integer('account_id')->default(0);
            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')
                ->references('id')->on('departments')
                ->onDelete('cascade');
            $table->unsignedInteger('work_schedule_id')->nullable();
            $table->boolean('invite_to_zoho')->nullable();
            $table->boolean('invite_to_slack')->nullable();
            $table->boolean('invite_to_asana')->nullable();
            $table->unsignedInteger('manager_id')->nullable();
            $table->datetime('last_login')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        //Foreign Key constraints can be added once the table is created already
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('manager_id')
                ->references('id')->on('employees')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
