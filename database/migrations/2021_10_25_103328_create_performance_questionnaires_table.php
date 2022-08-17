<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('submitter_id');
            $table->boolean('status')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedInteger('decision_authority_id')->nullable();
            $table->boolean('employee_can_view')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->foreign('submitter_id')->references('id')->on('employees')->onDelete('cascade');

            $table->foreign('decision_authority_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_questionnaires');
    }
}
