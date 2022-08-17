<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalWorkflowHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_workflow_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('approval_workflow_id');
            $table->unsignedInteger('approval_id');
            $table->json('approval_hierarchy');
            $table->unsignedInteger('level_number');
            $table->unsignedInteger('advance_approval_option_id')->nullable();
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
        Schema::dropIfExists('approval_workflow_histories');
    }
}
