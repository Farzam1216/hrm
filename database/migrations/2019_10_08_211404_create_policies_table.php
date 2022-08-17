<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('policy_name');
            $table->string('policy_type')->nullable();
            $table->string('first_accrual');
            $table->string('carry_over_date');
            $table->string('accrual_happen');
            $table->string('accrual_transition_happend')->nullable();
            $table->unsignedBigInteger('time_off_type');
            $table->foreign('time_off_type')
                ->references('id')
                ->on('time_off_types')
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
        Schema::dropIfExists('policies');
    }
}
