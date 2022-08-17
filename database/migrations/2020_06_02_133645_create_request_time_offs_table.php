<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTimeOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_time_offs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assign_timeoff_type_id');
            $table->date('to');
            $table->date('from');
            $table->text('note')->nullable();
            $table->string('status');
            $table->unsignedInteger('employee_id');
            $table->foreign('assign_timeoff_type_id')
                ->references('id')
                ->on('assign_time_off_types')
                ->onDelete('cascade');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_time_offs');
    }
}
