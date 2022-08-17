<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceApprovalWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_approval_workflows', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('approval_workflow_id');
            $table->foreign('approval_workflow_id')->references('id')->on('approval_workflows')->onDelete('cascade');
            $table->unsignedInteger('advance_approval_option_id');
            $table->foreign('advance_approval_option_id')->references('id')->on('advance_approval_options')->onDelete('cascade');
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
        Schema::dropIfExists('advance_approval_workflows');
    }
}
