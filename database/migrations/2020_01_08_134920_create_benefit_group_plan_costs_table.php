<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitGroupPlanCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_group_plan_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_plan_id');
            $table->foreign('group_plan_id')->references('id')->on('benefit_group_plans')->onDelete('cascade');
            $table->unsignedBigInteger('coverage_id');
            $table->foreign('coverage_id')->references('id')->on('benefit_plan_coverages')->onDelete('cascade');
            $table->string('employee_cost');
            $table->string('company_cost');
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
        Schema::dropIfExists('benefit_group_plan_costs');
    }
}
