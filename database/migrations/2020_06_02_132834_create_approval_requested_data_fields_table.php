<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRequestedDataFieldsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('approval_requested_data_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requested_field_id')->nullable();
            $table->foreign('requested_field_id')->references('id')->on('approval_requested_fields');
            $table->unsignedInteger('requested_by_id');
            $table->foreign('requested_by_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedInteger('approval_id');
            $table->foreign('approval_id')->references('id')->on('approvals')->onDelete('cascade');
            $table->unsignedInteger('requested_for_id');
            $table->foreign('requested_for_id')->references('id')->on('employees')->onDelete('cascade');
            $table->json('requested_data');
            $table->unsignedInteger('approval_workflow_id');
            $table->foreign('approval_workflow_id')->references('id')->on('approval_workflows')->onDelete('cascade');
            $table->boolean('is_approved')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('cascade');
            $table->mediumText('comments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('approval_requested_data_fields');
    }
}
