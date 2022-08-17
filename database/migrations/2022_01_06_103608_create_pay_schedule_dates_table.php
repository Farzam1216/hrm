<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayScheduleDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_schedule_dates', function (Blueprint $table) {
            $table->id();
            $table->string('period_start');
            $table->string('period_end');
            $table->string('pay_date');
            $table->string('adjustment');
            $table->unsignedBigInteger('pay_schedule_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pay_schedule_id')->references('id')->on('pay_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_schedule_dates');
    }
}
