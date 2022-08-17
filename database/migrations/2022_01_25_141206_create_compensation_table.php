<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompensationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compensation', function (Blueprint $table) {
            $table->id();
            $table->string('effective_date');
            $table->unsignedBigInteger('pay_schedule_id');
            $table->string('pay_type');
            $table->string('pay_rate');
            $table->string('pay_rate_frequency');
            $table->string('overtime_status');
            $table->unsignedBigInteger('change_reason_id');
            $table->string('comment');
            $table->string('status')->default('inactive');
            $table->unsignedInteger('employee_id');
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
        Schema::dropIfExists('compensation');
    }
}
