<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeBenefitHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_benefit_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_benefit_id');
            $table->unsignedBigInteger('benefit_group_plan_id');
            $table->unsignedInteger('employee_id');
            $table->string('employee_benefit_plan_coverage')->nullable();
            $table->string('deduction_frequency')->nullable();
            $table->json('employee_payment')->nullable();
            $table->json('company_payment')->nullable();
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
        Schema::dropIfExists('employee_benefit_histories');
    }
}
