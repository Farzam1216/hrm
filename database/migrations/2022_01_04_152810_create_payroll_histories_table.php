<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_histories', function (Blueprint $table) {
            $table->id();
            $table->string('basic_salary');
            $table->string('home_allowance')->nullable()->default(0);
            $table->string('travel_expense')->nullable()->default(0);
            $table->string('income_tax')->nullable()->default(0);
            $table->string('bonus')->nullable()->default(0);
            $table->string('custom_deduction')->nullable()->default(0);
            $table->string('absent_count')->nullable()->default(0);
            $table->string('deduction')->nullable()->default(0);
            $table->string('net_payable');
            $table->string('month_year');
            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('payroll_histories');
    }
}
