<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRequestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_requesters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('approval_id');
            $table->foreign('approval_id')->references('id')->on('approvals')->onDelete('cascade');
            $table->json('approval_requester_data');
            $table->unsignedInteger('advance_approval_option_id')->nullable();
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
        Schema::dropIfExists('approval_requesters');
    }
}
