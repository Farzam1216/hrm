<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('start_date');
            $table->unsignedBigInteger('plan_type_id');
            $table->foreign('plan_type_id')->references('id')->on('benefit_plan_types')->onDelete('cascade');
            $table->date('end_date')->nullable();
            $table->string('plan_cost_rate')->nullable();
            $table->string('description')->nullable();
            $table->string('plan_URL')->nullable();
            $table->integer('reimbursement_amount')->nullable();
            $table->string('reimbursement_frequency')->nullable();
            $table->string('reimbursement_currency')->nullable();
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
        Schema::dropIfExists('benefit_plans');
    }
}
