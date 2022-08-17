<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitStatusDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_status_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->string('future_message');
            $table->string('current_message');
            $table->string('status_edit_form');
            $table->JSON('status_lists');
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
        Schema::dropIfExists('benefit_status_details');
    }
}
