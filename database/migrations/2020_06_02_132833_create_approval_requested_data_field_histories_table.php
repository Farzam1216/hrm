<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRequestedDataFieldHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('approval_requested_data_field_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('approval_requested_data_field_id');
            $table->unsignedInteger('requested_field_id')->nullable();
            $table->unsignedInteger('approval_id');
            $table->unsignedInteger('requested_by_id');
            $table->unsignedInteger('requested_for_id');
            $table->json('requested_data');
            $table->unsignedInteger('approval_workflow_id');
            $table->boolean('is_approved')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->mediumText('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('approval_requested_data_field_histories');
    }
}
