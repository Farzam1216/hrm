<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignTimeOffTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_time_off_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('employee_id');
            $table->unsignedBigInteger('type_id');
            $table->string('accrual_option');
            $table->unsignedBigInteger('attached_policy_id')->nullable();

            $table->foreign('type_id')
                ->references('id')
                ->on('time_off_types')
                ->onDelete('cascade');
            $table->foreign('attached_policy_id')
                ->references('id')
                ->on('policies')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
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
        Schema::dropIfExists('assign_time_off_types');
    }
}
