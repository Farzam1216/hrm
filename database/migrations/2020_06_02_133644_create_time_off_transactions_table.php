<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeOffTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_off_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assign_time_off_id');
            $table->string('action');
            $table->date('accrual_date');
            $table->double('balance');
            $table->double('hours_accrued');
            $table->string('note')->nullable();
            $table->unsignedInteger('employee_id');
            $table->foreign('assign_time_off_id')
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
        Schema::dropIfExists('time_off_transactions');
    }
}
