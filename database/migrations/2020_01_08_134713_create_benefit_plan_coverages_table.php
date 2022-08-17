<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitPlanCoveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_plan_coverages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('coverage_name');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('benefit_plans')->onDelete('cascade');
            $table->integer('total_cost')->nullable();
            $table->string('cost_currency')->nullable();
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
        Schema::dropIfExists('benefit_plan_coverages');
    }
}
